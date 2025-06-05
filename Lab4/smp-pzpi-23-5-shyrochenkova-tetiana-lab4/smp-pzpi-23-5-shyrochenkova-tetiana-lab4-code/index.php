<?php

require_once 'db/config.php';

initializeDatabase();

session_start();

$path = $_SERVER['PATH_INFO'] ?? '/';

initializeDatabase();

switch ($path) {
	case '/':
		include "./views/home.php";
		break;
	case '/goods':
		if (isset($_SESSION["username"])) {
			include "./views/goods.php";
		} else {
			include "./views/404.php";
		}
		break;
	case '/cart':
		if (isset($_SESSION["username"])) {
			include "./views/cart.php";
		} else {
			include "./views/404.php";
		}
		break;
	case '/login':
		if (!isset($_SESSION["username"])) {
			include 'views/login.php';
		} else {
			header("Location: " . "/");
		}
		break;
	case '/profile':
		if (isset($_SESSION["username"])) {
			include 'views/profile.php';
		} else {
			include "./views/404.php";
		}
		break;
	default:
		include "./views/404.php";
		break;
}
