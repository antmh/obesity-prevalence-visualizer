<ul class="accordion">
  <?php foreach ($accordion as $category) : ?>
    <li class="accordion-category">
      <input id="<?= $category['name'] ?>" type="radio" name="accordion-toggle" checked="">
      <label class="accordion-category-title" for="<?= $category['name'] ?>">
        <?= $category['name'] ?>
      </label>
      <ul class="accordion-category-items">
        <?php foreach ($category['items'] as $item) : ?>
          <li>
            <?= $item ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>
