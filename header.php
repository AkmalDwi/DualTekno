<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
   <div class="container">
      <a class="navbar-brand" href="#">
         <img src="assets/logohexacropped.png" alt="DualTekno" width="30" height="30" class="d-inline-block align-text-top">
         DualTekno
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
         <ul class="navbar-nav me-auto">
            <li class="nav-item">
               <a class="nav-link active" aria-current="page" href="home.php">Home</a>
            </li>
            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Products
               </a>
               <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="laptop.php">Laptop</a></li>
                  <li><a class="dropdown-item" href="smartphone.php">Smartphone</a></li>
                  <li><a class="dropdown-item" href="televisi.php">Televisi</a></li>
               </ul>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="about.php">About</a>
            </li>
         </ul>

         <div class="d-flex align-items-center">
            <form action="search.php" method="GET" class="d-flex justify-content-center mt-2 me-3">
               <input type="text" name="query" class="form-control me-2" placeholder="Search products..." aria-label="Search">
               <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>

            <a id="dark-mode-toggle" role="button">
               <button type="button" class="btn btn-dark">
                  <i id="darkModeIcon" class="bi-sun"></i>
               </button>
            </a>


            <a class="btn btn-outline-dark position-relative ms-3" href="orders.php">
               <i class="bi bi-receipt"></i>
               <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle p-1">
               </span>
            </a>

            <a href="cart.php" class="btn btn-outline-dark position-relative ms-3">
               <i class="bi bi-cart-fill"></i>
               <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                  <?php
                  $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                  $cart_rows_number = mysqli_num_rows($select_cart_number);
                  echo $cart_rows_number;
                  ?>
               </span>
            </a>

            <a href="user_chat.php" class="btn btn-outline-dark ms-3">
               <i class="bi bi-envelope-fill"></i>
               <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
               </span>
            </a>

            <div class="dropdown ms-3">
               <a class="btn btn-light dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-person-circle" style="font-size: 24px;"></i>
               </a>
               <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li>
                     <p class="dropdown-item"><strong>Username:</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
                  </li>
                  <li>
                     <p class="dropdown-item"><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user_email']) ?></p>
                  </li>
                  <li>
                     <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</nav>