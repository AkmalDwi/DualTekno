<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
         <strong>' . $message . '</strong>
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      ';
   }
}
?>

<header class="bg-primary text-white py-3">
   <div class="container d-flex align-items-center justify-content-between">
      <a href="admin_page.php" class="text-white text-decoration-none fs-4 fw-bold">
         Admin<span class="text-warning">DualTekno</span>
      </a>

      <nav class="navbar navbar-expand-lg">
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
               <li class="nav-item">
                  <a href="admin_page.php" class="nav-link text-white">Home</a>
               </li>
               <li class="nav-item">
                  <a href="admin_products.php" class="nav-link text-white">Products</a>
               </li>
               <li class="nav-item">
                  <a href="admin_orders.php" class="nav-link text-white">Orders</a>
               </li>
               <li class="nav-item">
                  <a href="admin_users.php" class="nav-link text-white">Users</a>
               </li>
               <li class="nav-item">
                  <a href="admin_chat.php" class="nav-link text-white">Chats</a>
               </li>
            </ul>
         </div>
      </nav>
      <div class="dropdown">
         <a href="#" class="text-white text-decoration-none dropdown-toggle" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>
         </a>
         <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li><span class="dropdown-item-text">Username: <strong><?php echo $_SESSION['admin_name']; ?></strong></span></li>
            <li><span class="dropdown-item-text">Email: <strong><?php echo $_SESSION['admin_email']; ?></strong></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a href="logout.php" class="dropdown-item text-danger">Logout</a></li>
         </ul>
      </div>
   </div>
</header>
