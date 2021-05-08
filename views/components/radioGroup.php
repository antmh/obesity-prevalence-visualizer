<ul class="radio-group">
  <?php foreach (range(0, count($radioGroup['items']) - 1) as $index) : ?>
    <li>
      <label class="radio-item"><?= $radioGroup['items'][$index] ?>
        <input class="radio-item-input" type="radio" value="<?= $radioGroup['items'][$index] ?>" name="<?= $radioGroup['name'] ?>" <?= key_exists('default', $radioGroup) && $index === 0 ? "checked" : "" ?>>
        <span class="radio-item-mark"></span>
      </label>
    </li>
  <?php endforeach; ?>
</ul>
