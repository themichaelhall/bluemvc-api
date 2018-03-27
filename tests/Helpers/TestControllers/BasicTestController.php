<?php

namespace BlueMvc\Api\Tests\Helpers\TestControllers;

use BlueMvc\Api\ApiController;

/**
 * A basic API test controller.
 */
class BasicTestController extends ApiController
{
    /**
     * GET action.
     *
     * @return array The result.
     */
    public function getAction()
    {
        return ['actionMethod' => 'getAction'];
    }
}
