<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['order_btn'])) {

    $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
    $number = htmlspecialchars($_POST['number']);
    $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
    $method = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['method']));
    $address = htmlspecialchars(
      mysqli_real_escape_string(
          $conn,
          $_POST['flat'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] .', '. $_POST['province'] . ' - ' . $_POST['pin_code']
      )
   );
  
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
            $sub_total = $cart_item['price'] * $cart_item['quantity'];
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query(
        $conn,
        "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'"
    ) or die('Query failed');

    if ($cart_total == 0) {
        $message[] = 'Your cart is empty.';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order already placed!';
        } else {
            mysqli_query(
                $conn,
                "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')"
            ) or die('Query failed');
            $message[] = 'Order placed successfully!';
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - DualTekno</title>
    <link rel="icon" type="image/png" href="assets/logohexacropped.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container py-4">
        <div class="text-center mb-4">
            <h3>Checkout</h3>
        </div>

        <section class="mb-5">
            <div class="card shadow p-4">
                <h5 class="card-title">Your Order</h5>
                <div class="card-body">
                    <?php  
                    $grand_total = 0;
                    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                            $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                            $grand_total += $total_price;
                    ?>
                            <p class="mb-2"><?php echo htmlspecialchars($fetch_cart['name']); ?> <span class="text-muted">(Rp<?php echo number_format($fetch_cart['price'], 0, ',', '.'); ?> x <?php echo $fetch_cart['quantity']; ?>)</span></p>
                    <?php
                        }
                    } else {
                        echo '<p class="text-danger">Your cart is empty</p>';
                    }
                    ?>
                    <div class="mt-3 fw-bold">Grand Total: <span class="text-primary">Rp<?php echo number_format($grand_total, 0, ',', '.'); ?></span></div>
                </div>
            </div>
        </section>

        <section>
            <form action="" method="post" class="card shadow p-4">
                <h5 class="card-title mb-3">Place Your Order</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Nama">
                    </div>
                    <div class="col-md-6">
                        <label for="number" class="form-label">No HP</label>
                        <input type="number" class="form-control" id="number" name="number" required placeholder="No HP">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Email">
                    </div>
                    <div class="col-md-6">
                        <label for="method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="method" name="method" required>
                            <option value="Cash On Delivery">Cash on Delivery</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="flat" class="form-label">Alamat Rumah</label>
                        <input type="text" class="form-control" id="flat" name="flat" required placeholder="Alamat Rumah">
                    </div>
                    <div class="col-md-4">
                        <label for="city" class="form-label">Kelurahan/Desa</label>
                        <input type="text" class="form-control" id="city" name="city" required placeholder="Kelurahan/Desa">
                    </div>
                    <div class="col-md-4">
                        <label for="state" class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" id="state" name="state" required placeholder="Kecamatan">
                    </div>
                    <div class="col-md-4">
                        <label for="country" class="form-label">Kabupaten/Kota</label>
                        <input type="text" class="form-control" id="country" name="country" required placeholder="Kabupaten/Kota">
                    </div>
                    <div class="col-md-4">
                        <label for="province" class="form-label">Provinsi</label>
                        <input type="text" class="form-control" id="province" name="province" required placeholder="Provinsi">
                    </div>
                    <div class="col-md-4">
                        <label for="pin_code" class="form-label">Kode Pos</label>
                        <input type="number" class="form-control" id="pin_code" name="pin_code" required placeholder="Kode Pos">
                    </div>
                </div>
                <button type="submit" name="order_btn" class="btn btn-primary mt-4">Order Now</button>
            </form>
        </section>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>
