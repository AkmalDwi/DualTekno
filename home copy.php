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
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $product_quantity = intval($_POST['product_quantity']);

    $stmt = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $stmt->bind_param("si", $product_name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message[] = 'Product already added to cart!';
    } else {
        $stmt = $conn->prepare("INSERT INTO `cart` (user_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdss", $user_id, $product_name, $product_price, $product_quantity, $product_image);

        if ($stmt->execute()) {
            $message[] = 'Product added to cart!';
        } else {
            $message[] = 'Failed to add product to cart.';
        }
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
    <title>DualTekno</title>
    <link rel="icon" type="image/png" href="assets/logohexacropped.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="styles.css" rel="stylesheet">
</head>

<body>

    <?php include 'header.php'; ?>

    <header>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="assets/banner3.jpg" class="d-block w-100" alt="First slide">
                </div>
                <div class="swiper-slide">
                    <img src="assets/banner4.jpg" class="d-block w-100" alt="Second slide">
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </header>

    <section class="products">
        <?php
        function render_products($conn, $category, $title)
        {
            echo "<h1 class='title'>$title</h1>
              <div class='container px-4 px-lg-5 mt-5'>
                  <div class='row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center'>";

            $stmt = $conn->prepare("SELECT * FROM `products` WHERE category = ? LIMIT 6");
            $stmt->bind_param("s", $category);
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
                            <h5 class='fw-bolder'>Rp " . number_format($row['price'], 0, ',', '.') . "</h5>
                            <form action='' method='POST'>
                                <input type='hidden' name='product_name' value='{$row['name']}'>
                                <input type='hidden' name='product_price' value='{$row['price']}'>
                                <input type='hidden' name='product_image' value='{$row['image']}'>
                                <input type='number' name='product_quantity' value='1' min='1' class='form-control mb-3'>
                                <input type='submit' name='add_to_cart' value='Add to Cart' class='btn btn-primary'>
                            </form>
                        </div>
                    </div>
                </div>";
                }
            } else {
                echo '<p class="empty">No products available yet!</p>';
            }

            echo "</div>
                </div>
                    <div class='load-more' style='margin-top: 2rem; text-align:center'>
            <button class='option-btn'>Load More</button>
</div>
";
        }

        render_products($conn, 'Television', 'Televisions');
        render_products($conn, 'Smartphone', 'Smartphones');
        render_products($conn, 'Laptop', 'Laptops');
        ?>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="scripts.js"></script>
</body>

</html>