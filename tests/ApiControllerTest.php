<?php

namespace BlueMvc\Api\Tests;

use BlueMvc\Api\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Api\Tests\Helpers\TestControllers\ResultTypesController;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
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
     * Test a GET request.
     */
    public function testGetRequest()
    {
        $request = new FakeRequest('/');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
        self::assertSame('{"actionMethod":"getAction","content":null}', $response->getContent());
    }

    /**
     * Test a POST request with content.
     */
    public function testPostRequestWithContent()
    {
        $request = new FakeRequest('/', 'post');
        $request->setRawContent('{"Foo":"Bar"}');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
        self::assertSame('{"actionMethod":"postAction","content":{"Foo":"Bar"}}', $response->getContent());
    }

    /**
     * Test a POST request with invalid content.
     */
    public function testPostRequestWithInvalidContent()
    {
        $request = new FakeRequest('/', 'post');
        $request->setRawContent('FooBar');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode()->getCode());
        self::assertNull($response->getHeader('Content-Type'));
        self::assertSame('', $response->getContent());
    }

    /**
     * Test a request with invalid method.
     */
    public function testRequestWithInvalidMethod()
    {
        $request = new FakeRequest('/', 'put');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::METHOD_NOT_ALLOWED, $response->getStatusCode()->getCode());
        self::assertNull($response->getHeader('Content-Type'));
        self::assertSame('', $response->getContent());
    }

    /**
     * Test a request with parameter.
     */
    public function testRequestWithParameter()
    {
        $request = new FakeRequest('/foo', 'patch');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, 'foo', []);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
        self::assertSame('{"actionMethod":"patchAction","content":null,"parameter":"foo"}', $response->getContent());
    }

    /**
     * Test a request with missing required parameter.
     */
    public function testRequestWithMissingParameter()
    {
        $request = new FakeRequest('/', 'patch');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
        self::assertNull($response->getHeader('Content-Type'));
        self::assertSame('', $response->getContent());
    }

    /**
     * Test a request returning an action result.
     */
    public function testActionResult()
    {
        $request = new FakeRequest('/', 'get');
        $response = new FakeResponse();
        $controller = new ResultTypesController();
        $controller->processRequest($this->application, $request, $response, '', []);

        self::assertSame(StatusCode::NOT_MODIFIED, $response->getStatusCode()->getCode());
        self::assertNull($response->getHeader('Content-Type'));
        self::assertSame('', $response->getContent());
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
     * @var ApplicationInterface My application.
     */
    private $application;
}
