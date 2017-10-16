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
        $parsedBody = $this->decoder
            ->decode('id=1&name=Luke%20Skywalker&planet[name]=Tatooine&planet[location]=Outer%20Rim');

        self::assertInternalType('array', $parsedBody);
        self::assertArrayHasKey('id', $parsedBody);
        self::assertEquals(1, $parsedBody['id']);
        self::assertArrayHasKey('name', $parsedBody);
        self::assertEquals('Luke Skywalker', $parsedBody['name']);
        self::assertArrayHasKey('planet', $parsedBody);
        self::assertEquals('Tatooine', $parsedBody['planet']['name']);
        self::assertEquals('Outer Rim', $parsedBody['planet']['location']);
    }
}
