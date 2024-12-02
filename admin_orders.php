<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>
   <link rel="icon" type="image/png" href="assets/logohexacropped.png" />
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="orders container my-5">

   <h1 class="text-center mb-4">Placed Orders</h1>

   <div class="row g-4">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm h-100">
            <div class="card-body">
               <p class="card-text"><strong>User ID:</strong> <span><?php echo $fetch_orders['user_id']; ?></span></p>
               <p class="card-text"><strong>Placed On:</strong> <span><?php echo $fetch_orders['placed_on']; ?></span></p>
               <p class="card-text"><strong>Name:</strong> <span><?php echo $fetch_orders['name']; ?></span></p>
               <p class="card-text"><strong>Number:</strong> <span><?php echo $fetch_orders['number']; ?></span></p>
               <p class="card-text"><strong>Email:</strong> <span><?php echo $fetch_orders['email']; ?></span></p>
               <p class="card-text"><strong>Address:</strong> <span><?php echo $fetch_orders['address']; ?></span></p>
               <p class="card-text"><strong>Total Products:</strong> <span><?php echo $fetch_orders['total_products']; ?></span></p>
               <p class="card-text"><strong>Total Price:</strong> <span>Rp<?php echo $fetch_orders['total_price']; ?></span></p>
               <p class="card-text"><strong>Payment Method:</strong> <span><?php echo $fetch_orders['method']; ?></span></p>

               <form action="" method="post" class="mt-3">
                  <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                  <div class="mb-3">
                     <label for="updatePayment<?php echo $fetch_orders['id']; ?>" class="form-label">Update Payment Status</label>
                     <select name="update_payment" id="updatePayment<?php echo $fetch_orders['id']; ?>" class="form-select">
                        <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                     </select>
                  </div>
                  <button type="submit" name="update_order" class="btn btn-primary btn-sm w-100 mb-2">Update</button>
                  <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');" class="btn btn-danger btn-sm w-100">Delete</a>
               </form>
            </div>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="text-center text-muted">No orders placed yet!</p>';
      }
      ?>
   </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
