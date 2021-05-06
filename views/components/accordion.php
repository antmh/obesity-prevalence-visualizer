<ul class="accordion">
  <?php foreach ($accordion as $radioGroup) : ?>
    <li class="accordion-category">
      <input id="<?= $radioGroup['name'] ?>" type="checkbox">
      <label class="accordion-category-title" for="<?= $radioGroup['name'] ?>">
        <?= $radioGroup['name'] ?>
      </label>
      <?php include('views/components/radioGroup.php'); ?>
    </li>
  <?php endforeach; ?>
</ul>
