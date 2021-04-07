<table>
  <tr>
    <?php foreach ($table->getHeader() as $headerCell) : ?>
      <th><?= $headerCell ?></th>
    <?php endforeach; ?>
  </tr>
  <?php foreach ($table->getBody() as $row) : ?>
    <tr>
      <?php foreach (array_values($row) as $cell) : ?>
        <td><?= $cell ?></td>
      <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
</table>
