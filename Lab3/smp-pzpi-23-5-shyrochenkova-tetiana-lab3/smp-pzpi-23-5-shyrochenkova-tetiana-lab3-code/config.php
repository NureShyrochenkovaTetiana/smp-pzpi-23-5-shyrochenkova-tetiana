<?php

function getDatabase() {
	return new PDO('sqlite:./db/storage.sqlite');
}

function initializeDatabase() {
	$connection = getDatabase();

	$connection->exec("
		CREATE TABLE IF NOT EXISTS goods (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			name TEXT NOT NULL,
			price REAL NOT NULL
		)
	");

	$hasProducts = $connection->query("SELECT COUNT(*) FROM goods")->fetchColumn();

	if (!$hasProducts) {
		$connection->exec("
			INSERT INTO goods (name, price) VALUES
			('Молоко пастеризоване', 12),
    	('Хліб чорний', 9),
    	('Сир білий', 21),
    	('Сметана 20%', 25),
    	('Кефір 1%', 19),
    	('Вода газована', 25),
    	('Печиво \"Весна\"', 25);
		");
	}
}
