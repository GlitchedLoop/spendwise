<?php

$userId = $_SESSION['user_id'];

$query = "
SELECT title, message
FROM notifications
WHERE user_id = $userId
AND status = 'unread'
ORDER BY created_at DESC
LIMIT 3
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0):
?>

<div class="alert-box">

    <?php while ($alert = mysqli_fetch_assoc($result)): ?>

        <div class="alert-item">
            <h4><?= $alert['title']; ?></h4>
            <p><?= $alert['message']; ?></p>
        </div>

    <?php endwhile; ?>

</div>

<?php endif; ?>