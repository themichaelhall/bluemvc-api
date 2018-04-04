<?php

namespace BlueMvc\Api\Tests\Helpers\TestControllers;

use BlueMvc\Api\ActionResults\ApiResult;
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
        return new ApiResult(new StatusCode(StatusCode::CREATED), ['Message' => 'Resource was created']);
    }
}
