<?php
namespace Shop\Orders;

use \Shop\Products;

/**
 * Class which represents an order with all products in it.
 *
 * @author Dionis Lebedev <github@dionis.me>
 * @version 0.0.0.1
 *
 * @copyright &copy; 2013, Dionis Lebedev
 */
class Order {

	/** @var array Products which user has added to the order */
	var $orderedItems = array();

	/**
	 * add a product to the order.
	 *
	 * @param Products\Product Product which the user wants to buy.
	 *
	 * @return Order Class which represents this order with all products in it.
	 */
	public function push(Products\Product $product){

		$this->orderedItems[] = new Item($product);

		return $this;
	}

}