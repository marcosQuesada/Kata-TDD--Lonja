<?php

namespace Kata\RomanosBundle\Tests\Kata;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Kata\RomanosBundle\Entity\KataLonja;

class KataLonjaTest extends WebTestCase
{

	protected $markets = array(
		'MADRID' => array(
						'prices' => array(
							'vieiras' 	=> 500,
							'pulpo'		=> 0,
							'centollos' => 450
							),
						'distancia' 	=> 800
						),
		'BARCELONA' => array(
						'prices' => array(
							'vieiras' 	=> 450,
							'pulpo'		=> 120,
							'centollos' => 0
							),
						'distancia' 	=> 1100
						),
		'LISBON' => array(
						'prices' => array(
							'vieiras' 	=> 600,
							'pulpo'		=> 100,
							'centollos' => 500
							),
						'distancia' 	=> 600
						)	
		);

	protected $invoiceLoad = array(
					'vieiras' 	=>	50,
					'pulpo'		=>	100,
					'centollos'	=>	50
					);

	protected $lonja;

	public function setUp()  {
		$this->lonja = new KataLonja();
	}

	public function testBestMarketToSellFromExposedExampleIsLisbon()  {

		$bestMarketToSell = $this->lonja->sell($this->invoiceLoad, $this->markets);
		
		$this->assertEquals( $bestMarketToSell , 'LISBON');
	}

	public function testTotalBillOfInvoicedOrder()  {
		$invoice = array(
					'vieiras' 	=>	100,
					'pulpo'		=>	100,
					'centollos'	=>	100
					);

		$market = $this->markets['LISBON'];

		$totalBill = $this->lonja->getTotalBillOnMarket($invoice, $market);

		$this->assertEquals($totalBill, 120000);
	
	}

	public function testDeliveryCost()  {
		$deliveryData = array(
					'distance' => 200,
					'kmCost' => 2,
					'cargoFee' => 5
					);

		$total = $deliveryData['distance'] * $deliveryData['kmCost'] + $deliveryData['cargoFee'];

		$transportFee = $this->lonja
							 ->getDeliveryCost( $deliveryData );

		$this->assertEquals($transportFee , $total);

	}
	

	public function testBestMarketToSellOnlyVieirasWithDistanceIsLisbon()  {

		$deliveryData = array(
					'distance' => 600,
					'kmCost' => 2,
					'cargoFee' => 5
					);
		$invoice = array(
					'vieiras' 	=>	100,
					'pulpo'		=>	0,
					'centollos'	=>	0
					);		

		$bestMarketToSell = $this->lonja->sell($invoice,$this->markets, $deliveryData);
		
		$this->assertEquals( $bestMarketToSell , 'LISBON');
	}


	public function testBestMarketToSellOnlyVieirasIsLisbon()  {
		$invoice = array(
					'vieiras' 	=>	100,
					'pulpo'		=>	0,
					'centollos'	=>	0
					);		

		$bestMarketToSell = $this->lonja->sell($invoice, $this->markets);
		
		$this->assertEquals( $bestMarketToSell , 'LISBON');
	}

	public function testBestMarketToSellOnlyPulpoIsBarcelona()  {
		$invoice = array(
					'vieiras' 	=>	0,
					'pulpo'		=>	100,
					'centollos'	=>	0
					);		
		$bestMarketToSell = $this->lonja->sell($invoice, $this->markets);
		
		$this->assertEquals( $bestMarketToSell , 'BARCELONA');	
	}

	public function testBestMarketToSellOnlyCentollosIsLisbon()  {

		$invoice = array(
					'vieiras' 	=>	0,
					'pulpo'		=>	0,
					'centollos'	=>	100
					);
		$bestMarketToSell = $this->lonja->sell($invoice, $this->markets);
		
		$this->assertEquals( $bestMarketToSell , 'LISBON');	
	}
}	