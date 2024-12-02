<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
   exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
   $message = mysqli_real_escape_string($conn, $_POST['message']);
   mysqli_query($conn, "INSERT INTO `chat` (user_id, admin_id, message, sender) VALUES ('$user_id', '1', '$message', 'user')") or die('query failed');
   header("Location: user_chat.php");
   exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_message'])) {
   $message_id = mysqli_real_escape_string($conn, $_POST['message_id']);
   $message = mysqli_real_escape_string($conn, $_POST['message']);
   mysqli_query($conn, "UPDATE `chat` SET message = '$message' WHERE id = '$message_id' AND user_id = '$user_id'") or die('query failed');
   header("Location: user_chat.php");
   exit;
}

if (isset($_GET['delete_message'])) {
   $message_id = mysqli_real_escape_string($conn, $_GET['delete_message']);
   mysqli_query($conn, "DELETE FROM `chat` WHERE id = '$message_id' AND user_id = '$user_id'") or die('query failed');
   header("Location: user_chat.php");
   exit;
}

$chat_query = mysqli_query($conn, "SELECT * FROM `chat` WHERE user_id = '$user_id' ORDER BY timestamp") or die('query failed');
$chats = [];
while ($row = mysqli_fetch_assoc($chat_query)) {
   $chats[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Message - DualTekno</title>
   <link rel="icon" type="image/png" href="assets/logohexacropped.png" />
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
   <link href="styles.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
<header class="bg-primary py-3">
        <div class="container px-4 px-lg-5 my-5">
            <h1 class="text-center text-white">Message</h1>
        </div>
    </header>
<div class="container mt-5">
<h1 class="text-center mb-4">Seller</h1>
   <div class="card">
      <div class="card-body chat-box" style="height: 400px; overflow-y: auto;">
         <?php foreach ($chats as $chat): ?>
            <div class="d-flex <?= $chat['sender'] === 'user' ? 'justify-content-end' : 'justify-content-start' ?> mb-3">
            <div class="p-3 rounded <?= $chat['sender'] === 'user' ? 'bg-success text-white' : 'bg-light' ?>" style="max-width: 75%;">
                  <p class="mb-1"><?= $chat['message'] ?></p>
                  <small class="text-dark"><?= $chat['timestamp'] ?></small>
                  <?php if ($chat['sender'] === 'user'): ?>
                     <div class="mt-2">
                        <a href="javascript:void(0);" onclick="showEditForm(<?= $chat['id'] ?>, '<?= addslashes($chat['message']) ?>')" class="text-light me-2">Edit</a>
                        <a href="?delete_message=<?= $chat['id'] ?>" class="text-danger">Delete</a>
                     </div>
                  <?php endif; ?>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
      <form method="POST" class="d-flex mt-3">
         <textarea name="message" class="form-control me-2" rows="1" placeholder="Type your message..." required></textarea>
         <button type="submit" name="send_message" class="btn btn-primary">Send</button>
      </form>
   </div>
</div>
<br>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
   function showEditForm(id, message) {
      const formHtml = `
         <form method="POST" class="d-flex mt-3">
            <input type="hidden" name="message_id" value="${id}">
            <textarea name="message" class="form-control me-2" rows="1" required>${message}</textarea>
            <button type="submit" name="edit_message" class="btn btn-success">Update</button>
         </form>
      `;
      document.querySelector('.chat-box').innerHTML += formHtml;
   }
</script>
</body>
</html>
