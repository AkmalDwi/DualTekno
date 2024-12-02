<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_GET['order_id'])) {
   $order_id = $_GET['order_id'];
   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id' AND user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($order_query) > 0) {
      $fetch_order = mysqli_fetch_assoc($order_query);
   } else {
      echo "<script>alert('Pesanan tidak ditemukan!'); window.location = 'orders.php';</script>";
      exit();
   }
} else {
   header('location:orders.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Invoice - DualTekno</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
      body {
         background-color: #f8f9fa;
         font-family: Arial, sans-serif;
      }
      .invoice-container {
         max-width: 800px;
         margin: 50px auto;
         padding: 30px;
         background: #ffffff;
         border-radius: 8px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      .invoice-header {
         text-align: center;
         margin-bottom: 30px;
      }
      .invoice-header img {
         width: 80px;
         margin-bottom: 10px;
      }
      .invoice-header h2 {
         font-size: 24px;
         font-weight: bold;
         margin: 0;
         color: #343a40;
      }
      .invoice-details {
         margin-bottom: 20px;
      }
      .invoice-footer {
         text-align: center;
         margin-top: 30px;
         font-size: 14px;
         color: #6c757d;
      }
      .btn-print {
         background-color: #007bff;
         color: #fff;
      }
      .btn-back {
         background-color: #6c757d;
         color: #fff;
      }
   </style>
</head>

<body>
   <div class="invoice-container">
      <div class="invoice-header">
         <img src="assets/logohexacropped.png" alt="Logo">
         <h2>DualTekno Invoice</h2>
         <p class="text-muted">No. Pesanan: <?php echo $fetch_order['id']; ?></p>
      </div>

      <div class="invoice-details">
         <h5 class="text-primary">Detail Pelanggan</h5>
         <p><strong>Nama:</strong> <?php echo $fetch_order['name']; ?></p>
         <p><strong>Email:</strong> <?php echo $fetch_order['email']; ?></p>
         <p><strong>Nomor:</strong> <?php echo $fetch_order['number']; ?></p>
         <p><strong>Alamat:</strong> <?php echo $fetch_order['address']; ?></p>

         <hr>

         <h5 class="text-primary">Detail Pesanan</h5>
         <p><strong>Tanggal Pemesanan:</strong> <?php echo $fetch_order['placed_on']; ?></p>
         <p><strong>Metode Pembayaran:</strong> <?php echo $fetch_order['method']; ?></p>
         <p><strong>Produk:</strong> <?php echo $fetch_order['total_products']; ?></p>
         <p><strong>Total Harga:</strong> Rp<?php echo number_format($fetch_order['total_price'], 0, ',', '.'); ?></p>
         <p><strong>Status Pembayaran:</strong> 
            <span class="fw-bold" style="color: <?php echo ($fetch_order['payment_status'] == 'pending') ? 'red' : 'green'; ?>">
               <?php echo $fetch_order['payment_status']; ?>
            </span>
         </p>
      </div>

      <div class="text-center mt-4">
         <button onclick="window.print()" class="btn btn-print btn-lg mx-2">Cetak Invoice</button>
         <a href="orders.php" class="btn btn-back btn-lg mx-2">Kembali</a>
      </div>

      <div class="invoice-footer">
         <p>Terima kasih telah berbelanja di DualTekno. Jika ada pertanyaan, silakan hubungi <a href="mailto:cs@dualtekno.com">cs@dualtekno.com</a>.</p>
      </div>
   </div>
</body>

</html>
