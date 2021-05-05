<?php

$logged = false;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['LOGGED'])) {
    $logged = true;
} else {
    $logged = false;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="Antonio Mihaes, Stoleriu Daniel">
    <link rel="shortcut icon" type="image/png" href="/assets/icon.png">
    <link rel="stylesheet" href="styles.css">
    <title>Obesity Prevalence Visualizer</title>
  </head>
  <body>
    <header>
      <img src="/assets/icon.svg" class="logo">
      <nav>
        <div id="nav-menu-thumb"></div>
        <input id="nav-menu-toggle" type="checkbox">
          <?php
            if ($logged == true) {
                echo '<ul>';
                  echo '<li><a class="nav-link" href="/">Home</a></li>';
                  echo '<li><a class="nav-link" href="/who">Who</a></li>';
                  echo '<li><a class="nav-link" href="/eurostat">Eurostat</a></li>';
                  echo '<li><a class="nav-link" href="/administration">Administration</a></li>';
                  echo '<li><a class="nav-link" href="/logout">Logout</a></li>';
                echo '</ul>';
            } else {
                echo '<ul>';
                  echo '<li><a class="nav-link" href="/">Home</a></li>';
                  echo '<li><a class="nav-link" href="/who">Who</a></li>';
                  echo '<li><a class="nav-link" href="/eurostat">Eurostat</a></li>';
                  echo '<li><a class="nav-link" href="/login">Login</a></li>';
                echo '</ul>';
            }
            ?>
      </nav>
    </header>
    <main>
