<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\SteamAPI.class.php";

/**
 * @group include
 * @group classtest
 */
final class SteamAPI_Test extends TestCase
{
	/**
	 * @group fast
	 * @small
	 * @covers SteamAPI::__construct
	 * @covers SteamAPI::__destruct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses CurlRequest::__construct
	 * Time
	 */
    public function test_construct() {
		$api = new SteamAPI(4088);
		$this->assertisObject($api);
		
		$property = $this->getPrivateProperty( 'SteamAPI', 'steamGameID' );
		$this->assertEquals(4088, $property->getValue( $api ));
    }

	/**
	 * @group fast
	 * @small
	 * @covers SteamAPI::setAuth
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses CurlRequest::__construct
	 * @uses SteamAPI::__destruct
	 * Time
	 */
	public function test_setAuth() {
		$api = new SteamAPI(4088);
		
		$property = $this->getPrivateProperty( 'SteamAPI', 'SteamAPIwebkey' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'SteamProfileID' );
		$this->assertNotNull($property->getValue( $api ));		
	}

	/**
	 * @group fast
	 * @small
	 * @covers SteamAPI::setApiUrls
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setAuth
	 * @uses CurlRequest::__construct
	 * @uses SteamAPI::__destruct
	 * Time
	 */
	public function test_setApiUrls() {
		$api = new SteamAPI(4088);
		
		$property = $this->getPrivateProperty( 'SteamAPI', 'GetOwnedGamesURL' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetRecentlyPlayedGamesURL' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetPlayerAchievementsURL' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetUserStatsForGameURL' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetGameNewsURL' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetSchemaForGameURL' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetAppDetailsURL' );
		$this->assertNotNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetSteamPICSURL' );
		$this->assertNotNull($property->getValue( $api ));
	}

	/**
	 * @group fast
	 * @small
	 * @covers SteamAPI::setApiUrls
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setAuth
	 * @uses CurlRequest::__construct
	 * @uses SteamAPI::__destruct
	 * Time
	 */
	public function test_setApiUrls_Null() {
		$api = new SteamAPI();
		
		$property = $this->getPrivateProperty( 'SteamAPI', 'GetPlayerAchievementsURL' );
		$this->assertNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetUserStatsForGameURL' );
		$this->assertNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetGameNewsURL' );
		$this->assertNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetSchemaForGameURL' );
		$this->assertNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetAppDetailsURL' );
		$this->assertNull($property->getValue( $api ));

		$property = $this->getPrivateProperty( 'SteamAPI', 'GetSteamPICSURL' );
		$this->assertNull($property->getValue( $api ));
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamAPI::CallAPI
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 * Time
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
	 * @group fast
	 * @small
	 * @covers SteamAPI::CallAPI
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 * Time
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
	 * @group fast
	 * @small
	 * @covers SteamAPI::GetSteamAPI
	 * @uses SteamAPI::CallAPI
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 * Time
	 */
	public function test_GetSteamAPI() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');
			 
		$api = new SteamAPI(4088,$stub);
		
		$result = $api->GetSteamAPI("GetOwnedGames");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetRecentlyPlayedGames");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetPlayerAchievements");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetUserStatsForGame");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetGameNews");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetSchemaForGame");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetAppDetails");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetSteamPICS");
		$this->assertisArray($result);
	}

	/**
	 * @group fast
	 * @small
	 * @covers SteamAPI::GetSteamAPI
	 * @uses SteamAPI::CallAPI
	 * @uses SteamAPI::__construct
	 * @uses SteamAPI::setApiUrls
	 * @uses SteamAPI::setAuth
	 * @uses SteamAPI::__destruct
	 * Time
	 */
	public function test_GetSteamAPI_Null() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

		$api = new SteamAPI(null,$stub);
		
		$result = $api->GetSteamAPI("GetOwnedGames");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetRecentlyPlayedGames");
		$this->assertisArray($result);
		
		$result = $api->GetSteamAPI("GetPlayerAchievements");
		$this->assertNull($result);
		
		$result = $api->GetSteamAPI("GetUserStatsForGame");
		$this->assertNull($result);
		
		$result = $api->GetSteamAPI("GetGameNews");
		$this->assertNull($result);
		
		$result = $api->GetSteamAPI("GetSchemaForGame");
		$this->assertNull($result);
		
		$result = $api->GetSteamAPI("GetAppDetails");
		$this->assertNull($result);
		
		$result = $api->GetSteamAPI("GetSteamPICS");
		$this->assertNull($result);
		
		$result = $api->GetSteamAPI("other");
		$this->assertNull($result);
	}
		
	/**
 	 * getPrivateProperty
 	 *
 	 * @author	Joe Sexton <joe@webtipblog.com>
 	 * @param 	string $className
 	 * @param 	string $propertyName
 	 * @return	ReflectionProperty
	 * Source: https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
 	 */
	public function getPrivateProperty( $className, $propertyName ) {
		$reflector = new ReflectionClass( $className );
		$property = $reflector->getProperty( $propertyName );
		$property->setAccessible( true );

		return $property;
	}

	/**
 	 * getPrivateMethod
 	 *
 	 * @author	Joe Sexton <joe@webtipblog.com>
 	 * @param 	string $className
 	 * @param 	string $methodName
 	 * @return	ReflectionMethod
	 * Source: https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
 	 */
	public function getPrivateMethod( $className, $methodName ) {
		$reflector = new ReflectionClass( $className );
		$method = $reflector->getMethod( $methodName );
		$method->setAccessible( true );

		return $method;
	}
	
}