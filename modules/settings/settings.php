<?php

require_once '../../includes/session_check.php';
$pageCSS = 'settings.css';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$userId = $_SESSION['user_id'];

$query = "
SELECT *
FROM settings
WHERE user_id = $userId
LIMIT 1
";

$settings = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>

<div class="settings-page">

    <h1>Settings</h1>

    <?php require_once '../../includes/flash_message.php'; ?>

    <form method="POST" action="update_settings.php" class="main-form">

        <label>Preferred Currency</label>

        <select name="currency">
            <option value="₹" <?= $settings['currency'] == '₹' ? 'selected' : ''; ?>>
                ₹ INR
            </option>
            <option value="$" <?= $settings['currency'] == '$' ? 'selected' : ''; ?>>
                $ USD
            </option>
            <option value="€" <?= $settings['currency'] == '€' ? 'selected' : ''; ?>>
                € EUR
            </option>
        </select>

        <button type="submit">
            Save Settings
        </button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>