<div class="bar-chart">
  <?php foreach ($barChart->getXValues() as $index => $value) : ?>
    <div class="bar-chart-column" style="height: <?= $value ?>%; animation-delay: <?= 0.025 * $index ?>s;"></div>
  <?php endforeach; ?>
</div>
