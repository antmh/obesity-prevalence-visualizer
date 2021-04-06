<table>
  <tr>
    <?php foreach (array_keys($values[0]) as $header) : ?>
      <th><?= ucfirst($header) ?></th>
    <?php endforeach; ?>
  </tr>
  <?php foreach ($values as $row) : ?>
    <tr>
      <?php foreach (array_values($row) as $cell) : ?>
        <td><?= $cell ?></td>
      <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
</table>
