<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2014 Christoph Kappestein <k42b3.x@gmail.com>
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

use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Http\Stream\TempStream;
use PSX\Controller\ControllerTestCase;
use PSX\Dispatch\RedirectException;
use PSX\Url;

/**
 * ControllerAbstractTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class ControllerAbstractTest extends ControllerTestCase
{
	public function testNormalRequest()
	{
		$path     = '/controller';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		$controller = $this->loadController($request, $response);

		$this->assertEquals('foobar', (string) $response->getBody());
	}

	public function testInnerApi()
	{
		$path     = '/controller/inspect';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		$controller = $this->loadController($request, $response);
	}

	public function testForward()
	{
		$path     = '/controller/forward';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		$controller = $this->loadController($request, $response);

		$this->assertJsonStringEqualsJsonString(json_encode(array('bar' => 'foo')), (string) $response->getBody());
	}

	public function testRedirect()
	{
		$path     = '/controller/redirect';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		try
		{
			$controller = $this->loadController($request, $response);

			$this->fail('Must throw an redirect exception');
		}
		catch(RedirectException $e)
		{
			$this->assertEquals(302, $e->getStatusCode());
			$this->assertEquals('http://localhost.com/foobar', $e->getUrl());
		}
	}

	public function testSetArrayBody()
	{
		$path     = '/controller/array';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		$controller = $this->loadController($request, $response);

		$expect = <<<JSON
{"foo":["bar"]}
JSON;

		$this->assertJsonStringEqualsJsonString($expect, (string) $response->getBody());
	}

	public function testSetRecordBody()
	{
		$path     = '/controller/record';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		$controller = $this->loadController($request, $response);

		$expect = <<<JSON
{"foo":["bar"]}
JSON;

		$this->assertJsonStringEqualsJsonString($expect, (string) $response->getBody());
	}

	public function testSetDomDocumentBody()
	{
		$path     = '/controller/dom';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		$controller = $this->loadController($request, $response);

		$expect = <<<XML
<?xml version="1.0"?>
<foo>bar</foo>
XML;

		$this->assertXmlStringEqualsXmlString($expect, (string) $response->getBody());
	}

	public function testSetSimpleXmlBody()
	{
		$path     = '/controller/simplexml';
		$request  = new Request(new Url('http://127.0.0.1' . $path), 'GET');
		$response = new Response();
		$response->setBody(new TempStream(fopen('php://memory', 'r+')));

		$controller = $this->loadController($request, $response);

		$expect = <<<XML
<?xml version="1.0"?>
<foo>bar</foo>
XML;

		$this->assertXmlStringEqualsXmlString($expect, (string) $response->getBody());
	}

	protected function getPaths()
	{
		return array(
			'/controller'           => 'PSX\Controller\Foo\Application\TestController::doIndex',
			'/controller/inspect'   => 'PSX\Controller\Foo\Application\TestController::doInspect',
			'/controller/forward'   => 'PSX\Controller\Foo\Application\TestController::doForward',
			'/controller/redirect'  => 'PSX\Controller\Foo\Application\TestController::doRedirect',
			'/controller/array'     => 'PSX\Controller\Foo\Application\TestController::doSetArrayBody',
			'/controller/record'    => 'PSX\Controller\Foo\Application\TestController::doSetRecordBody',
			'/controller/dom'       => 'PSX\Controller\Foo\Application\TestController::doSetDomDocumentBody',
			'/controller/simplexml' => 'PSX\Controller\Foo\Application\TestController::doSetSimpleXmlBody',
			'/api'                  => 'PSX\Controller\Foo\Application\TestApiController::doIndex',
			'/api/insert'           => 'PSX\Controller\Foo\Application\TestApiController::doInsert',
			'/api/inspect'          => 'PSX\Controller\Foo\Application\TestApiController::doInspect',
		);
	}
}
