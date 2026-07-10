<?php
session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/my-header.php';

$message = "";

/* ==========================
   Upload Image
========================== */

if (isset($_POST['submit'])) {

    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed)) {

            $message = "Only JPG, JPEG, PNG, GIF and WebP images are allowed.";

        } elseif ($_FILES['image']['size'] > (5 * 1024 * 1024)) {

            $message = "Image must be under 5MB.";

        } else {

            $newFileName = uniqid('img_', true) . '.' . $extension;
            $filePath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {

                // Save image
                $stmt = $conn->prepare("
                    INSERT INTO images (filename, filepath)
                    VALUES (?, ?)
                ");

                $stmt->bind_param("ss", $newFileName, $filePath);

                if ($stmt->execute()) {

                    $imageId = $conn->insert_id;

                    $stmt->close();

                    // Update the user's profile image
                    $stmt = $conn->prepare("
                        UPDATE users
                        SET image_id = ?
                        WHERE id = ?
                    ");

                    $stmt->bind_param("ii", $imageId, $_SESSION['user_id']);
                    $stmt->execute();
                    $stmt->close();

                    $message = "Image uploaded successfully.";

                } else {

                    $message = "Database error: " . $stmt->error;
                    $stmt->close();

                }

            } else {

                $message = "Failed to upload image.";

            }

        }

    } else {

        $message = "Please select an image.";

    }
}

/* ==========================
   Load logged in user
========================== */

$stmt = $conn->prepare("
    SELECT
        u.username,
        i.filepath
    FROM users u
    LEFT JOIN images i
        ON u.image_id = i.id
    WHERE u.id = ?
");

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$stmt->close();
?>

<div class="container" id="container">

    <div class="header">
        <h1>Dashboard</h1>
    </div>

    <div class="menu">
        <a href="edit-index.php">Edit Home Posts</a><br>
        <a href="change_password.php">Change Password</a><br>
        <a href="logout.php">Logout</a><br>
        <a href="#" onclick="showPanel();">Upload Image</a>
    </div>

    <div class="content">

        <h3>Dashboard</h3>

        <p>Welcome <?= htmlspecialchars($user['username']); ?>!</p>

        <p>You are logged in.</p>

        <button id="toggleBtn">Change Color</button>

        <?php if (!empty($user['filepath'])) : ?>

            <img src="<?= htmlspecialchars($user['filepath']); ?>"
                 alt="Profile Picture"
                 style="max-width:200px; border-radius:10px;">

        <?php else : ?>

            <p>No profile image uploaded.</p>

        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" id="panel" style="background:navy;">

            <p>Select image to upload:</p>

            <input type="file"
                   name="image"
                   accept="image/*"
                   required>

            <input type="submit"
                   name="submit"
                   value="Upload Image">

        </form>

        <?php if ($message): ?>

            <div class="message">
                <?= htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>

    </div>

    <div class="footer">
        <h4>Footer</h4>
    </div>

</div>