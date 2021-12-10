<?php
class PriceCalculation {
    private $price; 	//Currency

	private $MSRP;
	private $HoursPlayed;
	private $HoursToBeat;

    public function __construct($price, $HoursPlayed, $HoursToBeat=null, $MSRP=null )
    {
        $this->price = $price;
        $this->MSRP = $MSRP;
        $this->HoursPlayed = $HoursPlayed;
        $this->HoursToBeat = $HoursToBeat;
    }

    public function getPrice($printformat=false)
	{return $printformat ? $this->printCurrencyFormat($this->price) : $this->price;}
	
    public function getVarianceFromMSRP($printformat=false)
    {
		$output=$this->getVariance($this->price ,$this->MSRP);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}
	
    public function getVarianceFromMSRPpct($printformat=false)
    {
		$output=$this->getVariancePct($this->price ,$this->MSRP);
		return $printformat ?  $this->printPercentFormat($output) : $output;
	}
	
    public function getPricePerHourOfTimeToBeat($printformat=false)
    {
		$output=$this->getPriceperhour($this->price, $this->HoursToBeat*60*60);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}
	
    public function getPricePerHourOfTimePlayed($printformat=false)
    {
		$output=$this->getPriceperhour($this->price, $this->HoursPlayed);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}
	
    public function getPricePerHourOfTimePlayedReducedAfter1Hour($printformat=false)
    {
		$output=$this->getLessXhour($this->price, $this->HoursPlayed,1);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}

    public function getHoursTo01LessPerHour($printformat=false)
    {
		$output=$this->getHourstoXless($this->price, $this->HoursPlayed,0.01);
		return $printformat ? $this->printDurationFormat($output) : $output;
	}
	
    public function getHoursToDollarPerHour($target,$printformat=false)
    {
		$output=$this->getHrsToTarget($this->price, $this->HoursPlayed  ,$target);
		return $printformat ? $this->printDurationFormat($output) : $output;
	}
	
	private function getVariance($price,$msrp) {
		$variance=0;
		if($msrp<>0){
			$variance=$price-$msrp;
		}
		return $variance;
	}

	private function getVariancePct($price,$msrp) {
		$variance=0;
		if($msrp<>0){
			$variance=(1-($price/$msrp))*100;
		}
		return $variance;
	}
	
	private function getPriceperhour($price,$seconds){
		$hours=$seconds/60/60;
		if($hours<1){
			$priceperhour=$price;
		} else {
			$priceperhour=$price/$hours;
		}
		return $priceperhour;
	}

	private function getLessXhour($price,$seconds,$xhour=1){
		$hours=$seconds/60/60;
		$priceperhour=$this->getPriceperhour($price,$seconds);
		
		if($xhour+$hours==0) {
			$LessXhour=0;
		} else {
			$LessXhour=$priceperhour-($price/(max($xhour,$hours)+$xhour));
		}
		
		return $LessXhour;
	}
	
	private function getHourstoXless($price,$seconds,$xless=.01){
		$priceperhour=$this->getPriceperhour($price,$seconds);
		$hoursxless=$this->getHrsToTarget($price,$seconds,$priceperhour-$xless);
		
		return $hoursxless;
	}

	private function getHrsToTarget($CalcValue,$seconds,$target){
		$hours=$seconds/60/60;
		if($target>0){
			$hourstotarget= $CalcValue/$target-$hours;
		} else {
			$hourstotarget=0;
		}
		
		return $hourstotarget;
	}
	
	private function printCurrencyFormat($price) 
	{return sprintf("$%.2f", $price);}
	private function printPercentFormat($price) 
	{return sprintf("%.2f%%", $price);}
	private function printDurationFormat($hours) 
	{return timeduration($hours,"hours");}
	
}