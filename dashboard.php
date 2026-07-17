<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$message = "";

if (isset($_GET['saved'])) {
    $message = "Colours saved successfully.";
}

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


/* ==========================================
   Save Theme Colours
========================================== */
if (isset($_POST['save_colors'])) {

    $theme = $_POST['theme_color'];
    $background = $_POST['background_color'];
    $text = $_POST['text_color'];

    $stmt = $conn->prepare("
        UPDATE users
        SET theme_color=?,
            background_color=?,
            text_color=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "sssi",
        $theme,
        $background,
        $text,
        $_SESSION['user_id']
    );

    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?saved=1");
    exit();
}
/* ==========================================
   Upload Image
========================================== */

if (isset($_POST['submit'])) {

    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $extension = strtolower(
            pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)
        );

        if (!in_array($extension, $allowed)) {

            $message = "Only JPG, JPEG, PNG, GIF and WebP images are allowed.";

        } elseif ($_FILES['image']['size'] > (5 * 1024 * 1024)) {

            $message = "Image must be under 5MB.";

        } else {

            $newFileName = uniqid('img_', true) . '.' . $extension;
            $filePath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {

                $stmt = $conn->prepare("
                    INSERT INTO images (filename, filepath)
                    VALUES (?, ?)
                ");

                $stmt->bind_param("ss", $newFileName, $filePath);

                if ($stmt->execute()) {

                    $imageId = $conn->insert_id;

                    $stmt->close();

                    $stmt = $conn->prepare("
                        UPDATE users
                        SET image_id = ?
                        WHERE id = ?
                    ");

                    $stmt->bind_param(
                        "ii",
                        $imageId,
                        $_SESSION['user_id']
                    );

                    $stmt->execute();
                    $stmt->close();

                    $message = "Image uploaded successfully.";

                } else {

                    $message = "Database error: " . $stmt->error;
                }

            } else {

                $message = "Failed to upload image.";
            }
        }

    } else {

        $message = "Please select an image.";
    }
}

/* ==========================================
   Load User
========================================== */

$stmt = $conn->prepare("
    SELECT
        u.username,
        u.theme_color,
        u.background_color,
        u.text_color,
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

include 'includes/my-header.php';
?>

<style>
:root{
    --theme: <?= htmlspecialchars($user['theme_color'] ?: '#007bff'); ?>;
    --background: <?= htmlspecialchars($user['background_color'] ?: '#ffffff'); ?>;
    --footer: <?= htmlspecialchars($user['theme_color'] ?: '#051b33'); ?>;
    --text: <?= htmlspecialchars($user['text_color'] ?: '#000000'); ?>;
}

.main-body{
    background: var(--background);
    color: var(--text);
}

.header,
.footer,
.menu{
    background: var(--theme);
}

button{
    background: var(--theme);
    color: white;
}
</style>

<div class="container main-body" id="container" >

    <div class="header">
        <h1>Dashboard </h1>
    </div>
<?php
/* ==========================================
   Load Posts
========================================== */

$stmt = $conn->prepare("
    SELECT id, title
    FROM posts
    ORDER BY id DESC
");

$stmt->execute();

$posts = $stmt->get_result();

$stmt->close();
?>
<?php
/*check becomes:*/
?>
   <div class="menu">

  <a href="#" id="showPosts">Edit Post</a><br>

    <a href="change_password.php">Change Password</a><br>

    <a href="#" class="show-form" data-form="uploadForm">
        Upload Image
    </a><br>

    <a href="#" class="show-form" data-form="profileForm">
        Edit Profile
    </a><br>

     <?php if (in_array($_SESSION['role'] ?? '', ['Super Admin', 'Admin'])): ?>
    <a href="/admin/">Admin Panel</a>
    <?php endif; ?>
        <br>
    <a href="logout.php">Logout</a><br>
   

</div>

    <div class="content">

        <h3>Dashboard</h3>

        <p>Welcome <?= htmlspecialchars($user['username']); ?>!</p>

        <p>You are logged in.</p>

        <?php if (!empty($user['filepath'])) : ?>

            <img src="<?= htmlspecialchars($user['filepath']); ?>"
                 alt="Profile Picture"
                 style="max-width:200px; border-radius:10px;">

        <?php else : ?>

            <p>No profile image uploaded.</p>

        <?php endif; ?>

<form method="post"
      id="uploadForm"
      class="form"
      enctype="multipart/form-data"
      style="display:none; background:navy;">
            <p>Select image to upload:</p>

            <input type="file"
                   name="image"
                   accept="image/*"
                   required>

            <input type="submit"
                   name="submit"
                   value="Upload Image">

        </form>

       <form method="post" id="profileForm" class="form" style="display:none;">

    <label>Theme Colour</label>
    <input
        type="color"
        name="theme_color"
        value="<?= htmlspecialchars($user['theme_color'] ?: '#007bff'); ?>">

    <label>Background Colour</label>
    <input
        type="color"
        name="background_color"
        value="<?= htmlspecialchars($user['background_color'] ?: '#ffffff'); ?>">

    <label>Text Colour</label>
    <input
        type="color"
        name="text_color"
        value="<?= htmlspecialchars($user['text_color'] ?: '#000000'); ?>">

    <button type="submit" name="save_colors">
        Save Colours
    </button>

</form> 


        <?php if ($message): ?>

            <div class="message">
                <?= htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>
<br>

<div class="post-links" id="postList" style="display:none;">

    <h3>Edit Home Posts</h3>

    <?php while ($post = $posts->fetch_assoc()) : ?>

        <p>
            <a href="edit-index.php?id=<?= $post['id']; ?>">
                <?= htmlspecialchars($post['title']); ?>
            </a>
        </p>

    <?php endwhile; ?>

</div>

    </div>
    
    <div class="footer">
        <h4>Footer</h4>
    </div>

</div>