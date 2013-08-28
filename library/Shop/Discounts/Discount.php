<?php
namespace Shop\Discounts;

use \Shop\Products;

/**
 * Class which represents a discount.
 *
 * @author Dionis Lebedev <github@dionis.me>
 * @version 0.0.0.1
 *
 * @copyright &copy; 2013, Dionis Lebedev
 */
class Discount extends Products\Set {

	/** @var int Amount of percents the price should go down. */
	var $percent = 0;
	/** @var array Products that are excluded from discount. */
	var $exclProducts = array();
	/** @var int Minimum amount of products which should be ordered for discount to apply. */
	var $minProductNum = 1;

	var $apply = false;

	/**
	 * set a discount for this product set.
	 *
	 * @param int Amount of percents the price should go down.
	 * @param array Products that should be excluded from discount.
	 *
	 * @return Discount Class which represents a discount.
	 */
	public function setDiscount($percent = 0){

		if(!is_int($percent)){
			return false;
		}

		$this->percent		= $percent;

		return $this;
	}

	/**
	 * do not apply discount
	 *
	 * @param Product Class which represents a product.
	 *
	 * @return Set Class which represents this set.
	 */
	public function notApplyDiscountToProduct(Products\Product $product){
		$this->exclProducts[] = $product;
		return $this;
	}

	/**
	 * set a minimum amount of products which should be ordered for discount to apply.
	 *
	 * @param int Minimum amount of products which should be ordered for discount to apply.
	 *
	 * @return Discount Class which represents a discount.
	 */
	public function setMinProductNumber($minProductNum = 1){

		if(!is_int($minProductNum)){
			return false;
		}

		$this->minProductNum = $minProductNum;
		return $this;
	}

}