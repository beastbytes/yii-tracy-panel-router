<?php
/**
 * @var ?int $matchTime
 * @var ?string $name
 */
?>
<?php if (!empty($name)): ?>
    <?= $name ?>:&nbsp;<?= number_format($matchTime * 1000, 3) ?>&nbsp;ms
<?php else: ?>
    redirect
<?php endif; ?>