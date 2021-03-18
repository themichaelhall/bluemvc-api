<?php

declare(strict_types=1);

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
    public function getAction(): array
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
    public function postAction(): array
    {
        return [
            'actionMethod' => 'postAction',
            'content'      => $this->getContent(),
        ];
    }

    /**
     * PATCH action.
     *
     * @param string $parameter A required parameter.
     *
     * @return array The result.
     */
    public function patchAction(string $parameter): array
    {
        return [
            'actionMethod' => 'patchAction',
            'content'      => $this->getContent(),
            'parameter'    => $parameter,
        ];
    }
}
