<?php

declare(strict_types=1);

namespace BlueMvc\Api\Tests\Helpers\TestControllers;

use BlueMvc\Api\ApiController;

/**
 * A test controller using custom JSON encode options.
 */
class CustomJsonEncodeOptionsTestController extends ApiController
{
    /**
     * GET action.
     *
     * @return string[] The result.
     */
    public function getAction(): array
    {
        return [
            'Bar',
            '42',
        ];
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * @return int The options used for JSON encoding.
     */
    protected function getJsonEncodeOptions(): int
    {
        return JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK;
    }
}
