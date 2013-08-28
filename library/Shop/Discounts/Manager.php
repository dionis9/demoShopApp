<?php
namespace Shop\Discounts;

/**
 * Class which is used to manage discounts.
 *
 * @author Dionis Lebedev <github@dionis.me>
 * @version 0.0.0.1
 *
 * @copyright &copy; 2013, Dionis Lebedev
 */
class Manager {

	/** @var array List of all discounts */
	var $allDiscount = array();

	/**
	 * add a discount to the manager.
	 *
	 * @param Discount Discount which should be added to the manager.
	 *
	 * @return Manager Class which is used to manage discounts.
	 */
	public function push(Discount $discount){

		$this->allDiscount[] = $discount;

		return $this;
	}

}