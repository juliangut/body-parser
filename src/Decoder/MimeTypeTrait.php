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
 * MimeType helper trait.
 */
trait MimeTypeTrait
{
    protected $mimeTypes = [];

    /**
     * Add MIME Type.
     *
     * @param string $mimeType
     */
    public function addMimeType($mimeType)
    {
        $mimeType = (string) $mimeType;

        if (!in_array($mimeType, $this->mimeTypes)) {
            $this->mimeTypes[] = $mimeType;
        }
    }

    /**
     * Remove MIME Type.
     *
     * @param string $mimeType
     */
    public function removeMimeType($mimeType)
    {
        $pos = array_search($mimeType, $this->mimeTypes, true);

        if ($pos !== false) {
            array_splice($this->mimeTypes, $pos, 1);
        }
    }

    /**
     * Get MIME Types that can be decoded.
     *
     * @return array
     */
    public function getMimeTypes()
    {
        return $this->mimeTypes;
    }
}
