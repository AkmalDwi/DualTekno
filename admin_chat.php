<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:login.php');
   exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
   $user_id = $_POST['user_id'];
   $message = mysqli_real_escape_string($conn, $_POST['message']);
   mysqli_query($conn, "INSERT INTO `chat` (user_id, admin_id, message, sender) VALUES ('$user_id', '$admin_id', '$message', 'admin')") or die('query failed');
   header("Location: admin_chat.php?user_id=$user_id");
   exit;
}

$users_query = mysqli_query($conn, "
   SELECT DISTINCT c.user_id, u.name 
   FROM chat c 
   JOIN users u ON c.user_id = u.id
") or die('query failed');

$chats = [];
if (isset($_GET['user_id'])) {
   $user_id = $_GET['user_id'];
   $chat_query = mysqli_query($conn, "
      SELECT c.*, u.name 
      FROM chat c 
      JOIN users u ON c.user_id = u.id 
      WHERE c.user_id = '$user_id' 
      ORDER BY c.timestamp
   ") or die('query failed');
   while ($row = mysqli_fetch_assoc($chat_query)) {
      $chats[] = $row;
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Chat</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="icon" type="image/png" href="assets/logohexacropped.png" />
</head>
<body>
<?php include 'admin_header.php'; ?>
<div class="container mt-5">
   <h2 class="text-center">Admin Chat</h2>
   <div class="row">
      <div class="col-md-4">
         <div class="list-group">
            <h4 class="mb-3">Users</h4>
            <?php while ($user = mysqli_fetch_assoc($users_query)): ?>
               <a href="admin_chat.php?user_id=<?= $user['user_id'] ?>" class="list-group-item list-group-item-action">
                  Chat with <?= $user['name'] ?>
               </a>
            <?php endwhile; ?>
         </div>
      </div>
      <div class="col-md-8">
         <?php if (!empty($chats)): ?>
            <div class="card">
               <div class="card-body chat-box" style="height: 400px; overflow-y: auto;">
                  <?php foreach ($chats as $chat): ?>
                     <div class="d-flex <?= $chat['sender'] === 'admin' ? 'justify-content-end' : 'justify-content-start' ?> mb-3">
                        <div class="p-3 rounded <?= $chat['sender'] === 'admin' ? 'bg-primary text-white' : 'bg-light' ?>" style="max-width: 75%;">
                           <p class="mb-1"><strong><?= $chat['sender'] === 'admin' ? 'You' : $chat['name'] ?>:</strong> <?= $chat['message'] ?></p>
                           <small class="text-muted"><?= $chat['timestamp'] ?></small>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
               <form method="POST" class="d-flex mt-3">
                  <input type="hidden" name="user_id" value="<?= $user_id ?>">
                  <textarea name="message" class="form-control me-2" rows="1" placeholder="Type your message..." required></textarea>
                  <button type="submit" name="send_message" class="btn btn-primary">Send</button>
               </form>
            </div>
         <?php else: ?>
            <p class="text-center">Select a user to start chatting</p>
         <?php endif; ?>
      </div>
   </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
