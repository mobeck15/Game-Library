<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getTopList.class.php";

/**
 * @group include
 * @group topList
 */
final class getTopList_Test extends testprivate
{
	/**
	 * @small
	 * @covers TopList
	 * @uses TopList
	 * @uses dataSet
	 * @uses Keywords
	 * @uses dataAccess
	 * @testWith [""]
	 *           ["Series"]
	 *           ["Store"]
	 *           ["Library"]
	 *           ["SteamR10"]
	 *           ["SteamR"]
	 *           ["PYear"]
	 *           ["LYear"]
	 *           ["Keyword"]
	 */
	public function test_getTopList($listType) {
		$data = $this->getTestDataSet();
		$topListObject=new TopList($data);
		
		$result = $topListObject->buildTopListArray($listType);
		$this->assertisArray($result);
		$this->assertArrayHasKey('leastPlay', current($result));
	}
	
	/**
	 * @small
	 * @covers TopList::buildSeriesTopList
	 * @uses TopList
	 * @uses dataSet
	 * @uses Keywords
	 * @uses dataAccess
	 */
	public function test_SeriesTopListWithNoPurchaseDate()
	{
		$listType="Series";
		$data = $this->getTestDataSet();
		
		$calculations = $data->getCalculations();
		$index=count($calculations);
		$calculations[$index] = $calculations[0];
		$calculations[$index]["Series"] = "One of a Kind";
		unset($calculations[$index]["PurchaseDateTime"]);
		
		$data2 = new dataSet(purchases: $data->getPurchases(), calculations: $calculations);
		
		$topListObject=new TopList($data2);
		$this->assertisArray($topListObject->buildTopListArray($listType));	
	}
	
	/**
	 * @small
	 * @covers TopList::updateBeatAverageStats
	 * @uses TopList
	 * @uses dataSet
	 * @uses Keywords
	 * @uses dataAccess
	 */
	public function testUpdateBeatAverageStats()
	{
		$data = $this->getTestDataSet();
		$topListObject=new TopList($data);

		$total = [
				'BeatAvg' => 1,
				'BeatAvg2' => 1,
			];
		
		$top = [
			'row1' => [
				'Products' => [2],
				'PctPlayed' => 1,
				'GameCount' => 1,
				'UnplayedCount' => 1,
				'Paid' => 0
			],
			'row2' => [
				'Products' => [2],
				'PctPlayed' => 2,
				'GameCount' => 1,
				'UnplayedCount' => 1,
				'Paid' => 0
			]
		];

		// Access private method
		$method = $this->getPrivateMethod(TopList::class, 'updateBeatAverageStats');

		// Invoke method
		$method->invokeArgs($topListObject, [&$top, $total]);

		// Assert returned value contains 'BeatAvg' key (added by updateBeatAverageStats)
		$this->assertIsArray($top);
		$this->assertArrayHasKey('BeatAvg', $top['row1']);
	}

	/**
	 * @small
	 * @covers TopList::updateRowWithProductStats
	 * @uses TopList
	 * @uses Keywords
	 * @uses dataAccess
	 * @uses dataSet
	 */
	public function testUpdateRowWithProductStats()
	{
		$data = $this->getTestDataSet();
		$topListObject=new TopList($data);

		$totalWant=0;
		$GrandTotalWant=0;
		$productId = 1;
		$row = [
			'ItemCount' => 0,
			'TotalLaunch' => 0,
			'TotalMSRP' => 0,
			'TotalHistoric' => 0,
			'TotalHours' => 0,
			'GameCount' => 0,
			'InactiveCount' => 0,
			'UnplayedInactiveCount' => 0,
			'UnplayedCount' => 0,
			'IncompleteCount' => 0
		];

		// Access private method
		$method = $this->getPrivateMethod(TopList::class, 'updateRowWithProductStats');

		// Invoke method
		$method->invokeArgs($topListObject, [&$row, $productId, &$totalWant, &$GrandTotalWant]);

		$this->assertIsArray($row);
	}
	
	/**
	 * @small
	 * @covers TopList::toTimestamp
	 * @uses TopList
	 * @uses dataSet
	 */
	public function test_toTimestamp()
	{	
		$topListObject=new TopList();
		
		// Access private method
		$method = $this->getPrivateMethod(TopList::class, 'toTimestamp');

		// Invoke method
		$result = $method->invokeArgs($topListObject, [1]);

		$this->assertEquals($result, 1);
	}
	
	
	/**
	 * @small
	 * @covers TopList::addNoneKeyword
	 * @uses TopList
	 * @uses dataSet
	 */
	public function test_addNoneKeyword()
	{	
		$data = $this->getTestDataSet();
		
		$calculations = $data->getCalculations();
		$index=count($calculations);
		$calculations[$index] = $calculations[0];
		$calculations[$index]["Series"] = "One of a Kind";
		unset($calculations[$index]["PurchaseDateTime"]);
		
		$data2 = new dataSet(purchases: $data->getPurchases(), calculations: $calculations);
		
		$topListObject=new TopList($data2);
		
		// Access private method
		$method = $this->getPrivateMethod(TopList::class, 'addNoneKeyword');

		// Invoke method
		$result = $method->invokeArgs($topListObject, [[1]]);

		$this->assertIsArray($result);
	}
}