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

use Jgut\BodyParser\Decoder\MimeTypeTrait;

/**
 * MimeType trait tests.
 */
class MimeTypeTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testMimeTypes()
    {
        /* @var MimeTypeTrait $trait */
        $trait = $this->getMockBuilder(MimeTypeTrait::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMockForTrait();

        $trait->addMimeType('application/json');
        $trait->addMimeType('application/xml');
        $trait->addMimeType('application/x-www-form-urlencoded');

        self::assertContains('application/json', $trait->getMimeTypes());
        self::assertContains('application/xml', $trait->getMimeTypes());
        self::assertContains('application/x-www-form-urlencoded', $trait->getMimeTypes());

        $trait->removeMimeType('application/xml');

        self::assertNotContains('application/xml', $trait->getMimeTypes());
    }
}
