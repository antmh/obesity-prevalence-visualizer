<table id="visualization">
  <tr>
    <?php foreach ($table->getHeader() as $headerCell): ?>
      <th><?= $headerCell ?></th>
    <?php endforeach; ?>
  </tr>
  <?php foreach ($table->getBody() as $row): ?>
    <tr>
      <?php foreach (array_values($row) as $index => $cell): ?>
        <?php if($table->getHeader()[$index] !== 'Rowid'): ?>
          <td><?= $cell ?></td>
        <?php endif; ?>
      <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
</table>