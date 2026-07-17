<?php session_start();

require 'includes/auth.php';

?>


<?php include 'includes/header.php'; ?>

<?php include 'includes/sidebar.php'; ?>

<main class="content">

<h1>Dashboard</h1>

<div class="cards">

    <div class="card">
        <h3>Users</h3>
        <p>24</p>
    </div>

    <div class="card">
        <h3>Posts</h3>
        <p>102</p>
    </div>

    <div class="card">
        <h3>Pages</h3>
        <p>15</p>
    </div>

    <div class="card">
        <h3>Comments</h3>
        <p>321</p>
    </div>

</div>

</main>

<?php include 'includes/footer.php'; ?>