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

use Jgut\BodyParser\Decoder\UrlEncoded;

/**
 * Form URL decoder request parser tests.
 */
class UrlEncodedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UrlEncoded
     */
    protected $decoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->decoder = new UrlEncoded;
    }

    public function testMimeTypes()
    {
        self::assertContains('application/x-www-form-urlencoded', $this->decoder->getMimeTypes());
    }

    public function testBodyParse()
    {
        $parsedBody = $this->decoder->decode('id=12&name=Julian&role=dev');

        self::assertInternalType('array', $parsedBody);
        self::assertArrayHasKey('id', $parsedBody);
        self::assertEquals(12, $parsedBody['id']);
        self::assertArrayHasKey('name', $parsedBody);
        self::assertEquals('Julian', $parsedBody['name']);
        self::assertArrayHasKey('role', $parsedBody);
        self::assertEquals('dev', $parsedBody['role']);
    }
}
