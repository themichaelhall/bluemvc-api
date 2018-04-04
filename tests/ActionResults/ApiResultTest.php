<?php

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
        $apiResult = new ApiResult(new StatusCode(StatusCode::CONFLICT));
        $application = new FakeApplication();
        $request = new FakeRequest();
        $response = new FakeResponse();

        $apiResult->updateResponse($application, $request, $response);

        self::assertSame(StatusCode::CONFLICT, $response->getStatusCode()->getCode());
        self::assertSame([], iterator_to_array($response->getHeaders()));
        self::assertSame('', $response->getContent());
    }

    /**
     * Test ApiResult with content.
     */
    public function testWithContent()
    {
        $apiResult = new ApiResult(new StatusCode(StatusCode::CONFLICT), ['Foo' => true]);
        $application = new FakeApplication();
        $request = new FakeRequest();
        $response = new FakeResponse();

        $apiResult->updateResponse($application, $request, $response);

        self::assertSame(StatusCode::CONFLICT, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame('{"Foo":true}', $response->getContent());
    }
}
