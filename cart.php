<?php

require_once 'db/actions.php';

$products = getGoods();
$cartItemsForDisplay = getCart() ?? [];
$total = getTotal($cartItemsForDisplay ?? []);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"])) {
        $productId = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : null;

        if ($productId && isset($products[$productId])) {
            unset($_SESSION["cart"][$productId]);
        } else if ($_POST["action"] === "checkout") {
            unset($_SESSION["cart"]);
        }
    }

    header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]));
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Магазин "Весна"
    </title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4">
    </script>
</head>

<body class="flex flex-col w-full h-dvh mt-auto">
<?php include "./templates/header.php" ?>
<main class="flex-1 container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">
            Наші товари
        </h1>
    </div>
    <div>
    <?php if (empty($cartItemsForDisplay)): ?>
        <div class="text-center py-12 bg-white shadow-md rounded-lg">
            <p class="text-gray-500 mb-4 text-xl">Ваш кошик пустий</p>
            <a href="/goods" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 bg-green-600 text-white hover:bg-green-700">
                Продовжити покупки
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold">Товари</h2>
                    </div>
                    <div class="p-6 pt-0">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <table class="w-full text-sm">
                                <thead class="[&_tr]:border-b [&_tr]:border-b-slate-300 border-gray-200">
                                    <tr>
                                        <th class="h-12 px-3 text-left align-middle font-semibold text-gray-600">Назва</th>
                                        <th class="h-12 px-3 text-left align-middle font-semibold text-gray-600">Ціна</th>
                                        <th class="h-12 px-3 text-left align-middle font-semibold text-gray-600">Кількість</th>
                                        <th class="h-12 px-3 text-right align-middle font-semibold text-gray-600">До сплати</th>
                                        <th class="h-12 px-3 text-center align-middle font-semibold text-gray-600">Дії</th>
                                    </tr>
                                </thead>
                                <tbody class="[&_tr:last-child]:border-0">
                                    <?php foreach ($cartItemsForDisplay as $item): ?>
                                        <tr class="border-b-slate-300 border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 align-middle font-medium text-gray-700"><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td class="p-3 align-middle text-green-600">$<?php echo number_format($item['price'], 2); ?></td>
                                            <td class="p-3 align-middle">
                                                <div class="flex items-center space-x-1">
                                                    <input
                                                        type="number"
                                                        min="0"
                                                        name="quantity_input"
                                                        value="<?php echo $item['quantity']; ?>"
                                                        class="w-16 text-center h-8 border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                                        disabled
                                                    />
                                                </div>
                                            </td>
                                            <td class="p-3 align-middle font-semibold text-right text-gray-700">$<?php echo number_format($item['total'], 2); ?></td>
                                            <td class="p-3 align-middle text-center">
                                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="inline-flex">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                    <button
                                                        type="submit"
                                                        name="action" value="remove_item"
                                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-transparent h-8 w-8 text-red-600 hover:text-red-700 hover:bg-red-50"
                                                        title="Remove item"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="p-6">
                         <h2 class="text-xl font-semibold">Підсумок замовлення</h2>
                    </div>
                    <div class="p-6 pt-0 space-y-4">
                        <hr class="border-gray-200" />
                        <div class="flex justify-between font-semibold text-lg text-gray-800">
                            <span>До сплати:</span>
                            <span class="text-green-600">$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-3">
                            <button type="submit" name="action" value="checkout" class="w-full inline-flex items-center justify-center rounded-md text-base font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-11 px-4 py-2 bg-green-600 text-white hover:bg-green-700">
                                Оформити замовлення
                            </button>
                            <a href="/" class="w-full inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-300 bg-white hover:bg-gray-100 hover:text-gray-800 h-10 px-4 py-2 text-gray-700">
                                Продовжити покупки
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include "./templates/footer.php" ?>
</body>

</html>