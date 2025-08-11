<?php
//declare(strict_types=1);
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/utility.inc.php";
require_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
require_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getPurchases.class.php";
require_once $GLOBALS['rootpath']."/inc/dataAccess.class.php";

class Games {
	private $dataAccess;

    public function __construct(dataAccess $dataAccess = null) {
        $this->dataAccess = $dataAccess ?? new dataAccess();
    }
	
	public function buildGameArray($gameID = "", $connection = false) {
        $games = [];
        $statement = $this->dataAccess->getGames($gameID);
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $games[] = $this->CalculateGameRow($row);
        }
        return $games;
    }
	
	protected function CalculateGameRow($row){
		$row['Game_ID']=(int)$row['Game_ID'];
		
		$row = $this->normalizeLaunchDate($row);
		//TODO: update other files to remove need for 'LaunchDateValue'
		
		$row = $this->normalizeDate($row, 'LowDate');
		$row = $this->normalizeDate($row, 'DateUpdated', 'DateUpdatedSort');
		
		$row = $this->normalizePrice($row, 'LaunchPrice', $row['MSRP']);
		$row = $this->normalizePrice($row, 'MSRP', $row['MSRP']);
		$row = $this->normalizePrice($row, 'CurrentMSRP', $row['MSRP']);
		$row = $this->normalizePrice($row, 'HistoricLow', min($row['CurrentMSRP'], $row['MSRP']));

		$row = $this->intToNull($row, 'SteamAchievements');
		$row = $this->intToNull($row, 'SteamID');
		$row = $this->intToNull($row, 'SteamCards');
		
		$row = $this->normalizeScoreLink(
			$row, 'Metascore', 'MetascoreLinkCritic', 'MetascoreID', 'http://www.metacritic.com/game/'
		);

		$row = $this->normalizeScoreLink(
			$row, 'UserMetascore', 'MetascoreLinkUser', 'MetascoreID', 'http://www.metacritic.com/game/'
		);
		
		$row = $this->intToNull($row, 'SteamRating');
		
		$row = $this->setStoreOrSearchLink(
			$row, 'SteamID', 'SteamLinks',
			'http://store.steampowered.com/app/',
			'http://store.steampowered.com/search/?term='
		);

		$row = $this->setStoreOrSearchLink(
			$row, 'GOGID', 'GOGLink',
			'http://www.gog.com/game/',
			'http://www.gog.com/games##search='
		);

		$row = $this->setStoreOrSearchLink(
			$row, 'DesuraID', 'DesuraLink',
			'http://www.desura.com/games/',
			'http://www.desura.com/search?q='
		);

		$row = $this->setStoreOrSearchLink(
			$row, 'isthereanydealID', 'isthereanydealLink',
			'http://isthereanydeal.com/game/',
			'http://isthereanydeal.com/search?q='
		);
		
		$row = $this->normalizeTimeToBeat($row);
		$row = $this->normalizeMetascoreLinks($row);

		$row = $this->normalizeIntField($row, 'Want');
		$row = $this->normalizeIntField($row, 'ParentGameID');
		$row = $this->normalizeBoolField($row, 'Playable');
		
		return $row;
	}
	
	private function intToNull(array $row, string $field) : array
	{
		$row[$field] = (int) $row[$field];
		if ($row[$field] === 0) {
			$row[$field] = null;
		}
		return $row;
	}
	
	private function normalizePrice(array $row, string $field, $fallbackValue): array
	{
		if ($row[$field] == 0) {
			$row[$field] = sprintf("%.2f", $fallbackValue);
		} else {
			$row[$field] = sprintf("%.2f", $row[$field]);
		}
		return $row;
	}
	
	private function normalizeLaunchDate(array $row): array
	{
		if ($row['LaunchDate'] == null || $row['LaunchDate'] === "0000-00-00") {
			$row['LaunchDate'] = "0000-00-00";
			$this->triggerGameError($row, "has no launch date set.");
		}

		$row['LaunchDate'] = new DateTime($row['LaunchDate']);
		return $row;
	}

	private function normalizeDate(array $row, string $field, ?string $sortField = null): array
	{
		// Treat empty strings and 0-timestamp dates as "no date"
		if (empty($row[$field]) || strtotime($row[$field]) === 0) {
			$row[$field] = "";
			if ($sortField) {
				$row[$sortField] = "";
			}
		} else {
			if ($sortField) {
				$row[$sortField] = $row[$field];
			}
			$row[$field] = date("n/j/Y", strtotime($row[$field]));
		}
		return $row;
	}
	
	private function triggerGameError(array $row, string $message): void
	{
		trigger_error("<a href=''>" . $row['Game_ID'] . " - " . $row['Title'] . "</a> $message", E_USER_WARNING);
	}
	
	private function normalizeScoreLink(array $row, string $scoreField, string $linkField, string $idField, string $baseUrl): array
	{
		$row[$scoreField] = (int) $row[$scoreField];
		if ($row[$scoreField] === 0) {
			if ($row[$idField] === "") {
				$row[$scoreField] = null;
			} else {
				$row[$linkField] = "<a class='Search' href='{$baseUrl}{$row[$idField]}' target='_blank'>N/A</a>";
			}
		} else {
			$row[$linkField] = "<a href='{$baseUrl}{$row[$idField]}' target='_blank'>{$row[$scoreField]}</a>";
		}
		return $row;
	}

	private function setStoreOrSearchLink(array $row, string $idField, string $linkField, string $storeUrl, string $searchUrl): array
	{
		if (!empty($row[$idField])) {
			$row[$linkField] = "<a href='{$storeUrl}{$row[$idField]}' target='_blank'>Store</a>";
		} else {
			$row[$linkField] = "<a class='Search' href='{$searchUrl}" . urlencode($row['Title']) . "' target='_blank'>Search</a>";
		}
		return $row;
	}
	
	private function normalizeTimeToBeat(array $row): array
	{
		$row['TimeToBeatID'] = (int) $row['TimeToBeatID'];

		if ($row['TimeToBeatID'] !== 0) {
			$url = "http://howlongtobeat.com/game.php?id=" . $row['TimeToBeatID'];
			$row['TimeToBeatLink'] = "<a href='{$url}' target='_blank'>Link</a>";

			if ((int)$row['TimeToBeat'] === 0) {
				$row['TimeToBeatLink2'] = "<a class='Search' href='{$url}' target='_blank'>N/A</a>";
			} else {
				$duration = timeduration($row['TimeToBeat'], "hours");
				$row['TimeToBeatLink2'] = "<a href='{$url}' target='_blank'>{$duration}</a>";
			}
		} else {
			$row['TimeToBeatLink'] = "";
			$row['TimeToBeatLink2'] = "";
		}

		return $row;
	}

	private function normalizeMetascoreLinks(array $row): array
	{
		if ($row['MetascoreID'] !== "") {
			$url = "http://www.metacritic.com/game/" . $row['MetascoreID'];
			$row['MetascoreLink'] = "<a href='{$url}' target='_blank'>Link</a>";
		} else {
			$searchUrl = "http://www.metacritic.com/search/game/" . urlencode($row['Title']) . "/results";
			$link = "<a class='Search' href='{$searchUrl}' target='_blank'>Search</a>";
			$row['MetascoreLink'] = $link;
			$row['MetascoreLinkCritic'] = $link;
			$row['MetascoreLinkUser'] = $link;
		}

		return $row;
	}

	private function normalizeIntField(array $row, string $field): array
	{
		$row[$field] = (int) $row[$field];
		return $row;
	}

	private function normalizeBoolField(array $row, string $field): array
	{
		$row[$field] = (bool) $row[$field];
		return $row;
	}
}

class Game {
	public int $Game_ID;
	public DateTime $LaunchDate;
	
	public function __construct($GameRowArray) {
		$this->Game_ID    = (int)$GameRowArray['Game_ID'];
		$this->LaunchDate = new DateTime($GameRowArray['LaunchDate']);
	}
}

function getGames($gameID="",$connection=false) : array
{
	$gamesobj=new Games();
	return $gamesobj->buildGameArray($gameID,$connection);
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getGames.class") {
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="Games Inc Test";
	echo Get_Header($title);
	
	$lookupgame=lookupTextBox("Product", "ProductID", "id", "Game", $GLOBALS['rootpath']."/ajax/search.ajax.php");
	echo $lookupgame["header"];
	if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
		?>
		Please specify a game by ID.
		<form method="Get">
			<?php echo $lookupgame["textBox"]; ?>
			<input type="submit">
		</form>

		<?php
		echo $lookupgame["lookupBox"];
	} else {	
		$games=reIndexArray(getGames(""),"Game_ID");
		echo arrayTable($games[$_GET['id']]);
	}
	echo Get_Footer();
}
?>
