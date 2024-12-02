<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_brand = mysqli_real_escape_string($conn, $_POST['product_brand']);
    $product_specifications = mysqli_real_escape_string($conn, $_POST['product_specifications']);
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $product_quantity = intval($_POST['product_quantity']);

// Fetch the current stock of the product
$stmt = $conn->prepare("SELECT stock FROM `products` WHERE name = ?");
$stmt->bind_param("s", $product_name);
$stmt->execute();
$stock_result = $stmt->get_result();
$product_stock = $stock_result->fetch_assoc()['stock'] ?? 0;

if ($product_quantity > $product_stock) {
    $message[] = 'Requested quantity exceeds available stock!';
} else {
    // Proceed with adding to cart
    $stmt = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $stmt->bind_param("si", $product_name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message[] = 'Product already added to cart!';
    } else {
        $stmt = $conn->prepare("INSERT INTO `cart` (user_id, name, brand, specifications, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdss", $user_id, $product_name, $product_brand, $product_specifications, $product_price, $product_quantity, $product_image);

        if ($stmt->execute()) {
            // Deduct the quantity from stock
            $stmt = $conn->prepare("UPDATE `products` SET stock = stock - ? WHERE name = ?");
            $stmt->bind_param("is", $product_quantity, $product_name);
            $stmt->execute();

            $message[] = 'Product added to cart!';
        } else {
            $message[] = 'Failed to add product to cart.';
        }
    }
}

if ($product_quantity > $product_stock) {
    $message[] = "Only {$product_stock} units available for {$product_name}.";
}

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Televisi - DualTekno</title>
    <link rel="icon" type="image/png" href="assets/logohexacropped.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<header class="bg-primary py-3">
            <div class="container px-4 px-lg-5 my-5">
                <h1 class="text-center text-white">Televisi</h1>
            </div>
        </header>

        <section class="filter">
    <div class="container px-4 px-lg-5 mt-5">
        <form method="GET" class="mb-4">
            <select name="brand_filter" class="form-select" onchange="this.form.submit()">
                <option value="">Select Brand</option>
                <?php
                $stmt = $conn->prepare("SELECT DISTINCT brand FROM `products` WHERE category = 'Television'");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $selected = isset($_GET['brand_filter']) && $_GET['brand_filter'] == $row['brand'] ? 'selected' : '';
                    echo "<option value='{$row['brand']}' $selected>{$row['brand']}</option>";
                }
                ?>
            </select>
        </form>
    </div>
</section>
<section class="products">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

        <?php
        $brand_filter = isset($_GET['brand_filter']) ? $_GET['brand_filter'] : '';
        if ($brand_filter) {
            $stmt = $conn->prepare("SELECT * FROM `products` WHERE category = 'Television' AND brand = ? LIMIT 6");
            $stmt->bind_param("s", $brand_filter);
        } else {
            $stmt = $conn->prepare("SELECT * FROM `products` WHERE category = 'Television' LIMIT 6");
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='col mb-5'>
                    <div class='card h-100'>
                        <img class='card-img-top' src='uploaded_img/{$row['image']}' alt='Product image'>
                        <div class='card-body p-4 text-center'>
                            <h5 class='fw-bolder'>{$row['name']}</h5>
                            <h6 class='fw-bolder'>Brand: {$row['brand']}</h6>
                            <h6 class='fw-bolder'>Stock: {$row['stock']}</h6>
                            <p>{$row['specifications']}</p>
                            <h5 class='fw-bolder'>Rp " . number_format($row['price'], 0, ',', '.') . "</h5>
                            <form action='' method='POST'>
                                <input type='hidden' name='product_name' value='{$row['name']}' />
                                <input type='hidden' name='product_brand' value='{$row['brand']}' />
                                <input type='hidden' name='product_specifications' value='{$row['specifications']}' />
                                <input type='hidden' name='product_price' value='{$row['price']}' />
                                <input type='hidden' name='product_image' value='{$row['image']}' />
                                <input type='number' name='product_quantity' value='1' min='1' max='{$row['stock']}' class='form-control mb-3'>
                                <input type='submit' name='add_to_cart' value='Add to Cart' class='btn btn-primary' />
                            </form>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo '<p class="empty">No products available yet!</p>';
        }

        $stmt->close();
        ?>
        </div>
    </div>
</section>


<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="scripts.js"></script>

</body>
</html>
