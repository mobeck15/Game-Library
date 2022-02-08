<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getCalculations.inc.php";

/**
 * @group integration
 * @group purchases
 */
final class integration_Test extends TestCase
{
	/**
	 * @large
	 * @coversNothing
	 * @testWith [30]
	 *           [381]
	 *           [2002]
	 *           [2269]
	 */
	public function test_saleprice($useproductid) {
		/*
		 * @testWith [30]
		 *           [381]
		 *           [2002]
		 *           [2269]
		 * 30   = Wizardry 7
		 * 381  = VVVVVV
		 * 2002 = HurtWorld
		 * 2269 = Darksiders 2 Deathinitive edition
		 */
		//Formula: (MSRP/TotalMSRP)*BundlePrice
		$conn=get_db_connection();
		$settings=getsettings($conn);
		$calculations=reIndexArray(getCalculations("",$conn),"Game_ID");
		$purchases=reIndexArray(getPurchases("",$conn), "TransID");
		
		foreach ($calculations[$useproductid]["TopBundleIDs"] as $bundle) {
			$totalMSRP=0;
			$totalSale=0;
			foreach ($purchases[$bundle]["GamesinBundle"] as $gamein){
				if($settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] == 1) {
					$totalMSRP+=$calculations[$gamein["GameID"]]["MSRPPriceObj"]->getPrice();
					$totalSale+=$calculations[$gamein["GameID"]]["SalePriceObj"]->getPrice();
				}				
			}
			
			$totalExpectedSale=0;
			foreach ($purchases[$bundle]["GamesinBundle"] as $gamein){
				$expectedSale=($calculations[$gamein["GameID"]]["MSRP"]/$totalMSRP)*$purchases[$bundle]["Paid"];
				if($settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] == 1) {
					$totalExpectedSale += $expectedSale;
				}
				$this->assertEquals($expectedSale,$calculations[$gamein["GameID"]]["SalePriceObj"]->getPrice());
			}
			$this->assertEquals($totalExpectedSale,$totalSale);
		}
	}

	/**
	 * @large
	 * @coversNothing
	 * @testWith [30]
	 *           [381]
	 *           [2002]
	 *           [2269]
	 *           [79]
	 */
	public function test_altsaleprice($useproductid) {
		/*
		 * @testWith [30]
		 *           [381]
		 *           [2002]
		 *           [2269]
		 *           [79]
		 * 30   = Wizardry 7
		 * 381  = VVVVVV
		 * 2002 = HurtWorld
		 * 2269 = Darksiders 2 Deathinitive edition
		 * 79   = 11th Hour

		Formula testing sheet:
		https://docs.google.com/spreadsheets/d/1JOPWjHbGe6j5kAMhZboDVPw51a_9BUTZXdXigm07IC0/edit#gid=0

		//Formula:
		//GL3=sum(filter(Purchases!AM:AM,Purchases!C:C=A2,Purchases!B:B<>"Bundle"))
		//Purchases!AM=if(AI2=A2,R2, (iferror(S2/sum(filter(S:S,AI:AI=AI2,B:B<>"Bundle",AP:AP=true)),1/COUNTA(filter(A:A,AI:AI=AI2,B:B<>"Bundle",AP:AP=true)))*AJ2*Settings!$F$18)+iferror(O2/sum(filter(O:O,AI:AI=AI2,B:B<>"Bundle",AP:AP=true))*AJ2*Settings!$F$17,0)+if(sum(iferror(filter(AK:AK,AI:AI=AI2,B:B<>"Bundle",AP:AP=true)))=0,1/counta(filter(A:A,AI:AI=AI2,B:B<>"Bundle",AP:AP=true)),iferror(AK2/sum(filter(AK:AK,AI:AI=AI2,B:B<>"Bundle",AP:AP=true)),0))*AJ2*Settings!$F$16)*AP2
		//GL3 Formula: sum all instances of Alt sale that match Game Title and are not Bundle
		
		GL3 Formula: 
		if([Top Bundle Name]=[Title],
			[Paid], 
			(iferror([Want]/sum(filter([Want],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)
				),
				1/COUNTA(filter([Title],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)
				)
			)*[Bundle Price]*[Settings:Want Weight])
			
			+iferror([MSRP]/sum(filter([MSRP],
				[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true))*[Bundle Price]*[Settings:MSRP Weight],0)
			
			+if(sum(iferror(filter([Hours],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)))=0
					,1/counta(filter([Title],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)),iferror([Hours]/sum(filter([Hours],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)),0))*[Bundle Price]*[Settings:Play Time Weight])*[Count Game]
		
		GL3 pseudo code:
			if [Count Game] = false
				return 0
			if [Type] = Bundle
				return paid
			
			(iferror([Want]/sum(filter([Want],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)
				),
				1/COUNTA(filter([Title],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)
				)
			)*[Bundle Price]*[Settings:Want Weight])
			
			+iferror([MSRP]/sum(filter([MSRP],
				[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true))*[Bundle Price]*[Settings:MSRP Weight],0)
			
			+if(sum(iferror(filter([Hours],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)))=0,1/counta(filter(A:A,
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)),iferror([Hours]/sum(filter([Hours],
					[Top Bundle Name]=[Top Bundle Name],
					[Type]<>"Bundle",
					[Count Game]=true)),0))*[Bundle Price]*[Settings:Play Time Weight])
		
		//GL4
		//=if(U158=A158,R158, (iferror(O158/sum(filter(O:O,U:U=U158,B:B<>"Bundle",AD:AD=true)),1/COUNTA(filter(A:A,U:U=U158,B:B<>"Bundle",AD:AD=true)))*V158*Settings!$C$18)+iferror(P158/sum(filter(P:P,U:U=U158,B:B<>"Bundle",AD:AD=true))*V158*Settings!$C$17,0)+if(sum(iferror(filter(AE:AE,U:U=U158,B:B<>"Bundle",AD:AD=true)))=0,1/counta(filter(A:A,U:U=U158,B:B<>"Bundle",AD:AD=true)),iferror(AE158/sum(filter(AE:AE,U:U=U158,B:B<>"Bundle",AD:AD=true)),0))*V158*Settings!$C$16)*AD158
		$conn=get_db_connection();
		$settings=getsettings($conn);
		$calculations=reIndexArray(getCalculations("",$conn),"Game_ID");
		$purchases=reIndexArray(getPurchases("",$conn), "TransID");
		*/

		$conn=get_db_connection();
		$settings=getsettings($conn);
		$calculations=reIndexArray(getCalculations("",$conn),"Game_ID");
		$purchases=reIndexArray(getPurchases("",$conn), "TransID");
		
		/*
		MSRP - gameMSRP
		Want - gameWantScore
		Hours - gamePlayHours
		Bundle Price - 
		Bundle Total MSRP - totalMSRP
		Price Ratio - priceRatio
		Bundle Total Want - totalWant
		Bundle Total Hours - totalHours
		Want Ratio - wantRatio
		Hours Ratio - hoursRatio
		MSRP Share - MSRPshare
		Want Share - WantShare
		Hours Share - HoursShare
		MSRP Part - MSRPpart
		Want Part
		Hours Part
		Alt Sale Price
		*/
		
		foreach ($calculations[$useproductid]["TopBundleIDs"] as $bundle) {
			$gameMSRP=$calculations[$useproductid]["MSRPPriceObj"]->getPrice();
			$gameWantScore=$calculations[$useproductid]['Want'];
			$gamePlaySeconds=$calculations[$useproductid]['totalHrs'];
			
			$gamePlayHours=$gamePlaySeconds/60/60;
			$bundlePrice=$purchases[$bundle]['Paid'];
			$totalMSRP=0;
			$totalWant=0;
			$totalSeconds=0;
			$totalSale=0;
			
			foreach ($purchases[$bundle]["GamesinBundle"] as $gamein){
				if($settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] == 1) {
					$totalMSRP+=$calculations[$gamein["GameID"]]["MSRPPriceObj"]->getPrice();
					$totalWant+=$calculations[$gamein["GameID"]]["Want"];
					$totalSeconds+=$calculations[$gamein["GameID"]]["totalHrs"];
					$totalSale+=$calculations[$gamein["GameID"]]["SalePriceObj"]->getPrice();
				}				
			}
			
			$totalHours=$totalSeconds/60/60;
			$priceRatio = $gameMSRP / $totalMSRP;
			$wantRatio = $gameWantScore / $totalWant;
			$hoursRatio = $gamePlayHours / $totalHours;
			
			$totalWeight=$settings["WeightMSRP"]+$settings["WeightPlay"]+$settings["WeightWant"];
			$MSRPweightPct=$settings["WeightMSRP"]/$totalWeight;
			$WantWeightPct=$settings["WeightWant"]/$totalWeight;
			$HoursWeightPct=$settings["WeightPlay"]/$totalWeight;
			
			$MSRPshare = $bundlePrice * $MSRPweightPct;
			$WantShare = $bundlePrice * $WantWeightPct;
			$HoursShare = $bundlePrice * $HoursWeightPct;
			$MSRPpart = $MSRPshare * $priceRatio;
			$WantPart = $WantShare * $wantRatio;
			$HoursPart = $HoursShare * $hoursRatio;
			
			$expectedAltSale = $MSRPpart + $WantPart + $HoursPart;
			
			$actualAltSale=$calculations[$useproductid]["AltPriceObj"]->getPrice();
			
			$gameinbundledata=array();
			if (isset($purchases[$bundle]['GamesinBundle'][$useproductid])) {
				$gameinbundledata=$purchases[$bundle]['GamesinBundle'][$useproductid];
				//echo $purchases[$bundle]['GamesinBundle'][$useproductid]['Debug'];
				
				$debugtext2=
				"\nAltSale: " . $actualAltSale . " = " . ($gameinbundledata['AltPrice'] + $gameinbundledata['Altwant'] + $gameinbundledata['Althrs']) . " = WantPart (".
				($gameinbundledata['Altwant']) .") + HoursPart (" . ($gameinbundledata['Althrs']) . ") + MSRPpart (" . ($gameinbundledata['AltPrice']) . ")" ."\n";
				
				//echo $debugtext2;

				$debugtext3=
				"\nexpectedAltSale: " . $expectedAltSale . " = " . ($MSRPpart + $WantPart + $HoursPart) . " = WantPart (".
				($WantPart) .") + HoursPart (" . ($HoursPart) . ") + MSRPpart (" . ($MSRPpart) . ")" ."\n";
				
				//echo $debugtext3;
				
				$this->assertEquals($expectedAltSale,$actualAltSale);
			}

			
			//$this->assertEquals($expectedAltSale,$actualAltSale);
		}
	}

