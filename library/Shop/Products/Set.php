<?php
namespace Shop\Products;

/**
 * Class which represents a product set.
 *
 * @author Dionis Lebedev <github@dionis.me>
 * @version 0.0.0.1
 *
 * @copyright &copy; 2013, Dionis Lebedev
 */
class Set {

	/** @var string String of AND conjunction. */
	const COMBINED_BY_AND	= 'AND';
	/** @var string String of OR conjunction. */
	const COMBINED_BY_OR	= 'OR';

	/** @var array List of all products in this set. */
	var $allProducts = array();
	/** @var string Conjunction used to join products within this set. */
	var $combinedBy = self::COMBINED_BY_AND;

	/**
	 * Adds a product to product set.
	 *
	 * @param Product Class which represents a product.
	 *
	 * @return Set Class which represents this set.
	 */
	public function addProduct(Product $product){
		$this->allProducts[] = $product;
		return $this;
	}

	/**
	 * Adds a product set to product set.
	 *
	 * @param Set Class which represents a set.
	 *
	 * @return Set Class which represents this set.
	 */
	public function addProductsSet(Set $productsSet){
		$this->allProducts[] = $productsSet;
		return $this;
	}

	/**
	 * Set what conjunction should be used to join products within this set.
	 *
	 * @param string Conjunction which should be used to join products within this set.
	 *
	 * @return Set Class which represents this set.
	 */
	public function combineBy($combinedBy = self::COMBINED_BY_AND){

		if($combinedBy!=self::COMBINED_BY_AND and $combinedBy!=self::COMBINED_BY_OR)
			return false;

		$this->combinedBy = $combinedBy;
		return $this;
	}
}