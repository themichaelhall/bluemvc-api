<?php

declare(strict_types=1);

namespace BlueMvc\Api\Tests\Helpers\TestControllers;

use BlueMvc\Api\ActionResults\ApiResult;
use BlueMvc\Api\ActionResults\ApiResultException;
use BlueMvc\Api\ApiController;
use BlueMvc\Core\ActionResults\NotModifiedResult;
use BlueMvc\Core\Http\StatusCode;

/**
 * A test controller for different result types.
 */
class ResultTypesController extends ApiController
{
    /**
     * GET action.
     *
     * @return NotModifiedResult The result.
     */
    public function getAction()
    {
        return new NotModifiedResult();
    }

    /**
     * POST action.
     *
     * @return ApiResult The result.
     */
    public function postAction()
    {
        return new ApiResult(['Message' => 'Resource was created'], new StatusCode(StatusCode::CREATED));
    }

    /**
     * PUT action.
     *
     * @return null
     */
    public function putAction()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/plain');
        $this->getResponse()->setContent('This should not be altered.');

        return null;
    }

    /**
     * DELETE action.
     *
     * @throws ApiResultException
     */
    public function deleteAction()
    {
        throw new ApiResultException(['Message' => 'Failed to remove resource'], new StatusCode(StatusCode::NETWORK_AUTHENTICATION_REQUIRED));
    }
}
