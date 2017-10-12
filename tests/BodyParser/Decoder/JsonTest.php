<?php

/*
 * body-parser (https://github.com/juliangut/body-parser).
 * PSR7 body parser middleware.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/body-parser
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\BodyParser\Tests\Decoder;

use Jgut\BodyParser\Decoder\Json;

/**
 * JSON request decoder tests.
 */
class JsonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Json
     */
    protected $decoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->decoder = new Json;
    }

    public function testMimeTypes()
    {
        self::assertContains('application/json', $this->decoder->getMimeTypes());
        self::assertContains('text/json', $this->decoder->getMimeTypes());
        self::assertContains('application/x-json', $this->decoder->getMimeTypes());
    }

    public function testBodyParseToArray()
    {
        $rawBody = <<<JSON
{
    "id": 1,
    "name": "Luke Skywalker",
    "planet": "Tatooine"
}
JSON;
        $parsedBody = $this->decoder->decode($rawBody);

        self::assertInternalType('array', $parsedBody);
        self::assertArrayHasKey('id', $parsedBody);
        self::assertEquals(1, $parsedBody['id']);
        self::assertArrayHasKey('name', $parsedBody);
        self::assertEquals('Luke Skywalker', $parsedBody['name']);
        self::assertArrayHasKey('planet', $parsedBody);
        self::assertEquals('Tatooine', $parsedBody['planet']);
    }

    public function testBodyParseToObject()
    {
        $rawBody = <<<JSON
{
    "id": 1,
    "name": "Luke Skywalker",
    "planet": "Tatooine"
}
JSON;
        $parsedBody = (new Json(false))->decode($rawBody);

        self::assertInternalType('object', $parsedBody);
        self::assertObjectHasAttribute('id', $parsedBody);
        self::assertEquals(1, $parsedBody->id);
        self::assertObjectHasAttribute('name', $parsedBody);
        self::assertEquals('Luke Skywalker', $parsedBody->name);
        self::assertObjectHasAttribute('planet', $parsedBody);
        self::assertEquals('Tatooine', $parsedBody->planet);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^JSON request body parsing error/
     */
    public function testInvalidFormat()
    {
        $this->decoder->decode('{"id":12,"name":Luke Skywalker}');
    }
}
