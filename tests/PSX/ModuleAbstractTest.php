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

use PSX\Http\Request;
use PSX\Module\ModuleTestCase;
use PSX\Url;

/**
 * ModuleAbstractTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class ModuleAbstractTest extends ModuleTestCase
{
	public function testNormalRequest()
	{
		$path    = '/module';
		$request = new Request(new Url('http://127.0.0.1' . $path), 'GET');

		$controller = $this->loadModule($path, $request);
	}

	public function testInnerApi()
	{
		$path    = '/module/inspect';
		$request = new Request(new Url('http://127.0.0.1' . $path), 'GET');

		$controller = $this->loadModule($path, $request);
	}

	protected function getPaths()
	{
		return array(
			'/module' => 'PSX\Module\Foo\Application\TestModule',
		);
	}
}