/*
Testable Fields
Purchases:
	PrintPurchaseTimeStamp is the string of PurchaseDateTime (object) --should not be needed
	GamesinBundle is an array with keys equal to the IDs of the countable games in this bundle. (duplicates are not countable) --May not be needed.
	
	TotalMSRP is the total of the MSRP for all countable games in the bundle
	TotalWant is the total of the Want score for each countable game in the bundle
	TotalHrs is the total hours played for any countable game in the bundle
	TopBundleID follows the parent bundle id until a bundle is found where the parent is itself.
	TopBundle is the title from the topbundleID
	itemsinBundle is an array with keys equal to the IDs of the countable items in this bundle. (duplicates are not countable)
	ProductinBundle is an array with keys equal to the IDs of the countable games in this bundle. (duplicates are not countable)

Games:
	ParentGame is the title of the ParentGameID
	DateUdatedSort is Dateupdated formatted as Y-M-D
	All the links fields should be removed.

ActivityCalculations:
	firstPlayDateTime is the timestamp of the earliest history record that is countable (not idle)
	firstplay is a string of firstPlayDateTime --should not be needed
	lastPlayDateTime is the timestamp of the most recent history record that is countable (not idle)
	lastplay is a string of lastPlayDateTime --should not be needed
	elapsed is a total of the elapsed time from each history record
	Achievements is the Achievements in the most recent history record that is not null
	Status is the status in the most recent history record that is not null
	Review is the Review in the most recent history record that is not null
	LastBeat is the timestamp of the most recent history record which has the keword "Beat Game"
	GrandTotal ?

Calculations: 
	firstplaysort is an int of firstPlayDateTime --should not be needed
	totalHrs
	GrandTotal
	AchievementsLeft
	AchievementsPct
	Active
	CountGame
	TopBundleIDs
	FirstBundle
	AddedDateTime
	PurchaseDateTime
	BundlePrice
	Bundles
	OS
	Library
	DRM
	DaysSincePurchaseDate
	LaunchPriceObj
	MSRPPriceObj
	CurrentPriceObj
	HistoricPriceObj
	SalePriceObj
	AltPriceObj
	PaidPriceObj
*/

}