<?php

$flash = getFlashMessage();

if ($flash):
?>

<div class="flash-message <?= $flash['type']; ?>">
    <?= $flash['message']; ?>
</div>

<?php endif; ?>