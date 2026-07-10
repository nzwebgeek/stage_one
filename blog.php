<?php include 'includes/header.php'; ?>
<?php include 'db.php'; ?>
<?php include './helpers/helper.php'; ?>

<main>

<h1>Comments Section</h1>

<p>
    <strong>Start a comment here:</strong>
    Feel free to leave a comment, but make sure to register first.
</p>

<div class="comment-content-box">

    <div>Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>

    <div>Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>

    <div>Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>

    <div>Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>

    <div>
        <button id="toggleBtn">Change Color</button>
    </div>

    <div>Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>

</div>

<div class="wrapper">
    <?php comments($conn); ?>
</div>

</main>

<?php include 'includes/footer.php'; ?>