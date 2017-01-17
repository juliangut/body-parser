<?php

/*
 * body-parser (https://github.com/juliangut/body-parser).
 * PSR7 body parser middleware.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/body-parser
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\BodyParser\Decoder;

/**
 * Form URL encoded request decoder.
 */
class UrlEncoded implements Decoder
{
    use MimeTypeTrait;

    /**
     * UrlEncoded request decoder constructor.
     */
    public function __construct()
    {
        $this->addMimeType('application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    public function decode($rawBody)
    {
        parse_str($rawBody, $parsedBody);

        return $parsedBody;
    }
}
