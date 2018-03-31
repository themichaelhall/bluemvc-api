<?php

namespace BlueMvc\Api\Tests;

use BlueMvc\Api\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
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
     * Test GET request.
     */
    public function testGetRequest()
    {
        $request = new FakeRequest('/');
        $response = new FakeResponse();

        $this->application->run($request, $response);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame('{"actionMethod":"getAction","content":null}', $response->getContent());
    }

    /**
     * Test POST request with content.
     */
    public function testPostRequestWithContent()
    {
        $request = new FakeRequest('/', 'post');
        $request->setRawContent('{"Baz":false}');
        $response = new FakeResponse();

        $this->application->run($request, $response);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame('{"actionMethod":"postAction","content":{"Baz":false}}', $response->getContent());
    }

    /**
     * Test POST request with invalid content.
     */
    public function testPostRequestWithInvalidContent()
    {
        $request = new FakeRequest('/', 'post');
        $request->setRawContent('{"Foo"');
        $response = new FakeResponse();

        $this->application->run($request, $response);

        self::assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode()->getCode());
        self::assertSame([], iterator_to_array($response->getHeaders()));
        self::assertSame('', $response->getContent());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->application = new FakeApplication(__DIR__);
        $this->application->addRoute(new Route('', BasicTestController::class));
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