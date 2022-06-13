<?php

/**
 * This file is a part of the bluemvc-api package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Api;

use BlueMvc\Api\Exceptions\JsonException;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Traits\ControllerTrait;

/**
 * Class representing an API controller.
 *
 * @since 1.1.0
 */
abstract class ApiController implements ControllerInterface
{
    use ControllerTrait;

    /**
     * Constructs the controller.
     *
     * @since 1.1.0
     */
    public function __construct()
    {
        $this->content = null;
    }

    /**
     * Returns the content or null if there is no content.
     *
     * @since 1.1.0
     *
     * @return array|null The content or null if there is no content.
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * Processes the request.
     *
     * @since 1.1.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     * @param string               $action      The action.
     * @param array                $parameters  The parameters.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, string $action, array $parameters = []): void
    {
        $this->init($application, $request, $response);

        if (!$this->readContent()) {
            return;
        }

        $method = $this->getRequest()->getMethod()->getName();

        if ($action !== '') {
            $parameters = array_merge([$action], $parameters);
        }

        $handleResultFunction = function ($result) {
            $this->handleResult($result);
        };

        if (!$this->tryInvokeActionMethod($method, $parameters, false, $handleResultFunction, $hasFoundActionMethod)) {
            $statusCode = $hasFoundActionMethod ?
                new StatusCode(StatusCode::NOT_FOUND) :
                new StatusCode(StatusCode::METHOD_NOT_ALLOWED);
            $response->setStatusCode($statusCode);
        }
    }

    /**
     * Returns the options used for JSON encoding.
     *
     * @since 2.1.0
     *
     * @return int The options.
     */
    protected function getJsonEncodeOptions(): int
    {
        return $this->getApplication()->isDebug() ? JSON_PRETTY_PRINT : 0;
    }

    /**
     * Handles the result.
     *
     * @param mixed $result The result.
     *
     * @throws JsonException If result could not be encoded as JSON.
     */
    private function handleResult(mixed $result): void
    {
        if ($result instanceof ActionResultInterface) {
            $result->updateResponse($this->getApplication(), $this->getRequest(), $this->getResponse());

            return;
        }

        if ($result === null) {
            return;
        }

        $jsonResult = json_encode($result, $this->getJsonEncodeOptions());
        if ($jsonResult === false) {
            throw new JsonException(json_last_error());
        }

        $this->getResponse()->setContent($jsonResult);
        $this->getResponse()->setHeader('Content-Type', 'application/json');
    }

    /**
     * Reads the content.
     *
     * @return bool True if content was successfully read, false otherwise.
     */
    private function readContent(): bool
    {
        $rawContent = $this->getRequest()->getRawContent();
        if ($rawContent === '') {
            $this->content = null;

            return true;
        }

        $this->content = json_decode($rawContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->getResponse()->setStatusCode(new StatusCode(StatusCode::BAD_REQUEST));

            return false;
        }

        return true;
    }

    /**
     * @var array|null My content or null if no content.
     */
    private ?array $content;
}
