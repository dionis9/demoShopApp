<?php
namespace Shop\Products;

use Shop\Discounts\Discount;

/**
 * Class which represents a product.
 *
 * @author Dionis Lebedev <github@dionis.me>
 * @version 0.0.0.1
 *
 * @copyright &copy; 2013, Dionis Lebedev
 */
class Product {

	/** @var string Name of the product. */
	var $name = '';
	/** @var float|int Price of the product. */
	var $price = 0.0;

	/**
	 * Set products parametars like name and price.
	 *
	 * @param string Name of the product.
	 * @param float|int Price of the product.
	 *
	 * @return Product Class which represents this product.
	 */
	public function __construct($name, $price){

		if(!is_string($name))
			return false;

		if(!is_float($price) and !is_int($price))
			return false;

		$this->name		= $name;
		$this->price	= $price;

		return $this;
	}
}