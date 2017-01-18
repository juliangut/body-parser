<?php

/*
 * body-parser (https://github.com/juliangut/body-parser).
 * PSR7 body parser middleware.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/body-parser
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\BodyParser\Tests;

use Jgut\BodyParser\Decoder\Csv;
use Jgut\BodyParser\Decoder\Json;
use Jgut\BodyParser\Decoder\Xml;
use Jgut\BodyParser\Parser;
use Negotiation\Negotiator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;

/**
 * Body content parser tests.
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Negotiator
     */
    protected $negotiator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->negotiator = new Negotiator;
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Methods must be a string or an array
     */
    public function testInvalidMethod()
    {
        $decoder = new Parser($this->negotiator);

        /* @var Json $jsonDecoder */
        $jsonDecoder = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        $decoder->addDecoder($jsonDecoder, 'GET');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage GET HTTP requests can not carry body
     */
    public function testNotSupportedMethod()
    {
        $decoder = new Parser($this->negotiator);

        /* @var Json $jsonDecoder */
        $jsonDecoder = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        $decoder->addDecoder($jsonDecoder, ['GET']);
    }

    public function testParsers()
    {
        $decoder = new Parser($this->negotiator);

        /* @var Json $jsonDecoder */
        $jsonDecoder = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* @var Xml $xmlDecoder */
        $xmlDecoder = $this->getMockBuilder(Xml::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* @var Csv $csvDecoder */
        $csvDecoder = $this->getMockBuilder(Csv::class)
            ->disableOriginalConstructor()
            ->getMock();

        $decoder->addDecoder($jsonDecoder);
        $decoder->addPostDecoder($xmlDecoder);
        $decoder->addPutDecoder($csvDecoder);
        $decoder->addPatchDecoder($csvDecoder);

        self::assertEquals([$jsonDecoder, $xmlDecoder, $csvDecoder, $csvDecoder], $decoder->getDecoders());

        self::assertContains($jsonDecoder, $decoder->getDecoders('POST'));
        self::assertContains($xmlDecoder, $decoder->getDecoders('POST'));
        self::assertContains($csvDecoder, $decoder->getDecoders('PUT'));
        self::assertContains($csvDecoder, $decoder->getDecoders('PATCH'));

        self::assertNotContains($xmlDecoder, $decoder->getDecoders('PUT'));

        self::assertEmpty($decoder->getDecoders('GET'));

        self::assertEquals([$jsonDecoder], $decoder->getDecoders('UNKNOWN'));
    }

    public function testNotParseMethod()
    {
        $request = new ServerRequest([], [], null, 'GET');

        $decoder = new Parser($this->negotiator);

        $callable = function (ServerRequestInterface $request, ResponseInterface $response) {
            $this->assertNull($request->getParsedBody());

            return $response;
        };

        $decoder($request, new Response, $callable);
    }

    public function testNoContentTypeParser()
    {
        $request = new ServerRequest([], [], null, 'POST');
        $request = $request->withHeader('Content-Type', 'application/json');

        $decoder = new Parser($this->negotiator);

        $decoder->addDecoder(new Xml);

        $callable = function (ServerRequestInterface $request, ResponseInterface $response) {
            $this->assertNull($request->getParsedBody());

            return $response;
        };

        $decoder($request, new Response, $callable);
    }

    public function testBodyDecode()
    {
        $body = new Stream('php://temp', 'wb+');
        $body->write('{"id":10,"name":"Julian"}');

        $request = new ServerRequest([], [], null, 'POST', $body);
        $request = $request->withHeader('Content-Type', 'application/json');

        $decoder = new Parser($this->negotiator);

        $decoder->addDecoder(new Json);

        $callable = function (ServerRequestInterface $request, ResponseInterface $response) {
            $this->assertArrayHasKey('name', $request->getParsedBody());

            return $response;
        };

        $decoder($request, new Response, $callable);
    }
}
