<?php
/** Scan products and get the total cost.*/
include 'listing.php';
include 'invoices.php';
class Terminal {
	private $product_listing;
	private $product_invoice;

	public function __construct($product_listing) {
		$this->product_listing = $product_listing;
		$this->product_invoice = new Invoice($this->product_listing);
	}

	/** Adds the product into the system.
	*/
	public function scan($product_name) {
		if ($this->product_listing->isProductAvaliable($product_name)) {
			$this->product_invoice->add($product_name);
			return True;
		} 
		return False;
	}

	/** Clear the products that were scanned in the system.
	*/
	public function reset() {
		$this->product_invoice->clear();
	}

	/** Gets the total cost of the products scanned into the system.
	*/
	public function getTotalCost() {
		return $this->product_invoice->getTotal();
	}

	/** Sets the unit prices for the product.
	*/ 
	public function setUnitPricing($product_name, $unit_price) {
		$this->product_listing->updateUnitPrice($product_name, $unit_price);
	}

	/** Sets the volume prices for the product.
	*/
	public function setVolumePricing($product_name, $volume_prices) {
		$this->product_listing->updateVolumePrices($product_name, $volume_prices);
	}
}
?>