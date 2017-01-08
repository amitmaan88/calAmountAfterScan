<!DOCTYPE html>
<html>
<head>
  <title>Incedo</title>
 
</head>
<body>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="target" >
	<label >Please enter your data in (ABCDABAA ,CCCCCCC, ABCD) format to test : </label> 
	<input type="text" name="product_txt" />
	<button type="submit" name="submit">Submit</button>
</form>

<div class="result">
	<?php 
		include 'scan.php';
		include 'inventory.php';
		$product_inventory = new Inventory();
		// Adding Given Product and pricing details 
		$product_inventory->add("A", 2.00, [4=>7.00]);
		$product_inventory->add("B", 12.00);
		$product_inventory->add("C", 1.25, [6=>6.00]);
		$product_inventory->add("D", 0.15);
		$product_listing = new Listing($product_inventory);//Listing class exist in listing.php, this file include in scan.php 
		$terminal = new Terminal($product_listing);//Terminal class exist in scan.php
		// Processing form data
		if (isset($_POST["submit"])) {
			$items = $_POST["product_txt"];
			for ($i = 0; $i < strlen($items); $i++) {
				if ($items[$i] != " "){
					$scannable = $terminal->scan($items[$i]);
				}	
				// Check whether data scaned or not
				if (!$scannable){
					echo "Unable to get price for: " . $items[$i] . "<br>";
				}
			}
			// Display Result
			echo '<p><b> Result :-</b></p>';
			echo '<p>Input Data : <b>'.$items.'</b></p>';
			echo '<p> Total Amout : <b>$'.number_format($terminal->getTotalCost(), 2, '.', ',').'</b></p>';
		}
	?>
</div>
</body>
</html>