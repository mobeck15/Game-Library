<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath']."\inc\CurlRequest.class.php";

/**
 * @testdox CurlRequest_Test.php testing CurlRequest.class.php
 * @group include
 * @group classtest
 */
final class CurlRequest_Test extends testprivate
{
	/**
	 * @testdox __construct
	 * @small
	 * @covers CurlRequest::__construct
	 */
    public function test_construct() {
		$req = new CurlRequest("");
		$this->assertisObject($req);
    }

	/**
	 * @testdox setOption & execute
	 * @small
	 * @covers CurlRequest::setOption
	 * @covers CurlRequest::execute
	 * @uses CurlRequest
	 */
    public function test_setOption() {
		$req = new CurlRequest("localhost");
		$req->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->assertisString($req->execute());
    }

	/**
	 * @testdox getInfo
	 * @small
	 * @covers CurlRequest::getInfo
	 * @uses CurlRequest::__construct
	 * @uses CurlRequest::execute
	 * @uses CurlRequest::setOption
	 */
    public function test_getInfo() {
		$req = new CurlRequest("localhost");
		$req->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->assertisString($req->execute());
		$info=$req->getInfo(CURLINFO_HTTP_CODE);
		$this->assertisNumeric($info);
    }

	/**
	 * @testdox close
	 * @small
	 * @covers CurlRequest::close
	 * @uses CurlRequest
	 */
    public function test_close() {
		$req = new CurlRequest("localhost");
		
		$property = $this->getPrivateProperty( 'CurlRequest', 'handle' );
		$handle=$property->getValue( $req );
		$this->assertNotNull($handle);
		$this->assertEquals("resource",gettype($handle));

		$req->close();

		$property = $this->getPrivateProperty( 'CurlRequest', 'handle' );
		$handle=$property->getValue( $req );
		$this->assertEquals("resource (closed)",gettype($handle));
    }
}