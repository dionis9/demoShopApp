<?php
namespace Shop\Orders;

use Shop\Products\Product;
use Shop\Discounts\Discount;

/**
 * Class which represents a product.
 *
 * @author Dionis Lebedev <github@dionis.me>
 * @version 0.0.0.1
 *
 * @copyright &copy; 2013, Dionis Lebedev
 */
class Item extends Product {

	/** @var float|int Final price of the product after all discounts. */
	var $finalPrice = 0.0;

	/** @var Discount Discount which is applied to this item. */
	var $discount = NULL;

	/**
	 * Set item parametars like name, price and finalPrice.
	 *
	 * @param Product
	 *
	 * @return Item
	 */
	public function __construct(Product $product){

		$this->name			= $product->name;
		$this->price		= $product->price;
		$this->finalPrice	= $product->price;

		return $this;
	}

	/**
	 * Apply discount to this item
	 *
	 * @param Discount
	 *
	 * @return Item
	 */
	public function applyDiscount(Discount $discount){
		if($this->discount == NULL){
			$this->finalPrice = $this->price*(100-$discount->percent/100);
			$this->discount = $discount;
		}
		return $this;
	}
}