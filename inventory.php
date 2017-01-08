<?php
/** Including product Class , Adding and removing products
*/
include 'product.php';
class Inventory {
	private $inventory;

	public function __construct() {
		$this->inventory = array();
	}

	/** Add product to the inventory. */
	public function add($product_name, $unit_price=0.00, $vol_prices=array()) {
		try {
			$this->checkVolValid($vol_prices);//Check unit volume are numeric or not
			$this->checkUnitValid($unit_price);

			if (!$this->isInInventory($product_name))
				$this->inventory[$product_name] = new Product($product_name, $unit_price, $vol_prices);
		} catch (Exception $e) {
			echo nl2br($e->getMessage() . " for Product <b>" . $product_name. "</b>\n");
			echo nl2br("Product <b>" . $product_name . "</b> has not been added to the system\n");
		}
	}

	private function checkVolValid($vol_prices) {
		foreach($vol_prices as $key=>$value) {
			if (!is_numeric($value) || !is_numeric($key)){
				throw new Exception("Invalid volume: <b>" .$key . "," .$value . "</b>");
			}	
		}
	}

	private function checkUnitValid($unit_price) {
		if (!is_numeric($unit_price)) {
			throw new Exception("Invalid Unit Price: <b>" . $unit_price . "</b>");
		}
	}
	/**
	* Return product from the inventory.
	*/
	public function get($product_name) {
		if ($this->isInInventory($product_name))
			return $this->inventory[$product_name]; 
	}
	/**
	* Removes product from the inventory.
	*/
	public function remove($product_name) {
		unset($this->inventory[$product_name]);
	}

	/**
	* Checks product is in the inventory.
	* @return boolean
	*/
	public function isInInventory($product_name) {
		return array_key_exists($product_name, $this->inventory);
	}
}
?>