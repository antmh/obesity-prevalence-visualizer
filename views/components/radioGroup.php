<ul class="radio-group">
  <?php foreach ($radioGroup['items'] as $item) : ?>
    <li>
      <label class="radio-item"><?= $item ?>
        <input class="radio-item-input" type="radio" value="<?= $item ?>" name="<?= $radioGroup['name'] ?>">
        <span class="radio-item-mark"></span>
      </label>
    </li>
  <?php endforeach; ?>
</ul>
