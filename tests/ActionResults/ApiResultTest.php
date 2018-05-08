<?php

declare(strict_types=1);

namespace BlueMvc\Api\Tests\ActionResults;

use BlueMvc\Api\ActionResults\ApiResult;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Fakes\FakeApplication;
use BlueMvc\Fakes\FakeRequest;
use BlueMvc\Fakes\FakeResponse;
use PHPUnit\Framework\TestCase;

/**
 * Test ApiResult class.
 */
class ApiResultTest extends TestCase
{
    /**
     * Test ApiResult with no content.
     */
    public function testWithNoContent()
    {
        $apiResult = new ApiResult('', new StatusCode(StatusCode::OK));
        $application = new FakeApplication();
        $request = new FakeRequest();
        $response = new FakeResponse();

        $apiResult->updateResponse($application, $request, $response);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame('""', $response->getContent());
    }

    /**
     * Test ApiResult with content.
     */
    public function testWithContent()
    {
        $apiResult = new ApiResult(['Foo' => true], new StatusCode(StatusCode::CONFLICT));
        $application = new FakeApplication();
        $request = new FakeRequest();
        $response = new FakeResponse();

        $apiResult->updateResponse($application, $request, $response);

        self::assertSame(StatusCode::CONFLICT, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame('{"Foo":true}', $response->getContent());
    }

    /**
     * Test ApiResult with null result.
     */
    public function testWithNullResult()
    {
        $apiResult = new ApiResult(null, new StatusCode(StatusCode::NOT_FOUND));
        $application = new FakeApplication();
        $request = new FakeRequest();
        $response = new FakeResponse();

        $apiResult->updateResponse($application, $request, $response);

        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame('null', $response->getContent());
    }
}
