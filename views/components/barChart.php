<div class="bar-chart">
  <?php foreach ($barChart->getXValues() as $index => $value) : ?>
    <button
      class="bar-chart-column"
      style="
        height: <?= $value ?>%;
        animation-delay: <?= 0.025 * $index ?>s;
        --tooltip: &quot;<?= $barChart->getYValues()[$index] ?>&quot;;
    "></button>
  <?php endforeach; ?>
</div>
