<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/ratings.class.php";

/**
 * @group pageclass
 * @testdox ratings_Test.php testing ratings.class.php
 */
class ratings_Test extends testprivate {
	/**
	 * @small
	 * @covers ratingsPage::buildHtmlBody
	 * @covers ratingsPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses ratingsPage
	 * @uses Page
	 * @uses RatingsChartData
	 */
	public function test_outputHtml() {
		$page = new ratingsPage();
		
		$calculations = array(
			1=> array(
				"Metascore" => 1,
				"UserMetascore" => 1,
				"SteamRating" => 1,
				"Review" => 1,
				"Want" => 1
			)
		);
		
		$dataStub = $this->createStub(dataSet::class);
		$dataStub->method('getCalculations')
				 ->willReturn($calculations);
		$maxID = $this->getPrivateProperty( 'ratingsPage', 'data' );
		$maxID->setValue( $page , $dataStub );
		
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}