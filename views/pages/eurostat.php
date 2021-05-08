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
  <input class="button" type="submit" value="Submit">
</form>
<?php include('views/components/barChart.php'); ?>
