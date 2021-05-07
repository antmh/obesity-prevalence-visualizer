<form>
  <h2><?= $radioGroup['name'] ?></h2>
  <?php include('views/components/radioGroup.php'); ?>
  <h2><?= $checkboxGroup['name'] ?></h2>
  <?php include('views/components/checkboxGroup.php'); ?>
  <h2><?= $orderGroup['name'] ?></h2>
  <?php include('views/components/orderGroup.php'); ?>
  <h2>Filter</h2>
  <?php include('views/components/accordion.php'); ?>
  <input class="button" type="submit" value="Submit">
</form>
<?php include('views/components/barChart.php'); ?>
