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
     * @var bool
     */
    protected $assoc;

    /**
     * JSON request decoder constructor.
     *
     * @param bool $assoc
     */
    public function __construct($assoc = true)
    {
        $this->addMimeType('application/json');
        $this->addMimeType('text/json');
        $this->addMimeType('application/x-json');

        $this->assoc = (bool) $assoc;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function decode($rawBody)
    {
        $parsedBody = json_decode($rawBody, $this->assoc, 512, JSON_BIGINT_AS_STRING);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(sprintf('JSON request body parsing error: %s', json_last_error_msg()));
        }

        return $parsedBody;
    }
}
