<form class="select-form">
  <fieldset>
    <legend><?= $radioGroup['name'] ?></legend>
    <?php include('views/components/radioGroup.php'); ?>
  </fieldset>
  <fieldset>
    <legend><?= $checkboxGroup['name'] ?></legend>
    <?php include('views/components/checkboxGroup.php'); ?>
  </fieldset>
  <fieldset>
    <legend><?= $orderGroup['name'] ?></legend>
    <?php include('views/components/orderGroup.php'); ?>
  </fieldset>
  <fieldset>
    <legend>Filter</legend>
    <?php include('views/components/accordion.php'); ?>
  </fieldset>
  <fieldset>
    <legend>Export</legend>
    <?php $radioGroup = ['name' => 'export', 'items' => ['CSV', 'SVG', 'PNG']] ?>
    <?php include('views/components/radioGroup.php'); ?>
  </fieldset>
  <input class="button" type="submit" value="Submit">
</form>
<?php
if (isset($table)) {
    include('views/components/table.php');
} elseif (isset($barChart)) {
    include('views/components/barChart.php');
} elseif (isset($lineChart)) {
    include('views/components/lineChart.php');
}
?>
