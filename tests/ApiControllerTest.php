<?php

declare(strict_types=1);

namespace BlueMvc\Api\Tests;

use BlueMvc\Api\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Api\Tests\Helpers\TestControllers\CustomJsonEncodeOptionsTestController;
use BlueMvc\Api\Tests\Helpers\TestControllers\ResultTypesController;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Fakes\FakeApplication;
use BlueMvc\Fakes\FakeRequest;
use BlueMvc\Fakes\FakeResponse;
use PHPUnit\Framework\TestCase;

/**
 * Test ApiController class.
 */
class ApiControllerTest extends TestCase
{
    /**
     * Test basic test controller.
     *
     * @dataProvider basicTestControllerDataProvider
     *
     * @param string      $path                The path.
     * @param string      $method              The method.
     * @param null|string $rawContent          The raw content or null if no raw content.
     * @param int         $expectedStatusCode  The expected status code.
     * @param null|string $expectedContentType The expected content type header or null if no content type header is expected.
     * @param string      $expectedContent     The expected content.
     */
    public function testBasicTestController(string $path, string $method, ?string $rawContent, int $expectedStatusCode, ?string $expectedContentType, string $expectedContent)
    {
        $request = new FakeRequest('/', $method);
        if ($rawContent !== null) {
            $request->setRawContent($rawContent);
        }
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, $path, []);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContentType, $response->getHeader('Content-Type'));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testBasicTestController.
     *
     * @return array
     */
    public function basicTestControllerDataProvider()
    {
        return [
            ['', 'get', null, StatusCode::OK, 'application/json', '{"actionMethod":"getAction","content":null}'],
            ['', 'get', '{"Foo":"Bar"}', StatusCode::OK, 'application/json', '{"actionMethod":"getAction","content":{"Foo":"Bar"}}'],
            ['', 'post', null, StatusCode::OK, 'application/json', '{"actionMethod":"postAction","content":null}'],
            ['', 'post', '{"Foo":"Bar"}', StatusCode::OK, 'application/json', '{"actionMethod":"postAction","content":{"Foo":"Bar"}}'],
            ['', 'post', 'FooBar', StatusCode::BAD_REQUEST, null, ''],
            ['', 'put', null, StatusCode::METHOD_NOT_ALLOWED, null, ''],
            ['foo', 'patch', null, StatusCode::OK, 'application/json', '{"actionMethod":"patchAction","content":null,"parameter":"foo"}'],
            ['foo', 'patch', '{"Foo":"Bar"}', StatusCode::OK, 'application/json', '{"actionMethod":"patchAction","content":{"Foo":"Bar"},"parameter":"foo"}'],
            ['', 'patch', null, StatusCode::NOT_FOUND, null, ''],
        ];
    }

    /**
     * Test JSON is pretty printed in debug mode.
     */
    public function testJsonIsPrettyPrintedInDebugMode()
    {
        $this->application->setDebug(true);
        $request = new FakeRequest('/', 'get');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame(json_encode(['actionMethod' => 'getAction', 'content' => null], JSON_PRETTY_PRINT), $response->getContent());
    }

    /**
     * Test result types test controller.
     *
     * @dataProvider resultTypesTestControllerDataProvider
     *
     * @param string      $method              The method.
     * @param int         $expectedStatusCode  The expected status code.
     * @param null|string $expectedContentType The expected content type header or null if no content type header is expected.
     * @param string      $expectedContent     The expected content.
     */
    public function testResultTypesTestController(string $method, int $expectedStatusCode, ?string $expectedContentType, string $expectedContent)
    {
        $request = new FakeRequest('/', $method);
        $response = new FakeResponse();
        $controller = new ResultTypesController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContentType, $response->getHeader('Content-Type'));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testResultTypesTestController.
     *
     * @return array
     */
    public function resultTypesTestControllerDataProvider()
    {
        return [
            ['get', StatusCode::NOT_MODIFIED, null, ''],
            ['post', StatusCode::CREATED, 'application/json', '{"Message":"Resource was created"}'],
            ['delete', StatusCode::NETWORK_AUTHENTICATION_REQUIRED, 'application/json', '{"Message":"Failed to remove resource"}'],
            ['put', StatusCode::OK, 'text/plain', 'This should not be altered.'],
        ];
    }

    /**
     * Test controller with custom JSON encode options.
     */
    public function testWithCustomJsonEncodeOptions()
    {
        $request = new FakeRequest();
        $response = new FakeResponse();
        $controller = new CustomJsonEncodeOptionsTestController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
        self::assertSame('{"0":"Bar","1":42}', $response->getContent());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->application = $application = new FakeApplication(__DIR__);
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->application = null;
    }

    /**
     * @var FakeApplication My application.
     */
    private $application;
}
