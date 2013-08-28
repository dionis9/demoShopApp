<?php
namespace Shop\Orders;

use \Shop\Products;
use \Shop\Discounts;

/**
 * Class to calculate final prices.
 *
 * @author Dionis Lebedev <github@dionis.me>
 * @version 0.0.0.1
 *
 * @copyright &copy; 2013, Dionis Lebedev
 */
class Calculator {

	/** @var Order */
	private $order;

	/** @var Discounts\Manager */
	private $discountManager;

	/** @var bool Determines if price was already calculated */
	private $calculated = false;

	/**
	 * set current order
	 *
	 * @param Order
	 *
	 * @return Calculator Class to calculate final prices.
	 */
	public function setOrder(Order $order){

		$this->order = $order;

		return $this;
	}

	/**
	 * set current discount manager
	 *
	 * @param Discounts\Manager
	 *
	 * @return Calculator Class to calculate final prices.
	 */
	public function setDiscountManager(Discounts\Manager $discountManager){

		$this->discountManager = $discountManager;

		return $this;
	}

	/**
	 * do calculate final prices
	 *
	 * @return Calculator
	 */
	public function doCalculation(){
		if($this->calculated)
			return $this;
		$discountNum = count($this->discountManager->allDiscount);
		for($i=0;$i<$discountNum;$i++){
			$this->applyDiscount(
				$this->discountManager->allDiscount[$i],
				$this->discountManager->allDiscount[$i]->allProducts
			);
		}
		$this->calculated = true;
		return $this;
	}

	/**
	 * get all ordered items with calculated prices
	 *
	 * @return array $results Contacts name and finalPrice for each ordered item
	 */
	public function getOrderedItems(){
		$this->doCalculation();

		// prepare results
		$results = array();
		foreach($this->order->orderedItems as $item){
			$results[] = array(
				'name'			=> $item->name,
				'finalPrice'	=> $item->finalPrice,
			);
		}

		return $results;
	}

	/**
	 * get total price for the order
	 *
	 * @return int $result Total price of the order
	 */
	public function getTotal(){
		$this->doCalculation();

		// prepare results
		$result = 0;
		foreach($this->order->orderedItems as $item){
			$result += $item->finalPrice;
		}

		return $result;
	}

	/**
	 * apply discount to all products
	 *
	 * @param Discounts\Discount
	 * @param array List with all products
	 *
	 * @return true|false
	 */
	private function applyDiscount(Discounts\Discount $discount, array $allProducts = array()){
		if(!$discount->apply){
			$this->doesDiscountApply($discount);
			if(!$discount->apply){
				return false;
			}
		}
		foreach($allProducts as $productOrSet){
			if(in_array($productOrSet,$discount->exclProducts)){
				// excluded from discount
				continue;
			}
			if(get_class($productOrSet)=='Shop\Products\Set'){
				// its a product set
				$this->applyDiscount($discount,$productOrSet->allProducts);
			}
			else {
				// its a product
				foreach($this->order->orderedItems as $item){

					if($productOrSet->name==$item->name){
						$item->applyDiscount($discount);
						break;
					}
				}
			}
		}
		return true;
	}

	/**
	 * check if this discount should be applied to this order
	 *
	 * @param Discounts\Discount
	 *
	 * @return Discounts\Discount
	 */
	private function doesDiscountApply(Discounts\Discount $discount){
		if($discount->combinedBy == Products\Set::COMBINED_BY_OR){
			// any of the products must be in the order
			$res = $this->howManyProductsOrderedIn($discount->allProducts);
			if($res['found']<$discount->minProductNum){
				$discount->apply = false; // less then minumum products matched
			}
		}
		else {
			// all of the products must be in the order
			$res = $this->howManyProductsOrderedIn($discount->allProducts);
			if($res['notFound']>0){
				$discount->apply = false; // not all products found
			}
			else {
				$discount->apply = true;
			}
		}
	}

	/**
	 * check how many products were ordered in the 'allProducts' list
	 *
	 * @param array List of all products which should be checked
	 *
	 * @return array $foundStats Includes 'notFound' and 'found' number of products
	 */
	private function howManyProductsOrderedIn(array $allProducts = array()){
		$foundStats = array(
			'notFound'	=> 0,
			'found'		=> 0,
		);
		foreach($allProducts as $productOrSet){
			if(get_class($productOrSet)=='Shop\Products\Set'){
				// its a product set
				$tmp = $this->howManyProductsOrderedIn($productOrSet->allProducts);
				foreach($tmp as $i=>$v){
					$foundStats[$i]+=$v;
				}
			}
			else {
				// its a product
				if(!$this->isProductOrdered($productOrSet)){
					// product not ordered
					$foundStats['notFound']++;
				}
				else {
					$foundStats['found']++;
				}
			}
		}
		return $foundStats;
	}


	/**
	 * check if the product was ordered
	 *
	 * @param Products\Product
	 *
	 * @return true|false
	 */
	private function isProductOrdered(Products\Product $product){
		foreach($this->order->orderedItems as $item){
			if($item->name==$product->name){
				return true;
			}
		}
		return false;
	}

}