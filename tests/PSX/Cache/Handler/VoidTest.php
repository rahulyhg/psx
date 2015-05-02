<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Cache\Handler;

use PDOException;
use PSX\CacheTest;
use PSX\Sql\Table\ColumnAllocation;

/**
 * VoidTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class VoidTest extends \PHPUnit_Framework_TestCase
{
	public function testLoad()
	{
		$handler = new Void();
		$item    = $handler->load('key');

		$this->assertInstanceOf('PSX\Cache\Item', $item);
		$this->assertEquals('key', $item->getKey());
		$this->assertEquals(null, $item->get());
		$this->assertEquals(false, $item->isHit());
	}

	public function testWrite()
	{
		$handler = new Void();
		$item    = $handler->load('key');

		$item->set('foobar');

		$handler->write($item);
	}

	public function testRemove()
	{
		$handler = new Void();
		$handler->remove('foobar');
	}

	public function testRemoveAll()
	{
		$handler = new Void();
		$handler->removeAll();
	}
}