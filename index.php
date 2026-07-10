<?php include 'includes/header.php'; ?>
<?php include 'db.php'; ?>
<?php
$sql = "SELECT blogg.comment, users.username
         FROM blogg INNER JOIN users
         ON blogg.user_id = users.id ";

$result = $conn->query($sql);
?>
<div class="container">
    <h1>Home Page</h1>
    <main>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur nihil et labore dolore itaque ad voluptate aperiam, totam dignissimos harum neque assumenda doloribus earum repudiandae! Error esse necessitatibus deleniti molestiae?</p>
        <article>
            <h3>Comment Below</h3>


    <?php
if ($result->num_rows > 0) {

    $sql = "SELECT blogg.comment, users.username
        FROM blogg
        INNER JOIN users
        ON blogg.user_id = users.id
        ORDER BY blogg.id DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<h4>" . htmlspecialchars($row['username']) . "</h4>";
    echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
    echo "<hr>";

    }

} else {
    echo "<p>No comments yet.</p>";
}

$conn->close();
?>

            <p>Grid structures make full-page layout management simple.</p>



            <button id="toggleBtn">Change Color</button>
      </article>
    </section>
    </main>
</div>
<?php include 'includes/footer.php'; ?>