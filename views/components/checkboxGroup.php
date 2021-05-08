<ul class="checkbox-group">
  <?php foreach ($checkboxGroup['items'] as $item) : ?>
    <li>
      <label class="checkbox-item"><?= $item ?>
        <input class="checkbox-item-input" type="checkbox" name="checked[<?= $item ?>]">
        <span class="checkbox-item-mark"></span>
      </label>
    </li>
  <?php endforeach; ?>
</ul>
