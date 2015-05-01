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

namespace PSX\Data\Schema\Generator;

/**
 * JsonSchemaTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class JsonSchemaTest extends GeneratorTestCase
{
	public function testGenerate()
	{
		$generator = new JsonSchema();
		$result    = $generator->generate($this->getSchema());

		$expect = <<<'JSON'
{
  "$schema": "http://json-schema.org/draft-04/schema#",
  "id": "urn:schema.phpsx.org#",
  "description": "An general news entry",
  "definitions": {
    "ref6039818cbcfdcc20777fd8c74a5a392d": {
      "type": "object",
      "description": "An simple author element with some description",
      "properties": {
        "title": {
          "type": "string",
          "pattern": "[A-z]{3,16}"
        },
        "email": {
          "type": "string",
          "description": "We will send no spam to this addresss"
        },
        "categories": {
          "type": "array",
          "maxItems": 8,
          "items": {
            "type": "string"
          }
        },
        "locations": {
          "type": "array",
          "description": "Array of locations",
          "items": {
            "$ref": "#/definitions/refe081a664cb5227a334bc5e0fa367f178"
          }
        },
        "origin": {
          "$ref": "#/definitions/refe081a664cb5227a334bc5e0fa367f178"
        }
      },
      "required": [
        "title"
      ],
      "additionalProperties": false
    },
    "refe081a664cb5227a334bc5e0fa367f178": {
      "type": "object",
      "description": "Location of the person",
      "properties": {
        "lat": {
          "type": "integer"
        },
        "long": {
          "type": "integer"
        }
      },
      "additionalProperties": false
    }
  },
  "type": "object",
  "properties": {
    "tags": {
      "type": "array",
      "items": {
        "type": "string"
      },
      "minItems": 1,
      "maxItems": 6
    },
    "receiver": {
      "type": "array",
      "items": {
        "$ref": "#/definitions/ref6039818cbcfdcc20777fd8c74a5a392d"
      },
      "minItems": 1
    },
    "read": {
      "type": "boolean"
    },
    "author": {
      "$ref": "#/definitions/ref6039818cbcfdcc20777fd8c74a5a392d"
    },
    "sendDate": {
      "type": "string"
    },
    "readDate": {
      "type": "string"
    },
    "expires": {
      "type": "string"
    },
    "price": {
      "type": "number",
      "minimum": 1,
      "maximum": 100
    },
    "rating": {
      "type": "integer",
      "minimum": 1,
      "maximum": 5
    },
    "content": {
      "type": "string",
      "minLength": 3,
      "maxLength": 512,
      "description": "Contains the main content of the news entry"
    },
    "question": {
      "type": "string",
      "enum": [
        "foo",
        "bar"
      ]
    },
    "coffeeTime": {
      "type": "string"
    }
  },
  "required": [
    "receiver",
    "author",
    "price",
    "content"
  ],
  "additionalProperties": false
}
JSON;

		$this->assertJsonStringEqualsJsonString($expect, $result);
	}
}
