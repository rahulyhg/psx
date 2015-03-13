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

namespace PSX\Xml;

use PSX\Data\Record;

/**
 * WriterTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class WriterTest extends \PHPUnit_Framework_TestCase
{
	public function testWriter()
	{
		$writer = new Writer();
		$writer->setRecord(new Record('foo', array(
			'foo1' => 'bar',
			'foo2' => new Record('bar', array(
				'bar1' => 'foo', 
				'bar2' => 'foo',
			)),
			'foo3' => 'bar',
			'foo4' => 'bar',
			'foo5' => 'bar',
		)));

		$actual   = $writer->toString();
		$expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<foo>
  <foo1>bar</foo1>
  <foo2>
    <bar1>foo</bar1>
    <bar2>foo</bar2>
  </foo2>
  <foo3>bar</foo3>
  <foo4>bar</foo4>
  <foo5>bar</foo5>
</foo>
XML;

		$this->assertXmlStringEqualsXmlString($expected, $actual);
	}
}
