<?php
require_once 'store/profile.php';

global $profile;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['firstName'])) {
	try {
    $birthDate = new DateTime($_POST['dateOfBirth']);
		$today = new DateTime();

		$age = $today->diff($birthDate)->y;

    if (strlen(trim($_POST["firstName"])) == 0) {
			throw new Exception("Імʼя обовʼязкове");
		}

		if (strlen(trim($_POST['lastName'])) == 0) {
			throw new Exception("Прізвищем обовʼязкове");
		}

		if (strlen(trim($_POST["bio"])) == 0) {
			throw new Exception("Опис обовʼязковий");
		}

		if ($age < 16) {
			throw new Exception("Користувач не проходить за віковими обмеженнями");
		}

    $profile = [
      "firstName" => $_POST["firstName"],
      "lastName" => $_POST["lastName"],
      "bio" => $_POST["bio"],
      "dateOfBirth" => $_POST["dateOfBirth"],
      "avatar" => $profile["avatar"],
    ];
	} catch (Exception $e) {
		$error = $e->getMessage();
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['avatar'])) {
  $exts = ['jpg', 'jpeg', 'png'];
  $targetDir = './uploads/';

  $imageSize = getimagesize($_FILES["avatar"]["tmp_name"]);

	if (!$imageSize) {
		throw new Exception("Файл не є зображенням");
		}

	if ($_FILES["avatar"]["size"] > 2 * 1024 * 1024) {
		throw new Exception("Файл занадто великий");
	}

	$file = $targetDir . basename($_FILES["avatar"]["name"]);

	$imageFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

	if (!in_array($imageFileType, $exts)) {
		throw new Exception("Дозволені лише розширення " . implode(', ', $exts));
	}

	move_uploaded_file($_FILES["avatar"]["tmp_name"], $file);

  $profile['avatar'] = $file;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['logout_action'])) {
	unset($_SESSION["username"]);
	unset($_SESSION["authorizedAt"]);

	header("Location: /");
}
?>
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
<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md">
		<div class="flex flex-col gap-4">

			<div class="flex flex-col items-center space-y-4">
        <img src="<?= $profile['avatar'] ?? '' ?>" alt="avatar" class="w-48 h-48 rounded-2xl">
        <form id="upload_form" method="post" enctype="multipart/form-data">
          <input id="upload" type="file" name="avatar" accept="image/*" required hidden />
          <label for="upload" class="flex bg-white justify-center items-center items-center leading-none text-black h-9 px-3 min-w-[128px] rounded-lg text-sm border border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Завантажити
          </label>
        </form>
			</div>

			<form class="md:col-span-2 space-y-6" method="post">
				<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
					<div>
						<label for="first_name" class="block text-sm font-semibold mb-1">Імʼя</label>
						<input
							id="first_name"
							name="firstName"
							type="text"
							placeholder="Введіть імʼя"
							class="bg-gray-50 outline-none border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-green-500 focus:ring focus:ring-green-500 block w-full p-2.5"
							value="<?= $profile['firstName'] ?? '' ?>"
							autofocus
							required
						/>
					</div>
					<div>
						<label for="last_name" class="block text-sm font-semibold mb-1">Прізвище</label>
						<input
							id="last_name"
							name="lastName"
							type="text"
							placeholder="Введіть прізвище"
							class="bg-gray-50 outline-none border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-green-500 focus:ring focus:ring-green-500 block w-full p-2.5"
							value="<?= $profile['lastName'] ?? '' ?>"
							required
						/>
					</div>
					<div>
						<label class="block text-sm font-semibold mb-1">Дата народження</label>
						<input
							id="date_of_birth"
							name="dateOfBirth"
							type="date"
							placeholder="Введіть імʼя"
							class="bg-gray-50 outline-none border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-green-500 focus:ring focus:ring-green-500 block w-full p-2.5"
							value="<?= $profile['dateOfBirth'] ?? '' ?>"
							required
						/>
					</div>
				</div>

				<div>
					<label for="description" class="block text-sm font-semibold mb-1">Опис</label>
					<input
						id="description"
						name="bio"
						class="bg-gray-50 outline-none border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-green-500 focus:ring focus:ring-green-500 block w-full p-2.5"
						placeholder="Напишіть щось про себе..."
						value="<?= $profile['bio'] ?? '' ?>"
					>
				</div>

				<?php if (!empty($error)) : ?>
					<p class="text-red-500"><?= htmlspecialchars($error) ?></p>
				<?php endif ?>
				<div class="flex justify-end gap-3">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<button name="logout_action" class="bg-red-600 text-white h-9 px-3 min-w-[128px] rounded-lg text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ml-auto">
							Вийти
						</button>
          </form>
					<button class="bg-green-600 text-white h-9 px-3 min-w-[128px] rounded-lg text-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
						Зберегти
					</button>
				</div>
			</form>
		</div>
	</div>
</main>
<?php include "./templates/footer.php" ?>
<script>
  document.getElementById('upload').addEventListener('change', function () {
    if (this.files.length > 0) {
      document.getElementById('upload_form').submit();
    }
  });
</script>
</body>
</html>
