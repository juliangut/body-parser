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

use Jgut\BodyParser\Decoder\Xml;

/**
 * XML request decoder tests.
 */
class XmlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Xml
     */
    protected $decoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->decoder = new Xml;
    }

    public function testMimeTypes()
    {
        self::assertContains('application/xml', $this->decoder->getMimeTypes());
        self::assertContains('text/xml', $this->decoder->getMimeTypes());
        self::assertContains('application/x-xml', $this->decoder->getMimeTypes());
    }

    public function testEmptyBody()
    {
        self::assertNull($this->decoder->decode(''));
    }

    public function testBodyParse()
    {
        $rawBody = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <id>1</id>
  <name>Luke Skywalker</name>
  <planet>
    <name>Tatooine</name>
    <location>Outer Rim</location>
  </planet>
</root>
XML;
        $parsedBody = $this->decoder->decode($rawBody);

        self::assertInternalType('array', $parsedBody);
        self::assertArrayHasKey('id', $parsedBody);
        self::assertEquals(1, $parsedBody['id']);
        self::assertArrayHasKey('name', $parsedBody);
        self::assertEquals('Luke Skywalker', $parsedBody['name']);
        self::assertArrayHasKey('planet', $parsedBody);
        self::assertEquals(['name' => 'Tatooine', 'location' => 'Outer Rim'], $parsedBody['planet']);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^XML request body parsing error/
     */
    public function testInvalidFormat()
    {
        $this->decoder->decode('<?xml version="1.0" encoding="utf-8"?><root><id>1</id><name>Luke</planet></root>');
    }
}
