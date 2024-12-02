<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

if (isset($_POST['add_product'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $brand = mysqli_real_escape_string($conn, $_POST['brand']);
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $specifications = mysqli_real_escape_string($conn, $_POST['specifications']);
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if (!is_numeric($stock) || $stock < 0) {
      $message[] = 'Stock must be a positive number!';
   }

   if (mysqli_num_rows($select_product_name) > 0) {
      $message[] = 'Product name already added!';
   } else {
      $stock = $_POST['stock'];
$add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, brand, category, specifications, price, image, stock) VALUES('$name', '$brand', '$category', '$specifications', '$price', '$image', '$stock')");


      if ($add_product_query) {
         if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Product added successfully!';
         }
      } else {
         $message[] = 'Product could not be added!';
      }
   }
}

if (isset($message)) {
   foreach ($message as $msg) {
      echo '<div class="alert alert-info text-center">' . $msg . '</div>';
   }
}




if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Products</title>
   <link rel="icon" type="image/png" href="assets/logohexacropped.png" />

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <div class="container my-5">

      <section class="mb-5">
         <h2 class="text-center mb-4">Add New Product</h2>
         <div class="row">
            <div class="col-md-6 offset-md-3">
               <form action="" method="post" enctype="multipart/form-data" class="p-4 border rounded shadow-lg">
                  <div class="mb-3">
                     <label for="productName" class="form-label">Product Name</label>
                     <input type="text" name="name" id="productName" class="form-control form-control-lg"
                        placeholder="Enter product name" required>
                  </div>
                  <div class="mb-3">
                     <label for="productCategory" class="form-label">Category</label>
                     <select name="category" id="productCategory" class="form-select form-select-lg" required>
                        <option value="Television">Television</option>
                        <option value="Smartphone">Smartphone</option>
                        <option value="Laptop">Laptop</option>
                     </select>
                  </div>
                  <div class="mb-3">
                     <label for="productBrand" class="form-label">Brand</label>
                     <input type="text" name="brand" id="productBrand" class="form-control form-control-lg"
                        placeholder="Enter product brand" required>
                  </div>
                  <div class="mb-3">
                     <label for="productSpecifications" class="form-label">Specifications</label>
                     <textarea name="specifications" id="productSpecifications" class="form-control form-control-lg"
                        rows="5" placeholder="Enter product specifications" required></textarea>
                  </div>

                  <div class="mb-3">
                     <label for="productPrice" class="form-label">Price</label>
                     <input type="number" name="price" id="productPrice" class="form-control form-control-lg" min="0"
                        placeholder="Enter product price" required>
                  </div>
                  <div class="mb-3">
                     <label for="productStock" class="form-label">Stock</label>
                     <input type="number" name="stock" id="productStock" class="form-control form-control-lg" min="0" placeholder="Enter product stock" required>
                  </div>

                  <div class="mb-3">
                     <label for="productImage" class="form-label">Image</label>
                     <input type="file" name="image" id="productImage" class="form-control form-control-lg"
                        accept="image/jpg, image/jpeg, image/png" required>
                  </div>
                  <button type="submit" name="add_product" class="btn btn-primary btn-lg w-100">Add Product</button>
               </form>
            </div>
         </div>
      </section>

      <section>
         <h2 class="text-center mb-4">Products</h2>
         <div class="row g-4">

            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                  <div class="col-md-4">
                     <div class="card h-100">
                        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="card-img-top"
                           alt="Product Image">
                        <div class="card-body">
                           <h5 class="card-title"><?php echo $fetch_products['name']; ?></h5>
                           <p class="card-text">Category: <?php echo $fetch_products['category']; ?></p>
                           <p class="card-text">Price: Rp<?php echo $fetch_products['price']; ?></p>
                           <p class="card-text">Brand: <?php echo $fetch_products['brand']; ?></p>
                           <p class="card-text">Specifications: <?php echo nl2br($fetch_products['specifications']); ?></p>
                           <p class="card-text">Stock: <?php echo $fetch_products['stock']; ?></p>

                        </div>
                        <div class="card-footer text-center">
                           <a href="update.php?update=<?php echo $fetch_products['id']; ?>"
                              class="btn btn-warning btn-sm">Update</a>
                           <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>"
                              class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</a>
                        </div>
                     </div>
                  </div>
            <?php
               }
            } else {
               echo '<p class="text-center text-muted">No products available yet!</p>';
            }
            ?>

         </div>
      </section>

   </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>