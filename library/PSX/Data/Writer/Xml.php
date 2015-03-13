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

namespace PSX\Data\Writer;

use PSX\Data\RecordInterface;
use PSX\Data\WriterInterface;
use PSX\Http\MediaType;
use PSX\Xml\Writer;
use XMLWriter;

/**
 * Xml
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Xml implements WriterInterface
{
	public static $mime = 'application/xml';

	protected $writer;

	/**
	 *
	 * If an writer is given the result gets written to the XMLWriter and the
	 * write method returns null. Otherwise the write method returns the xml as
	 * string
	 *
	 * @param XMLWriter $writer
	 */
	public function __construct(XMLWriter $writer = null)
	{
		$this->writer = $writer;
	}

	public function write(RecordInterface $record)
	{
		$writer = new Writer($this->writer);
		$writer->setRecord($record);

		return $this->writer === null ? $writer->toString() : null;
	}

	public function isContentTypeSupported(MediaType $contentType)
	{
		return $contentType->getName() == self::$mime;
	}

	public function getContentType()
	{
		return self::$mime;
	}
}
