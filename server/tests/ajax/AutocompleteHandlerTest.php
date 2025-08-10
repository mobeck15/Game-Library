<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\ajax\AutocompleteHandler.php';

/**
 * @group api
 */
class AutocompleteHandlerTest extends TestCase
{
    private mysqli $mockConn;

    protected function setUp(): void
    {
        $this->mockConn = $this->createMock(mysqli::class);
    }

	/**
	 * @small
	 */
    public function testSearchReturnsExpectedResults()
    {
        $stmtMock = $this->createMock(mysqli_stmt::class);
        $resultMock = $this->createMock(mysqli_result::class);

        $sampleRow = ['id' => 1, 'label' => 'Half-Life'];
        $resultMock->method('fetch_assoc')
            ->willReturnOnConsecutiveCalls($sampleRow, null);

        $stmtMock->method('get_result')
            ->willReturn($resultMock);

        $stmtMock->method('bind_param')->willReturn(true);
        $stmtMock->method('execute')->willReturn(true);

        $this->mockConn->method('prepare')
            ->willReturn($stmtMock);

        $handler = new AutocompleteHandler($this->mockConn);
        $result = $handler->search('Game', 'Half');

        $this->assertSame([$sampleRow], $result);
    }

	/**
	 * @small
	 */
    public function testInvalidQueryTypeThrows()
    {
        $handler = new AutocompleteHandler($this->mockConn);

        $this->expectException(InvalidArgumentException::class);
        $handler->search('NonexistentType', 'abc');
    }
	
	/**
	 * @testWith ["stealth", "Game"]
	 *           ["stealth", "Trans"]
	 * @testdox Query type $type returns results with id and label for "$term"
	 * @group dbconnect
	 * @small
	 */
	public function testSearchReturnsIdAndLabel($type, $term)
	{
		$stmtMock = $this->createMock(mysqli_stmt::class);
		$resultMock = $this->createMock(mysqli_result::class);

		$sampleRow = ['id' => 1, 'label' => 'Half-Life'];
		$resultMock->method('fetch_assoc')
			->willReturnOnConsecutiveCalls($sampleRow, null);

		$stmtMock->method('get_result')->willReturn($resultMock);
		$stmtMock->method('bind_param')->willReturn(true);
		$stmtMock->method('execute')->willReturn(true);

		$this->mockConn->method('prepare')->willReturn($stmtMock);

		$handler = new AutocompleteHandler($this->mockConn);
		$result = $handler->search($term, $type);

		$this->assertSame([$sampleRow], $result);
	}

	/**
	 * @testWith ["steam", "DRM"]
	 *           ["Windows", "OS"]
	 *           ["steam", "Library"]
	 *           ["stealth", "Series"]
	 *           ["game", "Type"]
	 *           ["valve", "Developer"]
	 *           ["valve", "Publisher"]
	 *           ["steam", "Store"]
	 * @testdox Query type $type returns results with label only for "$term"
	 * @group dbconnect
	 * @small
	 */
	public function testSearchReturnsLabelOnly($type, $term)
	{
		$stmtMock = $this->createMock(mysqli_stmt::class);
		$resultMock = $this->createMock(mysqli_result::class);

		$sampleRow = ['label' => 'Half-Life'];
		$resultMock->method('fetch_assoc')
			->willReturnOnConsecutiveCalls($sampleRow, null);

		$stmtMock->method('get_result')->willReturn($resultMock);
		$stmtMock->method('bind_param')->willReturn(true);
		$stmtMock->method('execute')->willReturn(true);

		$this->mockConn->method('prepare')->willReturn($stmtMock);

		$handler = new AutocompleteHandler($this->mockConn);
		$result = $handler->search($term, $type);

		$this->assertSame([$sampleRow], $result);
	}
	

}
