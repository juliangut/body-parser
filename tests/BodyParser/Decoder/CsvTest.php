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
        self::assertContains('application/csv', $this->decoder->getMimeTypes());
    }

    public function testBodyParse()
    {
        $rawBody = <<<CSV
1,"Luke
Skywalker",Tatooine
CSV;
        $parsedBody = $this->decoder->decode($rawBody);

        self::assertInternalType('array', $parsedBody);
        self::assertEquals('1', $parsedBody[0][0]);
        self::assertEquals("Luke\nSkywalker", $parsedBody[0][1]);
        self::assertEquals('Tatooine', $parsedBody[0][2]);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^CSV request body parsing error/
     */
    public function testInvalidFormat()
    {
        $this->decoder->decode('1,"Luke Skywalker,Tatooine');
    }
}
