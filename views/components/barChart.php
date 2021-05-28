<div <?= isset($showId) && $showId === false ? '' : 'id="visualization"' ?> class="bar-chart">
  <?php foreach ($barChart->getXPercentages() as $index => $value) : ?>
    <button
      class="bar-chart-column"
      style="
        height: <?= $value ?>%;
        animation-delay: <?= 0.025 * $index ?>s;
        --tooltip: &quot;<?= $barChart->getYValues()[$index] ?>&quot;;
        --value: &quot;<?= $barChart->getXValues()[$index] ?>&quot;;
    "></button>
  <?php endforeach; ?>
</div>
