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

    public function testBodyParse()
    {
        $parsedBody = $this->decoder->decode('{"id":12,"name":"Julian"}');

        self::assertInternalType('array', $parsedBody);
        self::assertArrayHasKey('id', $parsedBody);
        self::assertEquals(12, $parsedBody['id']);
        self::assertArrayHasKey('name', $parsedBody);
        self::assertEquals('Julian', $parsedBody['name']);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^JSON request body parsing error/
     */
    public function testInvalidFormat()
    {
        $this->decoder->decode('{"id":12,"name":Julian}');
    }
}
