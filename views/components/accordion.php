<ul class="accordion">
  <?php foreach ($accordion as $category) : ?>
    <li class="accordion-category">
      <input id="<?= $category['name'] ?>" type="checkbox">
      <label class="accordion-category-title" for="<?= $category['name'] ?>">
        <?= $category['name'] ?>
      </label>
      <ul class="accordion-category-items">
        <?php foreach ($category['items'] as $item) : ?>
          <li>
            <label class="accordion-item"><?= $item ?>
              <input class="accordion-item-radio" type="radio" value="<?= $item ?>" name="<?= $category['name'] ?>">
              <span class="accordion-item-mark"></span>
            <label/>
          </li>
        <?php endforeach; ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>
