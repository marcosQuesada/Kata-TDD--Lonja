<?php

namespace Kata\RomanosBundle\Entity;

class KataLonja  {

	public function sell($invoice, $markets, $deliveryData = null) {		
		$bestPrice = 0;
		$bestMarket ='';

		foreach ($markets as $place=>$market) {
			$price = $this->getTotalBillOnMarket($invoice, $market);

			if(isset($distance))
				$price -= $this->getDeliveryCost($invoice );

			if($price > $bestPrice) {
				$bestPrice = $price;
				$bestMarket = $place;
			}
		}			

		return $bestMarket;
	}

	public function getTotalBillOnMarket($deliveryLoad, $market)  {
		$total = 0;
		$prices = $market['prices'];
		foreach($prices AS $key=>$value)  {
			if(isset($deliveryLoad[$key]))  {
				$total += $deliveryLoad[$key] * $value;
			}
		}
		return $total;
	}

	public static function getDeliveryCost($deliveryData)  {
		return $deliveryData['distance'] * $deliveryData['kmCost'] + $deliveryData['cargoFee'];
	}
}	