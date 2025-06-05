<?php

function getGoods() {
	$connection = getDatabase();

	return $connection->query("SELECT * FROM goods")->fetchAll(PDO::FETCH_ASSOC);
}

function getCart() {
	$products = getGoods();
	$cartItems = [];

	foreach ($products as $product) {
		if (isset($_SESSION['cart'][$product['id']])) {
			$item = [
				'id' => $product['id'],
				'name' => $product['name'],
				'price' => $product['price'],
				'quantity' => $_SESSION['cart'][$product['id']],
				'total' => $product['price'] * $_SESSION['cart'][$product['id']]
			];

			$cartItems[] = $item;
		}
	}

	return $cartItems;
}

function getTotal($cart) {
	$total = 0;

	foreach ($cart as $item) {
		$total += $item['total'];
	}

	return $total;
}
