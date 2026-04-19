<?php

require_once '../../includes/session_check.php';
$pageCSS = 'upload.css';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<div class="upload-page">

    <h1>Upload Bank Statement CSV</h1>

    <?php require_once '../../includes/flash_message.php'; ?>

    <form
        method="POST"
        action="parse_csv.php"
        enctype="multipart/form-data"
        class="main-form"
    >

        <input
            type="file"
            name="csv_file"
            accept=".csv"
            required
        >

        <button type="submit">
            Upload & Preview
        </button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>