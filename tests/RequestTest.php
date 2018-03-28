<?php

namespace BlueMvc\Api\Tests;

use BlueMvc\Api\Tests\Helpers\TestControllers\BasicTestController;
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
     * Test GET request.
     */
    public function testGetRequest()
    {
        $application = new FakeApplication(__DIR__);
        $application->addRoute(new Route('', BasicTestController::class));
        $request = new FakeRequest('/');
        $response = new FakeResponse();

        $application->run($request, $response);

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame(['Content-Type' => 'application/json'], iterator_to_array($response->getHeaders()));
        self::assertSame('{"actionMethod":"getAction"}', $response->getContent());
    }
}
