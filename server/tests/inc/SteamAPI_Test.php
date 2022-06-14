<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath']."\inc\SteamAPI.class.php";

/**
 * @group include
 * @group classtest
 */
final class SteamAPI_Test extends testprivate
{
	/**
	 * @small
	 * @covers SteamAPI::__construct
	 * @covers SteamAPI::__destruct
	 * @uses SteamAPI
	 * @uses CurlRequest
	 */
    public function test_construct() {
		$api = new SteamAPI(4088);
		$this->assertisObject($api);
		
		$property = $this->getPrivateProperty( 'SteamAPI', 'steamGameID' );
		$this->assertEquals(4088, $property->getValue( $api ));
    }

	/**
	 * @small
	 * @covers SteamAPI::setAuth
	 * @uses SteamAPI
	 * @uses CurlRequest
	 */
	public function test_setAuth() {
		$api = new SteamAPI(4088);
		
		$property = $this->getPrivateProperty( 'SteamAPI', 'SteamAPIwebkey' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'SteamProfileID' );
		$this->assertNotNull($property->getValue( $api ));		
	}

	/**
	 * @small
	 * @covers SteamAPI::setApiUrls
	 * @uses SteamAPI
	 * @uses CurlRequest
	 * @testWith ["GetOwnedGamesURL"]
	 *           ["GetRecentlyPlayedGamesURL"]
	 *           ["GetPlayerAchievementsURL"]
	 *           ["GetSchemaForGameURL"]
	 *           ["GetGameNewsURL"]
	 *           ["GetSchemaForGameURL"]
	 *           ["GetAppDetailsURL"]
	 *           ["GetSteamPICSURL"]
	 */
	public function test_setApiUrls($urlname) {
		$api = new SteamAPI(4088);
		
		$property = $this->getPrivateProperty( 'SteamAPI', $urlname );
		$this->assertNotNull($property->getValue( $api ));
	}

	/**
	 * @small
	 * @covers SteamAPI::setApiUrls
	 * @uses SteamAPI
	 * @uses CurlRequest
	 * @testWith ["GetPlayerAchievementsURL"]
	 *           ["GetUserStatsForGameURL"]
	 *           ["GetGameNewsURL"]
	 *           ["GetSchemaForGameURL"]
	 *           ["GetAppDetailsURL"]
	 *           ["GetSteamPICSURL"]
	 */
	public function test_setApiUrls_Null($urlname) {
		$api = new SteamAPI();
		
		$property = $this->getPrivateProperty( 'SteamAPI', $urlname );
		$this->assertNull($property->getValue( $api ));
	}
	
	/**
	 * @small
	 * @covers SteamAPI::CallAPI
	 * @uses SteamAPI
	 */
	public function test_CallAPI() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');
		$api = new SteamAPI(4088,$stub);
		
		$method = $this->getPrivateMethod( 'SteamAPI', 'CallAPI' );
		$result = $method->invokeArgs( $api, array("localhost") );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @covers SteamAPI::CallAPI
	 * @uses SteamAPI
	 */
	public function test_CallAPI_null() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');
			 
		$api = new SteamAPI(null,$stub);
		
		$method = $this->getPrivateMethod( 'SteamAPI', 'CallAPI' );
		$result = $method->invokeArgs( $api, array("localhost") );
		$this->assertisArray($result);
		$result = $method->invokeArgs( $api, array(null) );
		$this->assertNull($result);
	}
	
	/**
	 * @small
	 * @covers SteamAPI::GetSteamAPI
	 * @uses SteamAPI
	 * @testWith ["GetOwnedGames"]
	 *           ["GetRecentlyPlayedGames"]
	 *           ["GetPlayerAchievements"]
	 *           ["GetUserStatsForGame"]
	 *           ["GetGameNews"]
	 *           ["GetSchemaForGame"]
	 *           ["GetAppDetails"]
	 *           ["GetSteamPICS"]
	 */
	public function test_GetSteamAPI($APIname) {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');
			 
		$api = new SteamAPI(4088,$stub);
		
		$result = $api->GetSteamAPI($APIname);
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @covers SteamAPI::GetSteamAPI
	 * @uses SteamAPI
	 * @testWith ["GetOwnedGames"]
	 *           ["GetRecentlyPlayedGames"]
	 */
	public function test_GetSteamAPI_Nullarray($APIname) {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

		$api = new SteamAPI(null,$stub);
		
		$result = $api->GetSteamAPI($APIname);
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @covers SteamAPI::GetSteamAPI
	 * @uses SteamAPI
	 * @testWith ["GetPlayerAchievements"]
	 *           ["GetUserStatsForGame"]
	 *           ["GetGameNews"]
	 *           ["GetSchemaForGame"]
	 *           ["GetAppDetails"]
	 *           ["GetSteamPICS"]
	 *           ["other"]
	 */
	public function test_GetSteamAPI_Null($APIname) {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

		$api = new SteamAPI(null,$stub);
		
		$result = $api->GetSteamAPI($APIname);
		$this->assertNull($result);
	}
}