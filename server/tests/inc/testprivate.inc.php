<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

abstract class testprivate extends TestCase
{
	/**
 	 * getPrivateProperty
 	 *
 	 * @author	Joe Sexton <joe@webtipblog.com>
 	 * @param 	string $className
 	 * @param 	string $propertyName
 	 * @return	ReflectionProperty
	 * Source: https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
 	 */
	protected function getPrivateProperty( $className, $propertyName ) {
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
	protected function getPrivateMethod( $className, $methodName ) {
		$reflector = new ReflectionClass( $className );
		$method = $reflector->getMethod( $methodName );
		$method->setAccessible( true );

		return $method;
	}
	
	protected function getTestDataSet()
	{
		$purchases[0] = array(
				"Title" => "FirstTitle",
				"TransID" => 1,
				"BundleID" => 1,
				"PurchaseDate" => 1,
				"PurchaseTime" => 1,
				"Sequence" => 1,
				"Paid" => 1,
				"Store" => "Steam",
				"ProductsinBunde" => array(
					"0", "1"
				)
			);
		$purchases[1] = $purchases[0];
		$purchases[1]["Title"] = "SecondTitle";
		$purchases[1]["TransID"] = 2;
		$purchases[1]["BundleID"] = 2;

		$calculations[0]=array(
				"Title" => "FirstGame",
				"Series" => "FirstSeries",
				"AltSalePrice" => 1,
				"Game_ID" => 0,
				"CountGame" => 1,
				"Playable" => true,
				"Active" => true,
				"LaunchPrice" => 1,
				"MSRP" => 1,
				"HistoricLow" => 1,
				"GrandTotal" => 1,
				"Want" => 1,
				"Paid" => 1,
				"PurchaseDateTime" => new DateTime(),
				"SteamRating" => 1,
				"LaunchDate" => new DateTime(),
				"Status" => "Active",
				"Library" => array("Steam","GOG")
		);
		$calculations[1] = $calculations[0];
		$calculations[1]["Game_ID"] = 1;
		$calculations[1]["GrandTotal"] = 0;
		$calculations[1]["Active"] = false;
		$calculations[1]["Series"] = "FirstSeries";

		$calculations[2] = $calculations[0];
		$calculations[2]["Game_ID"] = 2;
		$calculations[2]["GrandTotal"] = 2;
		$calculations[2]["CountGame"] = true;
		
		$data = new dataSet(purchases: $purchases, calculations: $calculations);
		return $data;
	}
	
}