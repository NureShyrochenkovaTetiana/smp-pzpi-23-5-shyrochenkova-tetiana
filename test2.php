<?php

define('SCRIPT_NAME', basename($_SERVER['PHP_SELF']));
define('CACHE_FILE', './grocery_store_prices');

class Basket {
    private $items = [];

    public function addProduct($productName, $quantity) {
        if ($quantity == 0) {
            unset($this->items[$productName]);
        } else {
            $this->items[$productName] = $quantity;
        }
    }

    public function getItems() {
        return $this->items;
    }
}

class Checkout {
    private $prices;

    public function __construct($prices) {
        $this->prices = $prices;
    }

    public function calculateProductTotal($productName, $quantity) {
        if (isset($this->prices[$productName])) {
            return $this->prices[$productName] * $quantity;
        } else {
            return 0;
        }
    }

    public function calculateTotal($basket) {
        $total = 0;
        foreach ($basket->getItems() as $product => $quantity) {
            $total += $this->calculateProductTotal($product, $quantity);
        }
        return $total;
    }
}

function parsePricesFromFile($filename) {
    $file = @fopen($filename, 'r');
    if (!$file) {
        echo SCRIPT_NAME . ": Error opening file: $filename\n";
        exit();
    }

    $prices = [];
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        $parts = explode(':', $line);
        if (count($parts) == 2) {
            $productName = trim($parts[0]);
            $price = (float)trim($parts[1]);
            $prices[$productName] = $price;
        }
    }

    fclose($file);
    return $prices;
}

$basket = new Basket();
$prices = parsePricesFromFile(CACHE_FILE);
$checkout = new Checkout($prices);

while (true) {
    echo "\033[2J\033[H";
 
    echo "###########################\n";
    echo "# GROCERY STORE \"VESNA\" #\n";
    echo "###########################\n\n";
    echo "1 Select products\n";
    echo "2 Get total amount\n";
    echo "3 Set up your profile\n";
    echo "0 Exit the program\n\n";

    echo "Enter command: ";
    $input = trim(fgets(STDIN)); 
    
    if ($input == '1') {
        while (true) {
            echo "\033[2J\033[H";
 
            echo "№  NAME                    PRICE\n";
            echo "-------------------------------------------------\n";

            $index = 1;
            $productNames = [];
            foreach ($prices as $product => $price) {
                echo $index++ . " " . str_pad($product, 22) . " " . str_pad($price, 6) . "\n";
                $productNames[] = $product;
            }

            echo "   -----------\n";
            echo "0  RETURN\n";
            echo "Select a product: ";
            $productNumber = trim(fgets(STDIN));

            if ($productNumber == '0') {
                break;
            } elseif (is_numeric($productNumber) && $productNumber > 0 && $productNumber <= count($productNames)) {
                $productName = $productNames[$productNumber - 1];
                echo "You selected: " . $productName . "\n";

                echo "Enter quantity: ";
                $quantity = trim(fgets(STDIN));
                $quantity = (int)$quantity;
                if ($quantity > 100) {
                    echo "Sorry, you can only buy less than 100 items.\n";
                    sleep(1);
                } else {

                    if (is_numeric($quantity) && $quantity > 0) {
                        $basket->addProduct($productName, $quantity);
                        echo "Product added to your basket: $quantity x $productName.\n";
                        sleep(1);
                    } else {
                        echo "Invalid quantity. Please enter a valid number greater than 0.\n";
                        sleep(1);
                    }
                }
            } else {
                echo "Invalid product number. Please try again.\n";
                sleep(1);
            }
        }

    } elseif ($input == '2') {
        echo "\033[2J\033[H";

        echo "№  NAME                    PRICE  QUANTITY  TOTAL\n";
        echo "-------------------------------------------------\n";

        $index = 1;
        foreach ($basket->getItems() as $product => $quantity) {
            $total = $checkout->calculateProductTotal($product, $quantity);
            $price = isset($prices[$product]) ? $prices[$product] : 0;
            echo $index++ . " " . str_pad($product, 22) . " " . str_pad($price, 6) . " " . str_pad($quantity, 8) . " " . str_pad($total, 6) . "\n";
        }

        $totalAmount = $checkout->calculateTotal($basket);
        echo "-------------------------------------------------\n";
        echo "TOTAL TO PAY: " . $totalAmount . "\n";
     
        exit();
    } elseif ($input == '3') {
        while (true) {
            echo "\033[2J\033[H"; 

            echo "Your name: ";
            $name = trim(fgets(STDIN));
    
            if (empty($name)) {
                echo "The name cannot be empty.\n";
                sleep(1);
            } elseif (!preg_match("/[a-zA-Z]/", $name)) {
                echo "The name must contain at least one letter.\n";
                sleep(1);
            } else {
                break;
            }
        }
    
        while (true) {
            echo "\033[2J\033[H";

            echo "Your age: ";
            $age = trim(fgets(STDIN));
    
            if (!is_numeric($age)) {
                echo "Age must be a number.\n";
                sleep(1);
            } elseif ($age < 7 || $age > 150) {
                echo "The user must be at least 7 years old and no older than 150 years.\n";
                sleep(1);
            } else {
                break;
            }
        }
    } elseif ($input == '0') {
        echo "Have a nice day!\n";
        exit();
    } else {
        echo SCRIPT_NAME . ": Invalid command. Please try again.\n";
        sleep(1);
    }
}


