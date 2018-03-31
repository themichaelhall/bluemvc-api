<?php

namespace BlueMvc\Api\Tests;

use BlueMvc\Api\Tests\Helpers\TestControllers\BasicTestController;
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
        $application = new FakeApplication(__DIR__);
        $request = new FakeRequest('/');
        $response = new FakeResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '', []);

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
