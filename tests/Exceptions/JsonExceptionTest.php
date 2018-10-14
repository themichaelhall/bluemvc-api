<?php

declare(strict_types=1);

namespace BlueMvc\Api\Tests\Exceptions;

use BlueMvc\Api\Exceptions\JsonException;
use PHPUnit\Framework\TestCase;

/**
 * Test JsonException class.
 */
class JsonExceptionTest extends TestCase
{
    /**
     * Test with valid error code.
     */
    public function testValidErrorCode()
    {
        $jsonException = new JsonException(JSON_ERROR_UTF8);

        self::assertSame(JSON_ERROR_UTF8, $jsonException->getCode());
        self::assertSame('Malformed UTF-8 characters, possibly incorrectly encoded (JSON_ERROR_UTF8).', $jsonException->getMessage());
    }

    /**
     * Test with invalid error code.
     */
    public function testInvalidErrorCode()
    {
        $jsonException = new JsonException(-1);

        self::assertSame(-1, $jsonException->getCode());
        self::assertSame('', $jsonException->getMessage());
    }
}
