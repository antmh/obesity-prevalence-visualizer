<div class="bar-graph">
  <?php foreach ($values as $index => $value) : ?>
    <div class="bar-graph-column" style="height: <?= $value ?>%; animation-delay: <?= 0.1 + 0.1 * $index ?>s;"></div>
  <?php endforeach; ?>
</div>
