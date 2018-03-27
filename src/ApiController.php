<?php
/**
 * This file is a part of the bluemvc-api package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Api;

use BlueMvc\Core\Base\AbstractController;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing an API controller.
 *
 * @since 1.1.0
 */
class ApiController extends AbstractController
{
    /**
     * Constructs the controller.
     *
     * @since 1.1.0
     */
    public function __construct()
    {
        parent::__construct();
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

        $method = $this->getRequest()->getMethod()->getName();

        $this->tryInvokeActionMethod($method, $parameters, false, $result);

        $response->setContent(json_encode($result));
        $response->setHeader('Content-Type', 'application/json');
    }
}
