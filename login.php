
<?php
session_start();


require 'db.php';
include 'includes/header.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit();

    } else {
        $message = "Invalid username or password.";
    }

    $stmt->close();
}

?>
<main>
    <section>
        <h1>Login</h1>

        <?php if (!empty($message)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Your username..."
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Your password..."
                required
            >

            <input type="submit" value="Login">
        </form>

        <button id="toggleBtn">Change Color</button>

    </section>
</main>

<?php include 'includes/footer.php'; ?>