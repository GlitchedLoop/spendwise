<?php

require_once '../../includes/session_check.php';
$pageCSS = 'profile.css';

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$userId = $_SESSION['user_id'];

$query = "
SELECT full_name, email
FROM users
WHERE id = $userId
LIMIT 1
";

$user = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>

<div class="profile-page">

    <h1>Profile</h1>

    <?php require_once '../../includes/flash_message.php'; ?>

    <div class="profile-grid">

        <div class="profile-card">

            <h2>Update Profile</h2>

            <form method="POST" action="update_profile.php">

                <input
                    type="text"
                    name="full_name"
                    value="<?= htmlspecialchars($user['full_name']); ?>"
                    required
                >

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email']); ?>"
                    required
                >

                <button type="submit">
                    Save Changes
                </button>

            </form>

        </div>

        <div class="profile-card">

            <h2>Change Password</h2>

            <form method="POST" action="update_password.php">

                <input
                    type="password"
                    name="current_password"
                    placeholder="Current Password"
                    required
                >

                <input
                    type="password"
                    name="new_password"
                    placeholder="New Password"
                    required
                >

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm New Password"
                    required
                >

                <button type="submit">
                    Update Password
                </button>

            </form>

        </div>

    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>