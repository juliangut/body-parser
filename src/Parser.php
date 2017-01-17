<?php

/*
 * body-parser (https://github.com/juliangut/body-parser).
 * PSR7 body parser middleware.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/body-parser
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\BodyParser;

use Jgut\BodyParser\Decoder\Decoder;
use Negotiation\Negotiator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Body content parser.
 */
class Parser
{
    const METHOD_ALL = 'ALL';

    /**
     * Content negotiator.
     *
     * @var Negotiator
     */
    protected $negotiator;

    /**
     * Registered decoders.
     *
     * @var array
     */
    protected $decoders = [];

    /**
     * Parser constructor.
     *
     * @param Negotiator $negotiator
     */
    public function __construct(Negotiator $negotiator)
    {
        $this->negotiator = $negotiator;
    }

    /**
     * Add body decoder.
     *
     * @param Decoder      $decoder
     * @param string|array $methods
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function addDecoder(Decoder $decoder, $methods = self::METHOD_ALL)
    {
        if ($methods === self::METHOD_ALL) {
            $methods = [$methods];
        } elseif (!is_array($methods)) {
            throw new \InvalidArgumentException('Methods must be a string or an array');
        }

        array_walk(
            $methods,
            function ($method) use ($decoder) {
                $method = strtoupper(trim($method));

                if (!$this->methodCarriesBody($method)) {
                    throw new \RuntimeException(sprintf('%s HTTP requests can not carry body', $method));
                }

                if (!array_key_exists($method, $this->decoders)) {
                    $this->decoders[$method] = [];
                }

                $this->decoders[$method][] = $decoder;
            }
        );
    }

    /**
     * Add decoder for POST requests.
     *
     * @param Decoder $decoder
     */
    public function addPostDecoder(Decoder $decoder)
    {
        $this->addDecoder($decoder, ['POST']);
    }

    /**
     * Add decoder for PUT requests.
     *
     * @param Decoder $decoder
     */
    public function addPutDecoder(Decoder $decoder)
    {
        $this->addDecoder($decoder, ['PUT']);
    }

    /**
     * Add decoder for PATCH requests.
     *
     * @param Decoder $decoder
     */
    public function addPatchDecoder(Decoder $decoder)
    {
        $this->addDecoder($decoder, ['PATCH']);
    }

    /**
     * Get decoders for a method.
     *
     * @param string $method
     *
     * @return Decoder[]
     */
    public function getDecoders($method = self::METHOD_ALL)
    {
        $decoders = [];

        if (!$this->methodCarriesBody($method)) {
            return $decoders;
        }

        if (array_key_exists(static::METHOD_ALL, $this->decoders)) {
            if ($method === static::METHOD_ALL) {
                return $this->flatten($this->decoders);
            }

            $decoders = $this->decoders[static::METHOD_ALL];
        }

        if (!array_key_exists($method, $this->decoders)) {
            return $decoders;
        }

        return array_unique(array_merge($decoders, $this->decoders[$method]), SORT_REGULAR);
    }

    /**
     * Flatten array.
     *
     * @param array $array
     *
     * @return array
     */
    private function flatten(array $array)
    {
        return array_reduce(
            $array,
            function ($carry, $item) {
                return is_array($item) ? array_merge($carry, $this->flatten($item)) : array_merge($carry, [$item]);
            },
            []
        );
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (!$this->methodCarriesBody($request->getMethod())) {
            return $next($request, $response);
        }

        $contentType = $request->getHeaderLine('Content-Type');

        foreach ($this->getDecoders($request->getMethod()) as $decoder) {
            if ($this->negotiate($contentType, $decoder->getMimeTypes()) === '') {
                continue;
            }

            return $next($request->withParsedBody($decoder->decode((string) $request->getBody())), $response);
        }

        return $next($request, $response);
    }

    /**
     * Negotiate content type.
     *
     * @param string $contentType
     * @param array  $priorities
     *
     * @return string
     */
    protected function negotiate($contentType, array $priorities)
    {
        if (trim($contentType) !== '' && count($priorities)) {
            try {
                /* @var \Negotiation\BaseAccept $best */
                $best = $this->negotiator->getBest($contentType, $priorities);

                if ($best) {
                    return (string) $best->getValue();
                }
                // @codeCoverageIgnoreStart
            } catch (\Exception $exception) {
                // No action needed
            }
            // @codeCoverageIgnoreEnd
        }

        return '';
    }

    /**
     * Does HTTP method carry body content.
     *
     * @param string $method
     *
     * @return bool
     */
    private function methodCarriesBody($method)
    {
        return !in_array($method, ['GET', 'HEAD', 'OPTIONS', 'CONNECT', 'TRACE']);
    }
}
