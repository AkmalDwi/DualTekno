<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
   exit;
}

if (isset($_POST['update_product'])) {
   $update_id = $_POST['update_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $brand = mysqli_real_escape_string($conn, $_POST['brand']);
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $specifications = mysqli_real_escape_string($conn, $_POST['specifications']);
   $price = floatval($_POST['price']);
   $stock = intval($_POST['stock']);

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   if (!empty($image)) {
      // Hapus gambar lama jika ada
      $old_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$update_id'") or die('query failed');
      $fetch_old_image = mysqli_fetch_assoc($old_image_query);
      $old_image_path = 'uploaded_img/' . $fetch_old_image['image'];
      if (file_exists($old_image_path)) {
         unlink($old_image_path);
      }

      // Validasi ukuran gambar
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
         $update_query = "UPDATE `products` SET 
            name='$name', 
            brand='$brand', 
            category='$category', 
            specifications='$specifications', 
            price='$price', 
            stock='$stock', 
            image='$image' 
         WHERE id='$update_id'";
         mysqli_query($conn, $update_query) or die('query failed');
         $message[] = 'Product updated successfully!';
      }
   } else {
      // Update tanpa mengganti gambar
      $update_query = "UPDATE `products` SET 
         name='$name', 
         brand='$brand', 
         category='$category', 
         specifications='$specifications', 
         price='$price', 
         stock='$stock' 
      WHERE id='$update_id'";
      mysqli_query($conn, $update_query) or die('query failed');
      $message[] = 'Product updated successfully!';
   }
}

if (isset($_GET['update'])) {
   $update_id = $_GET['update'];
   $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
   if (mysqli_num_rows($update_query) > 0) {
      $fetch_product = mysqli_fetch_assoc($update_query);
   } else {
      header('location:admin_products.php');
      exit;
   }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>
   <link rel="icon" type="image/png" href="assets/logohexacropped.png" />

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <div class="container my-5">
      <h2 class="text-center mb-4">Update Product</h2>
      <div class="row">
         <div class="col-md-6 offset-md-3">
            <form action="" method="post" enctype="multipart/form-data" class="p-4 border rounded shadow-lg">
               <input type="hidden" name="update_id" value="<?php echo $fetch_product['id']; ?>">
               <div class="mb-3">
                  <label for="productName" class="form-label">Product Name</label>
                  <input type="text" name="name" id="productName" class="form-control form-control-lg" value="<?php echo $fetch_product['name']; ?>" required>
               </div>
               <div class="mb-3">
                  <label for="productCategory" class="form-label">Category</label>
                  <select name="category" id="productCategory" class="form-select form-select-lg" required>
                     <option value="Television" <?php if ($fetch_product['category'] == 'Television') echo 'selected'; ?>>Television</option>
                     <option value="Smartphone" <?php if ($fetch_product['category'] == 'Smartphone') echo 'selected'; ?>>Smartphone</option>
                     <option value="Laptop" <?php if ($fetch_product['category'] == 'Laptop') echo 'selected'; ?>>Laptop</option>
                  </select>
               </div>
               <div class="mb-3">
                  <label for="productBrand" class="form-label">Brand</label>
                  <input type="text" name="brand" id="productBrand" class="form-control form-control-lg" value="<?php echo $fetch_product['brand']; ?>" required>
               </div>
               <div class="mb-3">
                  <label for="productSpecifications" class="form-label">Specifications</label>
                  <textarea name="specifications" id="productSpecifications" class="form-control form-control-lg" rows="5" required><?php echo $fetch_product['specifications']; ?></textarea>
               </div>
               <div class="mb-3">
                  <label for="productPrice" class="form-label">Price</label>
                  <input type="number" name="price" id="productPrice" class="form-control form-control-lg" min="0" value="<?php echo $fetch_product['price']; ?>" required>
               </div>
               <div class="mb-3">
                  <label for="productStock" class="form-label">Stock</label>
                  <input type="number" name="stock" id="productStock" class="form-control form-control-lg" min="0" value="<?php echo $fetch_product['stock']; ?>" required>
               </div>

               <div class="mb-3">
                  <label for="productImage" class="form-label">Image</label>
                  <input type="file" name="image" id="productImage" class="form-control form-control-lg" accept="image/jpg, image/jpeg, image/png">
                  <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="Product Image" class="img-thumbnail mt-3" width="150">
               </div>
               <button type="submit" name="update_product" class="btn btn-primary btn-lg w-100">Update Product</button>
            </form>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>