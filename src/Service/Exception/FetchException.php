<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\ShariffBundle\Service\Exception;

use Psr\Http\Client\ClientExceptionInterface;

class FetchException extends \Exception implements ClientExceptionInterface
{
    /**
     * @var string|null
     */
    private $responseBody;

    public function __construct(string $message, string $responseBody = null)
    {
        parent::__construct($message);

        $this->responseBody = $responseBody;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }
}
