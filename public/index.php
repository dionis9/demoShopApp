<pre>
<?php
/*
 * DEMO Application
 *
 * There products and discounts. The application should check
 * when to apply which discount and calculate final price.
 *
 * Example of discounts:
 * 1. If user orders product A and B, he gets 10% discount on both products
 * 4. If user orders product A and one of the products K, L or M, he gets 5% discount on product A
 * 7. If user orders 5 products, except A or C, he gets 20% discount.
 *
 * One product can have only one discount.
 *
 */

/** Define few constants */
define('DS',	DIRECTORY_SEPARATOR);
define('ROOT',	dirname(dirname(__FILE__)));
define('DEBUG',	true);


/**
 * Autoload any required class
 */
function __autoload($className){
	$file = ROOT.DS.'library'.DS.str_replace('\\',DS,$className).'.php';
	if(file_exists($file)){
		require_once($file);
	}
	else {
		throw new Exception("Couldn't load class: ".$className);
	}
}

/** Run a demo application */

/** create products */
$productA = new \Shop\Products\Product('A', 10000);
$productB = new \Shop\Products\Product('B', 5000);
$productC = new \Shop\Products\Product('C', 2500);
$productD = new \Shop\Products\Product('D', 1250);
$productE = new \Shop\Products\Product('E', 625);
$productF = new \Shop\Products\Product('F', 312.5);
$productG = new \Shop\Products\Product('G', 156.25);
$productH = new \Shop\Products\Product('H', 150);
$productI = new \Shop\Products\Product('I', 100);
$productJ = new \Shop\Products\Product('J', 75);
$productK = new \Shop\Products\Product('K', 50);
$productL = new \Shop\Products\Product('L', 25);
$productM = new \Shop\Products\Product('M', 10);

/** create product sets */
$productSetKLM  = new \Shop\Products\Set();
$productSetKLM
	->addProduct($productK)
	->addProduct($productL)
	->addProduct($productM)
	->combineBy(\Shop\Products\Set::COMBINED_BY_OR);

/** create product discounts */

$discount1  = new \Shop\Discounts\Discount();
$discount1
	->addProduct($productA)
	->addProduct($productB)
	->setDiscount(10);

$discount2  = new \Shop\Discounts\Discount();
$discount2
	->addProduct($productD)
	->addProduct($productE)
	->setDiscount(5);

$discount3  = new \Shop\Discounts\Discount();
$discount3
	->addProduct($productE)
	->addProduct($productF)
	->addProduct($productG)
	->setDiscount(5);

$discount4 = new \Shop\Discounts\Discount();
$discount4
	->addProduct($productA)
	->addProductsSet($productSetKLM)
	->notApplyDiscountToProduct($productK)
	->notApplyDiscountToProduct($productL)
	->notApplyDiscountToProduct($productM)
	->setDiscount(5);

$discount5 = new \Shop\Discounts\Discount();
$discount5
	->addProduct($productB)
	->addProduct($productD)
	->addProduct($productE)
	->addProduct($productF)
	->addProduct($productG)
	->addProduct($productH)
	->addProduct($productI)
	->addProduct($productJ)
	->addProduct($productK)
	->addProduct($productL)
	->addProduct($productM)
	->combineBy(\Shop\Products\Set::COMBINED_BY_OR)
	->setMinProductNumber(3)
	->setDiscount(5);

$discount6 = new \Shop\Discounts\Discount();
$discount6
	->addProduct($productB)
	->addProduct($productD)
	->addProduct($productE)
	->addProduct($productF)
	->addProduct($productG)
	->addProduct($productH)
	->addProduct($productI)
	->addProduct($productJ)
	->addProduct($productK)
	->addProduct($productL)
	->addProduct($productM)
	->combineBy(\Shop\Products\Set::COMBINED_BY_OR)
	->setMinProductNumber(4)
	->setDiscount(10);

$discount7 = new \Shop\Discounts\Discount();
$discount7
	->addProduct($productB)
	->addProduct($productD)
	->addProduct($productE)
	->addProduct($productF)
	->addProduct($productG)
	->addProduct($productH)
	->addProduct($productI)
	->addProduct($productJ)
	->addProduct($productK)
	->addProduct($productL)
	->addProduct($productM)
	->combineBy(\Shop\Products\Set::COMBINED_BY_OR)
	->setMinProductNumber(5)
	->setDiscount(20);

/** place an order */

$productOrder = new \Shop\Orders\Order();
$productOrder
	->push($productA)
	->push($productK)
	->push($productL)
	->push($productM);

/** add discounts to the discount manager */

$discountManager = new \Shop\Discounts\Manager();
$discountManager
	->push($discount1)
	->push($discount2)
	->push($discount3)
	->push($discount4)
	->push($discount7)
	->push($discount6)
	->push($discount5);

/** calculate total price of the order */

$calculator = new \Shop\Orders\Calculator();
$calculator
	->setOrder($productOrder)
	->setDiscountManager($discountManager);


echo "TOTAL PRICE = ".$calculator->getTotal()."\n\n";

echo "getOrderedItems: ".print_r($calculator->getOrderedItems(),1);

//print_r($calculator); // could be interesting for you
?>
</pre>