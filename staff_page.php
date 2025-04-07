<?php
// filepath: d:\xampp\htdocs\Jtnewtrong\staff_page.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'staff') {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

// Initialize cart and discount if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
if (!isset($_SESSION['discount'])) {
    $_SESSION['discount'] = 0;
}

$message = "";
$errors = array();
$invoiceOrder = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    // Apply discount code
    if ($action == 'apply_discount') {
        $discount_code = trim($_POST['discount_code']);
        if (strtoupper($discount_code) === 'DISCOUNT10') {
            $_SESSION['discount'] = 0.10;
            $message = "10% discount applied.";
        } else {
            $_SESSION['discount'] = 0;
            $errors[] = "Invalid discount code.";
        }
    }
    // Add product to cart
    elseif ($action == 'add_to_cart') {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        if ($quantity <= 0) {
            $errors[] = "Quantity must be at least 1.";
        } else {
            $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $product = $result->fetch_assoc();
                if ($quantity > $product['stock']) {
                    $errors[] = "Not enough stock for \"" . htmlspecialchars($product['name']) . "\".";
                } else {
                    if (isset($_SESSION['cart'][$product_id])) {
                        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                    } else {
                        $_SESSION['cart'][$product_id] = array(
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'quantity' => $quantity,
                            'stock' => $product['stock']
                        );
                    }
                    $message = "Product added to cart.";
                }
            }
            $stmt->close();
        }
    }
    // Update cart quantities
    elseif ($action == 'update_cart') {
        foreach ($_POST['quantities'] as $pid => $qty) {
            $qty = intval($qty);
            if ($qty <= 0) {
                unset($_SESSION['cart'][$pid]);
            } else {
                $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
                $stmt->bind_param("i", $pid);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $product = $result->fetch_assoc();
                    if ($qty > $product['stock']) {
                        $errors[] = "Not enough stock for product ID $pid.";
                    } else {
                        $_SESSION['cart'][$pid]['quantity'] = $qty;
                    }
                }
                $stmt->close();
            }
        }
        if (empty($errors)) {
            $message = "Cart updated.";
        }
    }
    // Remove item from cart
    elseif ($action == 'remove_item') {
        $product_id = intval($_POST['product_id']);
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $message = "Item removed from cart.";
        }
    }
    // Checkout: Process order and generate invoice
    elseif ($action == 'checkout') {
        if (empty($_SESSION['cart'])) {
            $errors[] = "Your cart is empty.";
        } else {
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            if ($_SESSION['discount'] > 0) {
                $discount_amount = $total * $_SESSION['discount'];
                $total -= $discount_amount;
            } else {
                $discount_amount = 0;
            }
            $stmt = $conn->prepare("INSERT INTO orders (total_price) VALUES (?)");
            $stmt->bind_param("d", $total);
            if ($stmt->execute()) {
                $order_id = $stmt->insert_id;
                $stmt->close();
                foreach ($_SESSION['cart'] as $pid => $item) {
                    $quantity = $item['quantity'];
                    $price = $item['price'];
                    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt2->bind_param("iiid", $order_id, $pid, $quantity, $price);
                    $stmt2->execute();
                    $stmt2->close();
                    $stmt3 = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                    $stmt3->bind_param("ii", $quantity, $pid);
                    $stmt3->execute();
                    $stmt3->close();
                }
                $_SESSION['cart'] = array();
                $message = "Order placed successfully. Order ID: " . $order_id;
                $_SESSION['last_order_id'] = $order_id;
                $invoiceOrder = $order_id;
            } else {
                $errors[] = "Error placing order: " . $stmt->error;
            }
        }
    }
}

// Retrieve staff background color from settings function (assumed to exist)
function getSettings($conn) {
    $result = $conn->query("SELECT * FROM settings WHERE id = 1 LIMIT 1");
    return $result->fetch_assoc();
}
$settings = getSettings($conn);
$background_color = $settings['staff_background_color'];

// Fetch available products from database
$productQuery = "SELECT id, name, price, category FROM products ORDER BY name ASC";
$resultProducts = $conn->query($productQuery);
$productsArray = array();
while ($row = $resultProducts->fetch_assoc()) {
    $productsArray[] = $row;
}
// Pass products to JavaScript
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS Cashier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: <?= htmlspecialchars($background_color); ?>;
            padding-top: 20px;
        }
        .product-card { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">POS Cashier</h1>
        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach($errors as $err): ?>
                        <li><?= htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="row">
            <!-- Categories Sidebar -->
            <div class="col-md-3">
                <h4>Categories</h4>
                <ul class="list-group" id="categoryList">
                    <li class="list-group-item active" data-category="all">All</li>
                    <!-- Categories will be injected by JavaScript -->
                </ul>
            </div>
            <!-- Products Cards -->
            <div class="col-md-6">
                <div class="row" id="productContainer">
                    <!-- Product cards will be injected by JavaScript -->
                </div>
            </div>
            <!-- Shopping Cart -->
            <div class="col-md-3">
                <h4>Shopping Cart</h4>
                <ul class="list-group" id="cartList">
                    <!-- Cart items will display here -->
                </ul>
                <hr>
                <h5>Total: $<span id="totalAmount">0.00</span></h5>
                <button class="btn btn-success mt-2" id="checkoutBtn">Checkout</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JS Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Products array retrieved from the database
        const products = <?= json_encode($productsArray); ?>;

        let cart = [];

        // Initialize application: render categories and products
        function init() {
            renderCategories();
            renderProducts('all');
        }

        // Render unique list of categories
        function renderCategories() {
            const categoryList = document.getElementById('categoryList');
            const categories = [...new Set(products.map(p => p.category))];
            categories.forEach(cat => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerText = cat;
                li.dataset.category = cat;
                li.onclick = () => {
                    // Remove active class from all siblings
                    Array.from(categoryList.children).forEach(el => el.classList.remove('active'));
                    li.classList.add('active');
                    renderProducts(cat);
                };
                categoryList.appendChild(li);
            });
        }

        // Render product cards by selected category
        function renderProducts(category) {
            const container = document.getElementById('productContainer');
            container.innerHTML = '';
            const filteredProducts = (category === 'all') ? products : products.filter(p => p.category === category);
            filteredProducts.forEach(product => {
                const col = document.createElement('div');
                col.className = 'col-md-12 product-card';
                col.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">$${parseFloat(product.price).toFixed(2)}</p>
                            <button class="btn btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        // Add selected product to the cart (in JS, then later submitted via form)
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            const existing = cart.find(item => item.id === productId);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            renderCart();
        }

        // Render shopping cart UI
        function renderCart() {
            const cartList = document.getElementById('cartList');
            cartList.innerHTML = '';
            let total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `${item.name} x ${item.quantity} <span>$${(item.price * item.quantity).toFixed(2)}</span>`;
                cartList.appendChild(li);
            });
            document.getElementById('totalAmount').innerText = total.toFixed(2);
        }

        // Simple checkout handler (you may later integrate with your PHP checkout flow)
        document.getElementById('checkoutBtn').addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }
            alert('Proceeding to payment...');
            // Additional payment functionality can be added here
        });

        window.onload = init;
    </script>
</body>
</html>
