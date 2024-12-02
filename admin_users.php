<?php

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>
   <link rel="icon" type="image/png" href="assets/logohexacropped.png" />

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="users py-5">
      <div class="container">
         <h1 class="text-center mb-4">User Accounts</h1>
         <div class="row g-4">
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            ?>
               <div class="col-md-4">
                  <div class="card shadow border-0">
                     <div class="card-body">
                        <h5 class="card-title">User ID: <span class="text-primary"><?php echo $fetch_users['id']; ?></span></h5>
                        <p class="card-text">Username: <strong><?php echo $fetch_users['name']; ?></strong></p>
                        <p class="card-text">Email: <strong><?php echo $fetch_users['email']; ?></strong></p>
                        <p class="card-text">User Type: 
                           <span class="fw-bold" style="color:<?php echo ($fetch_users['user_type'] == 'admin') ? 'orange' : 'black'; ?>">
                              <?php echo $fetch_users['user_type']; ?>
                           </span>
                        </p>
                        <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</a>
                     </div>
                  </div>
               </div>
            <?php
            };
            ?>
         </div>

      </div>
   </section>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
