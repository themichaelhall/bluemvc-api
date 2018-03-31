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
        return [
            'actionMethod' => 'getAction',
            'content'      => $this->getContent(),
        ];
    }

    /**
     * POST action.
     *
     * @return array The result.
     */
    public function postAction()
    {
        return [
            'actionMethod' => 'postAction',
            'content'      => $this->getContent(),
        ];
    }
}
