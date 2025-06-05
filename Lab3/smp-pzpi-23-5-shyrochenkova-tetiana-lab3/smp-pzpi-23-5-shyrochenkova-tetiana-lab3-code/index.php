<?php

require_once 'db/config.php';

initializeDatabase();

session_start();

$path = $_SERVER['PATH_INFO'] ?? '/';

initializeDatabase();

switch ($path) {
	case '/':
		include "./home.php";
		break;
	case '/goods':
		include "./goods.php";
		break;
	case '/cart':
		include "./cart.php";
		break;
}
