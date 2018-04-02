<?php

namespace BlueMvc\Api\Tests\Helpers\TestControllers;

use BlueMvc\Api\ApiController;
use BlueMvc\Core\ActionResults\NotModifiedResult;

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
}
