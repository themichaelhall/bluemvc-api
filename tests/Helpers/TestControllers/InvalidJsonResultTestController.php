<?php

declare(strict_types=1);

namespace BlueMvc\Api\Tests\Helpers\TestControllers;

use BlueMvc\Api\ApiController;

/**
 * A test controller returning invalid JSON result.
 */
class InvalidJsonResultTestController extends ApiController
{
    /**
     * GET action.
     *
     * @return array
     */
    public function getAction(): array
    {
        return [
            'Foo' => NAN,
        ];
    }

    /**
     * POST action.
     *
     * @return array
     */
    public function postAction(): array
    {
        return [
            'Bar' => 'Baz' . chr(220),
        ];
    }
}
