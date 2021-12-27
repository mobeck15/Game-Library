<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\CurlRequest.class.php";

//Time: 00:00.258, Memory: 46.00 MB
//(5 tests, 5 assertions)
/**
 * @group include
 * @group classtest
 */
final class CurlRequest_Test extends TestCase
{
	/**
	 * @group fast
	 * @small
	 * @covers CurlRequest::__construct
	 * Time: 00:00.222, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_construct() {
		$req = new CurlRequest("");
		$this->assertisObject($req);
    }

	/**
	 * @group fast
	 * @small
	 * @covers CurlRequest::setOption
	 * @uses CurlRequest::__construct
	 * @uses CurlRequest::getInfo
	 * @uses CurlRequest::execute
	 * Time: 00:00.258, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_setOption() {
		$req = new CurlRequest("localhost");
		$req->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->assertisString($req->execute());
    }

	/**
	 * @group fast
	 * @small
	 * @covers CurlRequest::execute
	 * @uses CurlRequest::__construct
	 * @uses CurlRequest::setOption
	 * Time: 00:00.252, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_execute() {
		$req = new CurlRequest("localhost");
		$req->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->assertisString($req->execute());
    }

	/**
	 * @group fast
	 * @small
	 * @covers CurlRequest::getInfo
	 * @uses CurlRequest::__construct
	 * @uses CurlRequest::execute
	 * @uses CurlRequest::setOption
	 * Time: 00:00.239, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getInfo() {
		$req = new CurlRequest("localhost");
		$req->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->assertisString($req->execute());
		$info=$req->getInfo(CURLINFO_HTTP_CODE);
		$this->assertisNumeric($info);
    }

	/**
	 * @group fast
	 * @small
	 * @covers CurlRequest::close
	 * @uses CurlRequest::__construct
	 * @doesNotPerformAssertions
	 * Time: 00:00.218, Memory: 46.00 MB
	 * (1 test, 0 assertions)
	 */
    public function test_close() {
		$req = new CurlRequest("http://localhost");
		$req->close();
    }
}