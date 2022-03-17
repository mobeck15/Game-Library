<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\CurlRequest.class.php";

/**
 * @group include
 * @group classtest
 */
final class CurlRequest_Test extends TestCase
{
	/**
	 * @small
	 * @covers CurlRequest::__construct
	 */
    public function test_construct() {
		$req = new CurlRequest("");
		$this->assertisObject($req);
    }

	/**
	 * @small
	 * @covers CurlRequest::setOption
	 * @uses CurlRequest::__construct
	 * @uses CurlRequest::getInfo
	 * @uses CurlRequest::execute
	 */
    public function test_setOption() {
		$req = new CurlRequest("localhost");
		$req->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->assertisString($req->execute());
    }

	/**
	 * @small
	 * @covers CurlRequest::execute
	 * @uses CurlRequest::__construct
	 * @uses CurlRequest::setOption
	 */
    public function test_execute() {
		$req = new CurlRequest("localhost");
		$req->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->assertisString($req->execute());
    }

	/**
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
	 * @small
	 * @covers CurlRequest::close
	 * @uses CurlRequest::__construct
	 * @doesNotPerformAssertions
	 */
    public function test_close() {
		$req = new CurlRequest("http://localhost");
		$req->close();
    }
}