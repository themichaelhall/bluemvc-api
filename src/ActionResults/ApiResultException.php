<?php

/**
 * This file is a part of the bluemvc-api package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Api\ActionResults;

use BlueMvc\Core\ActionResults\ActionResultException;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;

/**
 * Class representing an API result exception.
 *
 * @since 2.1.0
 */
class ApiResultException extends ActionResultException
{
    /**
     * Constructs the API result exception.
     *
     * @since 2.1.0
     *
     * @param mixed|null          $content    The content.
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function __construct($content, StatusCodeInterface $statusCode)
    {
        parent::__construct(new ApiResult($content, $statusCode));
    }
}
