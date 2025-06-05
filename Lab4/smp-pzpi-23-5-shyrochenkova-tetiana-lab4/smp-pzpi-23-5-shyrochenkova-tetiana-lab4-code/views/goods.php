<?php

require_once 'db/actions.php';

$products = getGoods();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["add_to_cart_product_id"])) {
        $productId = (int)$_POST["add_to_cart_product_id"];
        $quantity = (int)$_POST["quantity"][$productId] ?? 0;

        if (isset($products[$productId])) {
            if ($quantity > 0) {
                $_SESSION["cart"][$productId] = $quantity;
            }
        }
    }
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
		<?php if (!empty($products)): ?>
          <form method="POST">
              <table class="w-full text-sm">
                  <thead class="[&_tr]:border-b [&_tr]:border-b-slate-300">
                  <tr>
                      <th class="h-10 px-2 text-left align-middle font-semibold text-muted-foreground">
                          Назва
                      </th>
                      <th class="h-10 px-2 text-left align-middle font-semibold text-muted-foreground">
                          Ціна
                      </th>
                      <th class="h-10 px-2 text-left align-middle font-semibold text-muted-foreground w-32">
                          Кількість
                      </th>
                      <th class="h-10 px-2 text-left align-middle font-semibold text-muted-foreground text-right">
                          Дії
                      </th>
                  </tr>
                  </thead>
                  <tbody class="[&_tr:last-child]:border-0">
					<?php foreach ($products as $product): ?>
                      <tr class="border-b border-b-slate-300 transition-colors hover:bg-gray-50 data-[state=selected]:bg-muted">
                          <td class="p-2 align-middle font-medium">
                            <?php echo htmlspecialchars($product['name']); ?>
                          </td>
                          <td class="p-2 align-middle font-semibold text-green-600">
                              $<?php echo number_format($product['price'], 2); ?>
                          </td>
                          <td class="p-2 align-middle">
                              <input type="number" min="0" placeholder="0" name="quantity[<?php echo $product['id']; ?>]" value="0" max="100" class="flex h-9 w-20 rounded-md border border-slate-300 border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                          </td>
                          <td class="p-2 align-middle text-right">
                              <button type="submit" name="add_to_cart_product_id" value="<?php echo $product['id']; ?>" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 px-3 text-white bg-green-600 hover:bg-green-700">
                                  У кошик
                              </button>
                          </td>
                      </tr>
					<?php endforeach; ?>
                  </tbody>
              </table>
          </form>
		<?php endif; ?>
    </div>
</main>
<?php include './templates/footer.php' ?>
</body>
</html>
