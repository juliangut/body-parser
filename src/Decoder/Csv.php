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
 * CSV request decoder.
 */
class Csv implements Decoder
{
    use MimeTypeTrait;

    /**
     * Fields delimiter character.
     *
     * @var string
     */
    protected $delimiter;

    /**
     * Field enclosure character.
     *
     * @var string
     */
    protected $enclosure;

    /**
     * Escape character.
     *
     * @var string
     */
    protected $escape;

    /**
     * CSV request decoder constructor.
     *
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($delimiter = ',', $enclosure = '"', $escape = '\\')
    {
        $this->delimiter = (string) $delimiter;
        $this->enclosure = (string) $enclosure;
        $this->escape = (string) $escape;

        $this->addMimeType('text/csv');
    }

    /**
     * {@inheritdoc}
     */
    public function decode($rawBody)
    {
        $lines = array_map(
            function ($line) {
                return str_getcsv($line, $this->delimiter, $this->enclosure, $this->escape);
            },
            explode("\n", $rawBody)
        );

        return count($lines) === 1 ? $lines[0] : $lines;
    }
}
