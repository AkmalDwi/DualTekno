<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart - DualTekno</title>
    <link rel="icon" type="image/png" href="assets/logohexacropped.png" />


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">

   <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>


<header class="bg-primary py-3">
        <div class="container px-4 px-lg-5 my-5">
            <h1 class="text-center text-white">Cart</h1>
        </div>
    </header>
    <br>

<section class="container shopping-cart">
   <h1 class="text-center mb-4">Products Added</h1>

   <div class="row">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
      ?>
      <div class="col-md-4">
         <div class="card mb-4 shadow-sm">
         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" class="card-img-top" alt="Product Image">
            <div class="card-body">
               <h5 class="card-title"><?php echo $fetch_cart['name']; ?></h5>
               <p class="card-text text-muted">Price: Rp<?php echo $fetch_cart['price']; ?></p>
               <form action="" method="post">
                  <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                  <div class="d-flex mb-2">
                     <input type="number" class="form-control me-2" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                     <button type="submit" name="update_cart" class="btn btn-primary">Update</button>
                  </div>
               </form>
               <p class="card-text"><strong>Sub Total:</strong> Rp<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?></p>
               <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this from cart?');">
                  <i class="fas fa-trash-alt"></i> Delete
               </a>
            </div>
         </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="text-center text-muted">Your cart is empty.</p>';
      }
      ?>
   </div>

   <div class="text-center mb-4">
      <a href="cart.php?delete_all" class="btn btn-danger <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
   </div>

   <div class="card">
      <div class="card-body text-center">
         <h5>Grand Total: <span class="text-success">Rp<?php echo $grand_total; ?></span></h5>
         <div class="d-flex justify-content-center gap-3 mt-3">
            <a href="home.php" class="btn btn-secondary">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-success <?php echo ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout</a>
         </div>
      </div>
   </div>
</section>
<br>

<?php include 'footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="scripts.js"></script>

</body>
</html>
