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

use League\Csv\Reader;

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
        $this->addMimeType('application/csv');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function decode($rawBody)
    {
        if (trim($rawBody) === '') {
            return;
        }

        $parsedBody = Reader::createFromString($rawBody)
            ->setDelimiter($this->delimiter)
            ->setEnclosure($this->enclosure)
            ->setEscape($this->escape)
            ->fetchAll();

        if (!$parsedBody) {
            throw new \RuntimeException('CSV request body parsing error: "verify CSV format"');
        }

        return $parsedBody;
    }
}
