<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];

$query = "
SELECT title, message
FROM insights
WHERE user_id = $userId
ORDER BY created_at DESC
LIMIT 5
";

$result = mysqli_query($conn, $query);

?>

<div class="smart-alerts">

    <h2>Smart Alerts</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <div class="alert-card">
                <h4><?= htmlspecialchars($row['title']); ?></h4>
                <p><?= htmlspecialchars($row['message']); ?></p>
            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>No smart alerts available yet.</p>

    <?php endif; ?>

</div>