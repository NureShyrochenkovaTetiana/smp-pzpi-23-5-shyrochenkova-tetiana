<?php
require_once "./store/credentials.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  global $credentials;

  if ($_POST["username"] != $credentials["username"] || $_POST["password"] != $credentials["password"]) {
    $error_message = "Невірний логін або пароль";
  } else {
    $_SESSION["username"] = $credentials["username"];
	  $_SESSION["authorizedAt"] = time();

	  header("Location: " . "/");
  }
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Логін</title>
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
	<main class="flex w-full h-dvh justify-center items-center">
		<section class="flex flex-col w-fit h-auto gap-4 border border-slate-300 rounded-lg p-10">
			<h1 class="scroll-m-20 text-2xl font-semibold tracking-tight text-center">Логін</h1>
			<form class="flex flex-col w-[300px] gap-4" method="post">
				<label for="username">
					Імʼя користувача
					<input
						id="username"
						name="username"
						type="text"
						placeholder="Введіть імʼя користувача"
						class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-green-500 focus:border-green-500 focus:ring focus:ring-green-500 block w-full p-2.5 outline-none"
						autofocus
						required
					/>
				</label>
				<label for="password">
					Пароль
					<input
						id="password"
						name="password"
						type="password"
						placeholder="Введіть пароль"
						class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-green-500 focus:border-green-500 focus:ring focus:ring-green-500 block w-full p-2.5 outline-none"
						required
					/>
				</label>
        
        <p class="text-red-500"><?php echo $error_message; ?></p>

				<button class="w-full bg-green-600 text-white h-9 px-3 min-w-32 rounded-lg text-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700">Увійти</button>
				<a href="/" class="text-sm text-center text-green-600 underline decoration-2">Перейти на головну</a>
			</form>
		</section>
	</main>
</body>
</html>
