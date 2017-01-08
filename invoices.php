<?php
/** An invoice system that tracks products and calculates the total cost of all them based on a product listing. 	
*/
class Invoice {
	private $invoice;
	private $product_listing;

	public function __construct($product_listing) {
		$this->invoice = array();
		$this->product_listing = $product_listing;
	}

	/** Adds the product to the invoice.
	*/
	public function add($product_name) {
		if ($this->isInInvoice($product_name))
			$this->invoice[$product_name]++;
		else
			$this->invoice[$product_name] = 1;
	}
	
	/** Removes all products in the invoice.
	*/
	public function clear() {
		$this->invoice = array();
	}
	/** Calculates the total cost of all products tracked by the invoice.
	*/
	public function getTotal() {
		$total = 0;
		foreach ($this->invoice as $product_name => $product_count) {
			$total += $this->calculate($product_name, $product_count);
		}
		return $total;
	}

	/** Gets the number of products tracked by the invoice.
	*/
	public function getCount() {
		return count($this->invoice);
	}

	/** Checks if the product is tracked by the invoice.
	*/
	public function isInInvoice($product_name) {
		return array_key_exists($product_name, $this->invoice);
	}

	/** calculate the total cost of the product
	*/
	private function calculate($product_name, $product_count) {
		$vol_p_total = 0.00;
		$remainder = $product_count;

		if ($this->product_listing->hasVolumePrices($product_name)) {
			// get volume price
			$product_volume = $this->product_listing->getVolume($product_name);
			$volume_calculation = $this->calculateVolumePrice($product_count, $product_volume);
			$vol_p_total = $volume_calculation[0];
			$remainder = $volume_calculation[1];
		}

		// get unit prices
		$product_unit_price = $this->product_listing->getUnitPrice($product_name);
		$unit_price_total = $this->calculateUnitPrice($remainder, $product_unit_price);
		
		return $vol_p_total + $unit_price_total;
	}

	/** Calculates volume price.
	*/
	private function calculateVolumePrice($product_count, $product_volume) {
		$volume_counts = array_keys($product_volume);

		$closest_volume = $this->findClosestVolume($product_count, $volume_counts);
		$total = 0.00;

		while ($closest_volume != -1 && $product_count != 0) {
			if ($closest_volume > $product_count) {
				$closest_volume = $this->findClosestVolume($product_count, $volume_counts);
			} else if (($product_count - $closest_volume) >= 0) {
				$product_count = $product_count - $closest_volume;
				$total += $product_volume[$closest_volume];
			}
		}

		return array($total, $product_count); 
	}	

	private function calculateUnitPrice($product_count, $unit_price) {
		return $product_count * $unit_price;
	}

	private function findClosestVolume($total, $volumes) {
		$smallest_diff = $total;
		$smallest_vol = $volumes[0];

		foreach($volumes as $volume) {
			$diff = abs($total - $volume);

			if ($diff < $smallest_diff && $total >= $volume) {
				$smallest_diff = $diff;
				$smallest_vol = $volume;
			}
		}

		if ($smallest_vol > $total) // not found
			return -1;

		return $smallest_vol;
	}
}
?>