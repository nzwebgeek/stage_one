<?php
//session_start();
include 'includes/header.php';
require 'db.php';

$message = "";
$role_id = 5; // User

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    

    if ($password != $confirm_password) {
        $message = "Passwords do not match.";
    } else {

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $hashedPassword, $role_id);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Username or email may already exist.";
        }

        $stmt->close();
    }
}
?>

<main>
<section>

<h1>Register</h1>

<?php if (!empty($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="post">

<label for="username">Username</label>
<input type="text" id="username" name="username" required>

<label for="email">Email</label>
<input type="email" id="email" name="email" required>

<label for="password">Password</label>
<input type="password" id="password" name="password" required>

<label for="confirm_password">Confirm Password</label>
<input type="password" id="confirm_password" name="confirm_password" required>

<input type="submit" value="Register">

</form>
        <button id="toggleBtn">Change Color</button>

</section>

</main>

<?php include 'includes/footer.php'; ?>