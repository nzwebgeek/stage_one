<?php

session_start();

require 'includes/auth.php';

include 'includes/header.php';
include 'includes/sidebar.php';

?>

<h1>Users</h1>

<a href="create-user.php" class="button">+ Add User</a>

<table>

<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Actions</th>
</tr>

<?php foreach($users as $user): ?>

<tr>

    <td><?= htmlspecialchars($user['username']); ?></td>

    <td><?= htmlspecialchars($user['email']); ?></td>

    <td><?= htmlspecialchars($user['role']); ?></td>

    <td>

        <a href="edit-user.php?id=<?= $user['id']; ?>">Edit</a>

        |

        <a href="delete-user.php?id=<?= $user['id']; ?>">Delete</a>

    </td>

</tr>

<?php endforeach; ?>

</table>

<?php include 'includes/footer.php'; ?>