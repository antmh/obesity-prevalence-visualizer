<table id="visualization">
  <tr>
    <?php foreach ($table->getHeader() as $headerCell): ?>
      <?php if($headerCell !== 'Rowid'): ?>
        <th><?= $headerCell ?></th>
      <?php endif; ?>
    <?php endforeach; ?>
  </tr>
  <?php foreach ($table->getBody() as $row): ?>
    <tr>
      <?php foreach (array_values($row) as $index => $cell): ?>
        <?php if($table->getHeader()[$index] !== 'Rowid'): ?>
          <td><?= $cell ?></td>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php if ($table->isDeletable()): ?>
        <td class="td-delete">
          <a href=<?php echo 'delete?row=' . $row[array_search('Rowid',$table->getHeader())] . '&' . $param ;?>> <img src='../../assets/delete.svg' width='23' height='23'></a>
        </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
</table>
