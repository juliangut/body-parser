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
 * XML request decoder.
 */
class Xml implements Decoder
{
    use MimeTypeTrait;

    /**
     * XML request decoder constructor.
     */
    public function __construct()
    {
        $this->addMimeType('application/xml');
        $this->addMimeType('text/xml');
        $this->addMimeType('application/x-xml');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function decode($rawBody)
    {
        $disableEntityLoader = libxml_disable_entity_loader(true);
        $useInternalErrors = libxml_use_internal_errors(true);

        $parsedBody = simplexml_load_string($rawBody);

        libxml_use_internal_errors($useInternalErrors);
        libxml_disable_entity_loader($disableEntityLoader);

        if (!$parsedBody instanceof \SimpleXMLElement) {
            // @codeCoverageIgnoreStart
            $errors = array_map(
                function (\LibXMLError $error) {
                    return '"' . $error->message . '"';
                },
                libxml_get_errors()
            );
            // @codeCoverageIgnoreEnd

            libxml_clear_errors();

            throw new \RuntimeException(sprintf('XML request body parsing error: "%s"', implode(',', $errors)));
        }

        return (array) $parsedBody;
    }
}
