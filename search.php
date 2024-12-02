<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit;
}

$query = $_GET['query'] ?? '';

$query = mysqli_real_escape_string($conn, $query);

$sql = "SELECT * FROM `products` WHERE name LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$search_term = '%' . $query . '%';
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - DualTekno</title>
    <link rel="icon" type="image/png" href="assets/logohexacropped.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>
<br>

<header>
    <h1 class="title">Search Results for: "<?php echo htmlspecialchars($query); ?>"</h1>
</header>

<section class="products">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='col mb-5'>
                    <div class='card h-100'>
                         <img class='card-img-top' src='uploaded_img/{$row['image']}' alt='Product image'>
                        <div class='card-body p-4 text-center'>
                            <h5 class='fw-bolder'>{$row['name']}</h5>
                            <h6 class='fw-bolder'>Brand: {$row['brand']}</h6>
                            <p>{$row['specifications']}</p>
                            <h5 class='fw-bolder'>Rp " . number_format($row['price'], 0, ',', '.') . "</h5>
                            <form action='' method='POST'>
                                <input type='hidden' name='product_name' value='{$row['name']}'>
                                <input type='hidden' name='product_brand' value='{$row['brand']}'>
                                <input type='hidden' name='product_specifications' value='{$row['specifications']}'>
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
                echo '<p class="empty">No products found for your search!</p>';
            }
            ?>
        </div>
    </div>
</section>
<br>
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="scripts.js"></script>
</body>
</html>
