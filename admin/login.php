<?php
session_start();

require 'includes/db.php';
 $error ="";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate here 
    if (empty($username) || empty($password)) {
    $error = "Please enter your username and password.";
    }

    // Query your users table here...
    $stmt = $conn->prepare(
        "SELECT
            u.id,
            u.username,
            u.password,
            u.role_id,
            r.name AS role_name
            FROM users u
            JOIN roles r
            ON u.role_id = r.id
            WHERE u.username = ?");

     $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        if (!in_array($user['role_name'], ['Super Admin', 'Admin'])) {
                $error = "You do not have permission to access the admin area.";
            }
        else{
             session_regenerate_id(true);

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['role_name'] = $user['role_name'];

            header("Location: index.php");
            exit;    

            } 
    }
    else {
    $error = "Invalid username or password.";
}

    
}
?>

    <link rel="stylesheet" href="assets/css/style.css">
        <main>
            <section>
            <h1 class="login-title">Admin Login</h1>
        <?php if (!empty($error)): ?>
            <p id="error-message"><?php echo htmlspecialchars($error); ?></p>
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

            </section>
        </main>

