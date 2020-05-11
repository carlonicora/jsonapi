<?php
namespace carlonicora\jsonapi\tests\unit;

use carlonicora\jsonapi\document;
use carlonicora\jsonapi\response;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use JsonException;

class responseTest extends abstractTestCase
{

    public function testResponseCreation() : void
    {
        $response = $this->generateResponse();

        $this->assertEquals($this->generateDocumentEmpty(), $response->document);
    }

    public function testEmptyDocumentGeneration() : void
    {
        $this->assertEquals($this->jsonDocumentMinimal, $this->generateResponse()->render());
    }

    public function testJsonException() : void
    {
        $response = $this->generateResponse();

        $document = $this->getMockBuilder(document::class)
            ->getMock();

        $document->method('export')
            ->willThrowException(
                new JsonException()
            );

        /** @var document $document */
        $response->document = $document;

        $this->assertEquals($this->jsonResponseError, $response->render());
    }

    public function testJsonRecursiveException() : void
    {
        $response = $this->getMockBuilder(response::class)
            ->onlyMethods(['generateResponseData'])
            ->getMock();

        $response->method('generateResponseData')
            ->willThrowException(
                new JsonException()
            );

        /** @var response $response */

        $this->assertNull($response->render());
    }

    public function test200() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_200);
        $this->assertEquals('OK', $this->invokeMethod($response, 'generateStatusText'));
        $this->assertEquals($this->jsonDocumentMinimal, $response->render());
    }

    public function test201() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_201);
        $this->assertEquals('Created', $this->invokeMethod($response, 'generateStatusText'));
        $this->assertNull($response->render());
    }

    public function test204() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_204);
        $this->assertEquals('No Content', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test304() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_304);
        $this->assertEquals('Not Modified', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test400() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_400);
        $this->assertEquals('Bad Request', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test401() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_401);
        $this->assertEquals('Unauthorized', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test403() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_403);
        $this->assertEquals('Forbidden', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test404() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_404);
        $this->assertEquals('Not Found', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test405() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_405);
        $this->assertEquals('Method Not Allowed', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test406() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_406);
        $this->assertEquals('Not Acceptable', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test409() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_409);
        $this->assertEquals('Conflict', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test410() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_410);
        $this->assertEquals('Gone', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test411() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_411);
        $this->assertEquals('Length Required', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test412() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_412);
        $this->assertEquals('Precondition Failed', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test415() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_415);
        $this->assertEquals('Unsupported Media Type', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test422() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_422);
        $this->assertEquals('Unprocessable Entity', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test428() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_428);
        $this->assertEquals('Precondition Required', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test429() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_429);
        $this->assertEquals('Too Many Requests', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test500() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_500);
        $this->assertEquals('Internal Server Error', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test501() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_501);
        $this->assertEquals('Not Implemented', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test502() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_502);
        $this->assertEquals('Bad Gateway', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test503() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_503);
        $this->assertEquals('Service Unavailable', $this->invokeMethod($response, 'generateStatusText'));
    }

    public function test504() : void
    {
        $response = $this->generateResponse(response::HTTP_STATUS_504);
        $this->assertEquals('Gateway Timeout', $this->invokeMethod($response, 'generateStatusText'));
    }
}