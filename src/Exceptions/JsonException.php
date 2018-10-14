<?php
/**
 * This file is a part of the bluemvc-api package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Api\Exceptions;

/**
 * Exception thrown on JSON failure.
 *
 * @since 2.1.0
 */
class JsonException extends \Exception
{
    /**
     * Constructs the exception.
     *
     * @since 2.1.0
     *
     * @param int $code The code from json_last_error().
     *
     * @see   \json_last_error()
     */
    public function __construct(int $code)
    {
        parent::__construct(self::JSON_ENCODE_ERRORS[$code] ?? '', $code);
    }

    /**
     * My errors as text.
     */
    private const JSON_ENCODE_ERRORS = [
        JSON_ERROR_DEPTH                 => 'The maximum stack depth has been exceeded (JSON_ERROR_DEPTH).',
        JSON_ERROR_STATE_MISMATCH        => 'Underflow or modes mismatch (JSON_ERROR_STATE_MISMATCH).',
        JSON_ERROR_CTRL_CHAR             => 'Control character error, possibly incorrectly encoded (JSON_ERROR_CTRL_CHAR).',
        JSON_ERROR_SYNTAX                => 'Syntax error (JSON_ERROR_SYNTAX).',
        JSON_ERROR_UTF8                  => 'Malformed UTF-8 characters, possibly incorrectly encoded (JSON_ERROR_UTF8).',
        JSON_ERROR_RECURSION             => 'Recursive references are included and cannot be encoded (JSON_ERROR_RECURSION).',
        JSON_ERROR_INF_OR_NAN            => 'The value includes either NAN or INF (JSON_ERROR_INF_OR_NAN).',
        JSON_ERROR_UNSUPPORTED_TYPE      => 'A value of an unsupported type was given, such as a resource (JSON_ERROR_UNSUPPORTED_TYPE).',
        JSON_ERROR_INVALID_PROPERTY_NAME => 'A key starting with \u0000 character was in the string (JSON_ERROR_INVALID_PROPERTY_NAME).',
        JSON_ERROR_UTF16                 => 'Single unpaired UTF-16 surrogate in unicode escape contained in the JSON string (JSON_ERROR_UTF16).',
    ];
}
