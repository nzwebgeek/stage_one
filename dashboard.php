
<?php session_start(); 



if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include 'includes/my-header.php';

?>

<div class="container">
  <div class="header"><h1>Dashboard</h1></div>
  <div class="menu"><a href="index.php">Home</a><br><a href="#">Change Password</a><br><a href="logout.php">Logout</a></div>
  <div class="content"><h3>Lorem Ipsum</h3>
<p>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

<p>You are logged in.</p>

<a href="logout.php">Logout</a>


</div>
  <div class="footer"><h4>Footer</h4></div>
</div>

</div>
