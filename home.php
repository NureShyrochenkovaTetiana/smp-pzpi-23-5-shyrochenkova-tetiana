<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Магазин "Весна"</title>
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col w-full h-dvh mt-auto">
<?php include "./templates/header.php" ?>
<main class="flex flex-col size-full bg-slate-50 items-center justify-center gap-6">
	<?php if (isset($_SESSION["username"])) : ?>
		<h1 class="scroll-m-20 text-2xl font-semibold tracking-tight">Вітаємо, <?php echo $_SESSION["username"] ?>!</h1>
	<?php else : ?>
		<a href="/login" class="flex items-center justify-center min-w-32 h-9 rounded-md text-base text-white bg-green-600 hover:bg-green-700 px-2 gap-2">
            Увійти в акаунт
        </a>
    <?php endif; ?>
</main>
<?php include "./templates/footer.php" ?>
</body>
</html>
