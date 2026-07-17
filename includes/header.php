<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
      

    <script src="js/script.js" defer></script>
</head>
<body>
 

<div class="container" id="container">

<header>
    <nav>
        <ul>
           <li>
            Welcome <?= htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>
            </li>
        </ul>
      <ul>
        <?php if (isset($_SESSION['user_id'])): ?>        
        <li><a href="index.php">Home</a></li> 
        <li><a href="blog.php">Blog</a></li> 
        <li><a href="dashboard.php">Dashboard</a></li> 
        <li><a href="logout.php">Logout</a> </li>
        <?php else : ?>
        <li><a href="index.php">Home</a></li> 
        <li><a href="blog.php">Blog</a></li> 
        <li><a href="register.php">Register</a></li> 
        <li><a href="contact.php">Contact</a></li> 
        <li><a href="login.php">Login</a></li> 
        <?php endif;?>
    </ul>
    </nav>
</header>
    