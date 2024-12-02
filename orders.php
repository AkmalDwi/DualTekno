<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders - DualTekno</title>
   <link rel="icon" type="image/png" href="assets/logohexacropped.png" />

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="styles.css" rel="stylesheet">
</head>

<body>

   <?php include 'header.php'; ?>

   <header class="bg-primary py-3">
        <div class="container px-4 px-lg-5 my-5">
            <h1 class="text-center text-white">Orders</h1>
        </div>
    </header>

   <section class="placed-orders py-5">
      <div class="container">

         <div class="row g-4">
            <?php
            $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($order_query) > 0) {
               while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
            ?>
                  <div class="col-md-6 col-lg-4">
                     <div class="card shadow border-0">
                        <div class="card-body">
                           <h5 class="card-title text-primary">Pesanan pada: <?php echo $fetch_orders['placed_on']; ?></h5>
                           <p><strong>Nama:</strong> <?php echo $fetch_orders['name']; ?></p>
                           <p><strong>Nomor:</strong> <?php echo $fetch_orders['number']; ?></p>
                           <p><strong>Email:</strong> <?php echo $fetch_orders['email']; ?></p>
                           <p><strong>Alamat:</strong> <?php echo $fetch_orders['address']; ?></p>
                           <p><strong>Metode Pembayaran:</strong> <?php echo $fetch_orders['method']; ?></p>
                           <p><strong>Pesanan Anda:</strong> <?php echo $fetch_orders['total_products']; ?></p>
                           <p><strong>Total Harga:</strong> Rp<?php echo $fetch_orders['total_price']; ?></p>
                           <p><strong>Status Pembayaran:</strong>
                              <span class="fw-bold" style="color: <?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>">
                                 <?php echo $fetch_orders['payment_status']; ?>
                              </span>
                           </p>
                           <a href="invoice.php?order_id=<?php echo $fetch_orders['id']; ?>" class="btn btn-primary mt-3">
                              Cetak Invoice
                           </a>
                        </div>
                     </div>
                  </div>
            <?php
               }
            } else {
               echo '<p class="text-center text-secondary">Belum ada pesanan yang dilakukan!</p>';
            }
            ?>
         </div>

      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
   <script src="scripts.js"></script>
</body>

</html>