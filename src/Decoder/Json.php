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
 * JSON request decoder.
 */
class Json implements Decoder
{
    use MimeTypeTrait;

    /**
     * JSON request decoder constructor.
     */
    public function __construct()
    {
        $this->addMimeType('application/json');
        $this->addMimeType('text/json');
        $this->addMimeType('application/x-json');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function decode($rawBody)
    {
        $parsedBody = json_decode($rawBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(sprintf('JSON request body parsing error: %s', json_last_error_msg()));
        }

        return $parsedBody;
    }
}
