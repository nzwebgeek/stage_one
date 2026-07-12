<?php

//include 'db.php';


function comments($conn, $postId)
{
    $commentsPerPage = 5;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }

    $offset = ($page - 1) * $commentsPerPage;

    // Count total comments
    $countSql = "SELECT COUNT(*) AS total FROM blogg";
    $countResult = $conn->query($countSql);
    $totalComments = $countResult->fetch_assoc()['total'];

    // Get only this page of comments
    $sql = "SELECT blogg.comment, users.username
            FROM blogg
            INNER JOIN users
            ON blogg.user_id = users.id
            WHERE blogg.post_id = ?
            ORDER BY blogg.id DESC
            LIMIT ?, ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $postId, $offset, $commentsPerPage);
            $stmt->execute();

            $result = $stmt->get_result();
    

    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="comment">
            <h3>' . htmlspecialchars($row['username']) . '</h3>
            <p>' . nl2br(htmlspecialchars($row['comment'])) . '</p>
        </div>';
    }

    // Pagination
    $totalPages = ceil($totalComments / $commentsPerPage);

    echo '<div class="pagination">';

    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '">Previous</a> ';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<a href="?page=' . $i . '">' . $i . '</a> ';
    }

    if ($page < $totalPages) {
        echo '<a href="?page=' . ($page + 1) . '">Next</a>';
    }

    echo '</div>';
}

function posts($conn)
{
    $sql = "SELECT posts.*, users.username
            FROM posts
            JOIN users
            ON posts.user_id = users.id
            ORDER BY posts.id DESC";

    $result = $conn->query($sql);

    while($row = $result->fetch_assoc())
    {
        echo '
        <article class="post">

            <h2>'.htmlspecialchars($row["title"]).'</h2>

            <small>
                Posted by '.htmlspecialchars($row["username"]).'
            </small>

            <p>'.nl2br(htmlspecialchars($row["content"])).'</p>

        </article>';

        comments($conn, $row["id"]);
    }
}
?>