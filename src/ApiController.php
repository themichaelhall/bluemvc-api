<?php
/**
 * This file is a part of the bluemvc-api package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Api;

use BlueMvc\Core\Base\AbstractController;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing an API controller.
 *
 * @since 1.1.0
 */
abstract class ApiController extends AbstractController
{
    /**
     * Constructs the controller.
     *
     * @since 1.1.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->content = null;
    }

    /**
     * Returns the content or null if there is no content.
     *
     * @since 1.1.0
     *
     * @return array|null The content or null if there is no content.
     */
    public function getContent()
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
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, $action, array $parameters = [])
    {
        parent::processRequest($application, $request, $response, $action, $parameters);

        if (!$this->readContent()) {
            return;
        }

        $method = $this->getRequest()->getMethod()->getName();

        if ($action !== '') {
            $parameters = array_merge([$action], $parameters);
        }

        if (!$this->tryInvokeActionMethod($method, $parameters, false, $result, $hasFoundActionMethod)) {
            $statusCode = $hasFoundActionMethod ?
                new StatusCode(StatusCode::NOT_FOUND) :
                new StatusCode(StatusCode::METHOD_NOT_ALLOWED);
            $response->setStatusCode($statusCode);

            return;
        }

        $this->handleResult($result);
    }

    /**
     * Handles the result.
     *
     * @param mixed $result The result.
     */
    private function handleResult($result)
    {
        if ($result instanceof ActionResultInterface) {
            $result->updateResponse($this->getApplication(), $this->getRequest(), $this->getResponse());

            return;
        }

        if ($result === null) {
            return;
        }

        $this->getResponse()->setContent(json_encode($result));
        $this->getResponse()->setHeader('Content-Type', 'application/json');
    }

    /**
     * Reads the content.
     *
     * @return bool True if content was successfully read, false otherwise.
     */
    private function readContent()
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
    private $content;
}
