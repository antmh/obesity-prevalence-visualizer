<ol class="order-group">
  <?php foreach (range(0, count($orderGroup['items']) - 1) as $index) : ?>
    <li class="order-item">
      <input class="order-item-input" type="checkbox" name="order[<?= $index ?>]" value="descending">
      <span class="order-item-mark"></span>
      <div class="select-wrapper">
        <select name="order-item[<?= $index ?>]">
          <?php foreach (range(0, count($orderGroup['items']) - 1) as $selectIndex) : ?>
            <option value="<?= $orderGroup['items'][$index] ?>" <?= $index === $selectIndex ? "selected" : "" ?>><?= $orderGroup['items'][$selectIndex] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </li>
  <?php endforeach; ?>
</ol>
