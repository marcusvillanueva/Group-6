<?php
// Simple column chart drawn with CSS.
// $chart = ['2026-05' => 3, '2026-06' => 7, ...], $money = true to show pesos.
$max = max(array_merge([1], array_values($chart)));
?>
<div class="column-chart">
    <?php foreach ($chart as $month => $value): ?>
        <div class="column">
            <span class="column-value"><?= ! empty($money) ? peso($value) : (int) $value ?></span>
            <div class="column-bar" style="height: <?= round($value / $max * 100) ?>%"></div>
            <span class="column-label"><?= date('M', strtotime($month . '-01')) ?></span>
        </div>
    <?php endforeach; ?>
</div>
