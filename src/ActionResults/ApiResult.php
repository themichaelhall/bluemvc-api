<?php
/**
 * This file is a part of the bluemvc-api package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Api\ActionResults;

use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing an API result.
 *
 * @since 1.1.0
 */
class ApiResult implements ActionResultInterface
{
    /**
     * ApiResult constructor.
     *
     * @since 1.1.0
     *
     * @param mixed|null          $content    The content.
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function __construct($content, StatusCodeInterface $statusCode)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    /**
     * Updates the response.
     *
     * @since 1.1.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     */
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): void
    {
        $response->setStatusCode($this->statusCode);
        $response->setHeader('Content-Type', 'application/json');
        $response->setContent(json_encode($this->content));
    }

    /**
     * @var StatusCodeInterface My status code.
     */
    private $statusCode;

    /**
     * @var mixed|null My content.
     */
    private $content;
}
