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
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses CurlRequest::__construct
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
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses CurlRequest::__construct
	 * @uses SteamAPI::__destruct
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
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setAuth
	 * @uses CurlRequest::__construct
	 * @uses SteamAPI::__destruct
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
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setAuth
	 * @uses CurlRequest::__construct
	 * @uses SteamAPI::__destruct
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
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 */
	public function test_CallAPI() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
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
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 */
	public function test_CallAPI_null() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
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
	 * @uses SteamAPI::CallAPI
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
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
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');
		//$stub=null;
			 
		$api = new SteamAPI(4088,$stub);
		
		$result = $api->GetSteamAPI($APIname);
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @covers SteamAPI::GetSteamAPI
	 * @uses SteamAPI::CallAPI
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 * @testWith ["GetOwnedGames"]
	 *           ["GetRecentlyPlayedGames"]
	 */
	public function test_GetSteamAPI_Nullarray($APIname) {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

		$api = new SteamAPI(null,$stub);
		
		$result = $api->GetSteamAPI($APIname);
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @covers SteamAPI::GetSteamAPI
	 * @uses SteamAPI::CallAPI
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 * @testWith ["GetPlayerAchievements"]
	 *           ["GetUserStatsForGame"]
	 *           ["GetGameNews"]
	 *           ["GetSchemaForGame"]
	 *           ["GetAppDetails"]
	 *           ["GetSteamPICS"]
	 *           ["other"]
	 */
	public function test_GetSteamAPI_Null($APIname) {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

		$api = new SteamAPI(null,$stub);
		
		$result = $api->GetSteamAPI($APIname);
		$this->assertNull($result);
	}
}