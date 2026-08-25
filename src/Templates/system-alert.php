<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));

if ($systemAlertMessage === '') {
    return;
}
?>
<div class="fixed inset-x-0 top-0 z-[120] bg-yellow-300 text-black border-b border-yellow-400 px-3 py-1 text-center text-xs font-medium leading-tight sm:text-sm"
    role="alert"
    style="position: fixed; top: 0; left: 0; right: 0; z-index: 9999; background-color: #fde047; color: #000000; border-bottom: 1px solid #facc15; padding: 4px 12px; text-align: center; font-size: 12px; font-weight: 500; line-height: 1.25;">
    <?= htmlspecialchars($systemAlertMessage, ENT_QUOTES, 'UTF-8') ?>
</div>