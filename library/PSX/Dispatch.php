<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2013 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX;

use DOMDocument;
use PSX\Http\Request;
use PSX\Http\Response;

/**
 * Dispatch
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Dispatch extends \Exception
{
	protected $config;
	protected $loader;

	public function __construct(Config $config, Loader $loader)
	{
		$this->config = $config;
		$this->loader = $loader;
	}

	public function route(Request $request)
	{
		$controller = null;
		
		ob_start();

		try
		{
			// load controller
			$controller = $this->loader->load($this->getPath(), $request);

			// get output
			$content = ob_get_contents();

			// process response
			$body = $controller->processResponse($content);
		}
		catch(\Exception $e)
		{
			$code = Base::getResponseCode();

			if($code === null && isset(Http::$codes[$e->getCode()]))
			{
				$code = $e->getCode();
			}
			else if($code === null)
			{
				$code = 500;
			}

			$accept  = Base::getRequestHeader('Accept');
			$with    = Base::getRequestHeader('X-Requested-With');
			$message = $e->getMessage();
			$trace   = '';

			if($this->config['psx_debug'] === true)
			{
				$message.= ' in ' . $e->getFile() . ' on line ' . $e->getLine();
				$trace   = $e->getTraceAsString();
			}

			// in the best case we have an clean exception where no output was
			// made before if output was already made we append the output to 
			// the error message to save the error context
			$context = ob_get_contents();

			// build response
			if(PHP_SAPI == 'cli')
			{
				if(!empty($context))
				{
					$message = $context . "\n" . $message;
				}

				$body = $message . "\n" . $trace;
			}
			else if($with == 'XMLHttpRequest' || (strpos($accept, 'text/json') !== false || strpos($accept, 'application/json') !== false))
			{
				Base::setResponseCode($code);
				header('Content-type: application/json');

				if(!empty($context))
				{
					$message = $context . "\n" . $message;
				}

				$body = json_encode(array('success' => false, 'message' => $message, 'trace' => $trace));
			}
			else if(strpos($accept, 'text/html') !== false)
			{
				Base::setResponseCode($code);
				header('Content-type: text/html');

				if(!empty($context))
				{
					$message = htmlspecialchars($context) . "\n" . $message;
				}

				$body = $this->getErrorTemplate($message, $trace);
			}
			else if(strpos($accept, 'text/xml') !== false || strpos($accept, 'application/xml') !== false)
			{
				Base::setResponseCode($code);
				header('Content-type: application/xml');

				if(!empty($context))
				{
					$message = $context . "\n" . $message;
				}

				$dom = new DOMDocument();

				$response = $dom->createElement('response');
				$response->appendChild($dom->createElement('success', 'false'));
				$response->appendChild($dom->createElement('message', $message));
				$response->appendChild($dom->createElement('trace', $trace));

				$dom->appendChild($response);

				$body = $dom->saveXML();
			}
			else
			{
				// sorry we have no idea what content to serve so hopefully 
				// plain text is understandable

				Base::setResponseCode($code);
				header('Content-type: text/plain');

				if(!empty($context))
				{
					$message = $context . "\n" . $message;
				}

				$body = $message . "\n" . $trace;
			}
		}

		ob_end_clean();

		// build response
		$response = $this->buildResponse($body);

		// call response filter
		if($controller instanceof ModuleAbstract)
		{
			$filters = $controller->getResponseFilter();

			foreach($filters as $filter)
			{
				$filter->handle($response);
			}
		}

		return $response;
	}

	protected function buildResponse($body)
	{
		$code    = Base::getResponseCode();
		$message = $code === null ? Http::$codes[200] : Http::$codes[$code];
		$header  = Response::headerToArray(implode(Http::$newLine, headers_list()));

		return new Response(Base::getProtocol(), Base::getResponseCode(), $message, $header, $body);
	}

	protected function getPath()
	{
		return isset($_GET['x']) ? $_GET['x'] : (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : ''));
	}

	protected function getErrorTemplate($message, $trace)
	{
		// set default values
		$config = $this->config;
		$self   = isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] : $_SERVER['PHP_SELF'];
		$url    = $config['psx_url'] . '/' . $config['psx_dispatch'];
		$dir    = PSX_PATH_TEMPLATE . '/' . $config['psx_template_dir'];
		$base   = parse_url($config['psx_url'], PHP_URL_PATH);
		$render = round(microtime(true) - $GLOBALS['psx_benchmark'], 6);

		// get template
		ob_start();

		include PSX_PATH_TEMPLATE . '/' . $this->config['psx_template_dir'] . '/error.tpl';

		$template = ob_get_contents();

		ob_end_clean();

		return $template;
	}
}
