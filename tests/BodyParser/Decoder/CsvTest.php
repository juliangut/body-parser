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
        $parsedBody = $this->decoder->decode('"12","Julian"');

        self::assertInternalType('array', $parsedBody);
        self::assertEquals('12', $parsedBody[0]);
        self::assertEquals('Julian', $parsedBody[1]);
    }
}
