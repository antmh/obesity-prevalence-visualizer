<ul class="accordion">
  <?php foreach ($accordion as $radioGroup) : ?>
    <li class="accordion-category">
      <input id="<?= urlencode($radioGroup['name']) ?>" type="checkbox">
      <label class="accordion-category-title" for="<?= urlencode($radioGroup['name']) ?>">
        <?= $radioGroup['name'] ?>
      </label>
      <?php include('views/components/radioGroup.php'); ?>
    </li>
  <?php endforeach; ?>
</ul>
