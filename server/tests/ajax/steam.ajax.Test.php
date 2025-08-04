<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\ajax\SteamAPIHandler.php';
require_once $GLOBALS['rootpath'].'\inc\SteamAPI.class.php';
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';

/**
 * @group api
 */
class SteamAPIHandlerTest extends testprivate
{
	/**
	 * @covers SteamAPIHandler::handleRequest
	 * @covers SteamAPIHandler::__construct
	 * @uses CurlRequest
	 * @uses SteamAPI
	 */
    public function testHandleRequestWithApiParam(): void
    {
        $mockSteamAPI = $this->createMock(SteamAPI::class);
        $mockSteamAPI->expects($this->once())
                     ->method('GetSteamAPI')
                     ->with('some_api')
                     ->willReturn(['result' => 'success']);

        $handler = new SteamAPIHandler($mockSteamAPI);

        $response = $handler->handleRequest(['api' => 'some_api']);

        $this->assertJson($response);
        $this->assertStringContainsString('"result":"success"', $response);
    }

	/**
     * @covers SteamAPIHandler::handleRequest
 	 * @uses SteamAPIHandler
	 * @uses CurlRequest
	 * @uses SteamAPI
     */
    public function testHandleRequestWithoutApiParam(): void
    {
        // You can use a mock even if it's not used
        $mockSteamAPI = $this->createMock(SteamAPI::class);
        $handler = new SteamAPIHandler($mockSteamAPI);

        $response = $handler->handleRequest([]);

        $this->assertJson($response);
        $this->assertStringContainsString('API parameter is missing', $response);
    }
	
	/**
     * You can test sendHeaders using output buffering
     * But testing headers this way only confirms they're *sent*, not their exact value
     * @covers SteamAPIHandler::sendHeaders
	 * @uses SteamAPIHandler
	 * @uses CurlRequest
	 * @uses SteamAPI
     */
    public function testSendHeaders(): void
    {
        // This does not assert values, just that it runs without error
        $mockSteamAPI = $this->createMock(SteamAPI::class);
        $handler = new SteamAPIHandler($mockSteamAPI);

        // Suppress header() output during test run
		ob_start();
		$handler->sendHeaders();
		ob_end_clean();
		$this->assertTrue(true); // Just to mark the test as run
    }
	
	/**
     * @covers SteamAPIHandler::GetHeaders
	 * @uses SteamAPIHandler
	 * @uses CurlRequest
	 * @uses SteamAPI
     */
	public function testGetHeaders()
	{
		$mockSteamAPI = $this->createMock(SteamAPI::class);
        $handler = new SteamAPIHandler($mockSteamAPI);

		$method = $this->getPrivateMethod( 'SteamAPIHandler', 'GetHeaders' );
		$result = $method->invokeArgs( $handler, array() );
		
		$expected = [
			'Content-Type: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH, OPTIONS',
            'Access-Control-Allow-Headers: X-Requested-With',
		];

		$this->assertEquals($expected, $result);
	}
}
