<div class="line-chart">
  <?php foreach ($lineChart->getDatasets() as $data): ?>
    <?php foreach ($data as $index => $point): ?>
      <?php if ($index !== 0) : ?>
        <div
          class="line-chart-segment"
          style="
            --x1: <?= $data[$index - 1]['x'] ?>%;
            --y1: <?= $data[$index - 1]['y'] ?>%;
            --x2: <?= $point['x'] ?>%;
            --y2: <?= $point['y'] ?>%;
        "></div>
      <?php endif; ?>
        <div class="line-chart-point"
             style="
               --info: &quot;<?= $point['info'] ?>&quot;;
               --x: <?= $point['x'] ?>%;
               --y: <?= $point['y'] ?>%;
        "></div>
    <?php endforeach; ?>
  <?php endforeach; ?>
</div>
