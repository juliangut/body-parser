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

use Jgut\BodyParser\Decoder\Csv;

/**
 * CSV request decoder tests.
 */
class CsvTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Csv
     */
    protected $decoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->decoder = new Csv;
    }

    public function testMimeTypes()
    {
        self::assertContains('text/csv', $this->decoder->getMimeTypes());
    }

    public function testBodyParse()
    {
        $rawBody = <<<BODY
1,"Luke
Skywalker",Tatooine
2,Yoda,Dagobah
BODY;

        $parsedBody = $this->decoder->decode($rawBody);

        self::assertInternalType('array', $parsedBody);
        self::assertCount(2, $parsedBody);
        self::assertEquals('1', $parsedBody[0][0]);
        self::assertEquals('2', $parsedBody[1][0]);
        self::assertEquals("Luke\nSkywalker", $parsedBody[0][1]);
        self::assertEquals('Yoda', $parsedBody[1][1]);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^CSV request body parsing error/
     */
    public function testInvalidFormat()
    {
        $this->decoder->decode('1,"Luke Skywalker,Tattoine');
    }
}
