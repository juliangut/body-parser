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
 * Decoder interface.
 */
interface Decoder
{
    /**
     * Get MIME Types that can be decoded.
     *
     * @return array
     */
    public function getMimeTypes();

    /**
     * Decode request body.
     *
     * @param string $rawBody
     *
     * @return array|null
     */
    public function decode($rawBody);
}
