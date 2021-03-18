<?php

declare(strict_types=1);

namespace BlueMvc\Api\Tests;

use BlueMvc\Api\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Api\Tests\Helpers\TestControllers\InvalidJsonResultTestController;
use BlueMvc\Api\Tests\Helpers\TestControllers\ResultTypesController;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Route;
use BlueMvc\Fakes\FakeApplication;
use BlueMvc\Fakes\FakeRequest;
use BlueMvc\Fakes\FakeResponse;
use PHPUnit\Framework\TestCase;

/**
 * Test request to application with API controllers.
 */
class RequestTest extends TestCase
{
    /**
     * Test basic requests.
     *
     * @dataProvider basicRequestsDataProvider
     *
     * @param string      $path               The path.
     * @param string      $method             The method.
     * @param null|string $rawContent         The raw content or null if request has no raw content.
     * @param int         $expectedStatusCode The expected status code.
     * @param array       $expectedHeaders    The expected headers.
     * @param string      $expectedContent    The expected content.
     */
    public function testBasicRequests(string $path, string $method, ?string $rawContent, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $request = new FakeRequest($path, $method);
        if ($rawContent !== null) {
            $request->setRawContent($rawContent);
        }
        $response = new FakeResponse();

        $this->application->run($request, $response);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testBasicRequests.
     *
     * @return array[]
     */
    public function basicRequestsDataProvider(): array
    {
        return [
            ['/', 'get', null, StatusCode::OK, ['Content-Type' => 'application/json'], '{"actionMethod":"getAction","content":null}'],
            ['/', 'get', '["Foo"]', StatusCode::OK, ['Content-Type' => 'application/json'], '{"actionMethod":"getAction","content":["Foo"]}'],
            ['/', 'post', null, StatusCode::OK, ['Content-Type' => 'application/json'], '{"actionMethod":"postAction","content":null}'],
            ['/', 'post', '["Foo"]', StatusCode::OK, ['Content-Type' => 'application/json'], '{"actionMethod":"postAction","content":["Foo"]}'],
            ['/', 'post', '{"Foo"', StatusCode::BAD_REQUEST, [], ''],
            ['/', 'delete', null, StatusCode::METHOD_NOT_ALLOWED, [], ''],
            ['/Bar', 'patch', '{"Foo":"Baz"}', StatusCode::OK, ['Content-Type' => 'application/json'], '{"actionMethod":"patchAction","content":{"Foo":"Baz"},"parameter":"Bar"}'],
            ['/', 'patch', '{"Foo":"Baz"}', StatusCode::NOT_FOUND, [], ''],
        ];
    }

    /**
     * Test result types.
     *
     * @dataProvider resultTypesDataProvider
     *
     * @param string      $path               The path.
     * @param string      $method             The method.
     * @param null|string $rawContent         The raw content or null if request has no raw content.
     * @param int         $expectedStatusCode The expected status code.
     * @param array       $expectedHeaders    The expected headers.
     * @param string      $expectedContent    The expected content.
     */
    public function testResultTypes(string $path, string $method, ?string $rawContent, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $request = new FakeRequest($path, $method);
        if ($rawContent !== null) {
            $request->setRawContent($rawContent);
        }
        $response = new FakeResponse();

        $this->application->run($request, $response);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testResultTypes.
     *
     * @return array[]
     */
    public function resultTypesDataProvider(): array
    {
        return [
            ['/resultTypes/', 'GET', null, StatusCode::NOT_MODIFIED, [], ''],
            ['/resultTypes/', 'POST', null, StatusCode::CREATED, ['Content-Type' => 'application/json'], '{"Message":"Resource was created"}'],
            ['/resultTypes/', 'DELETE', null, StatusCode::NETWORK_AUTHENTICATION_REQUIRED, ['Content-Type' => 'application/json'], '{"Message":"Failed to remove resource"}'],
            ['/resultTypes/', 'PUT', null, StatusCode::OK, ['Content-Type' => 'text/plain'], 'This should not be altered.'],
        ];
    }

    /**
     * Test invalid JSON result.
     *
     * @dataProvider invalidJsonResultDataProvider
     *
     * @param string $method          The method.
     * @param string $expectedContent The expected content.
     */
    public function testInvalidJsonResult(string $method, string $expectedContent)
    {
        $this->application->setDebug(true);
        $request = new FakeRequest('/invalidJsonResult/', $method);
        $response = new FakeResponse();

        $this->application->run($request, $response);

        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
        self::assertSame([], iterator_to_array($response->getHeaders()));
        self::assertStringContainsString($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testInvalidJsonResult.
     *
     * @return array[]
     */
    public function invalidJsonResultDataProvider(): array
    {
        return [
            ['get', 'The value includes either NAN or INF (JSON_ERROR_INF_OR_NAN).'],
            ['post', 'Malformed UTF-8 characters, possibly incorrectly encoded (JSON_ERROR_UTF8).'],
        ];
    }

    /**
     * Set up.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->application = new FakeApplication(__DIR__);
        $this->application->addRoute(new Route('', BasicTestController::class));
        $this->application->addRoute(new Route('resultTypes', ResultTypesController::class));
        $this->application->addRoute(new Route('invalidJsonResult', InvalidJsonResultTestController::class));
    }

    /**
     * Tear down.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->application = null;
    }

    /**
     * @var FakeApplication My application.
     */
    private $application;
}
