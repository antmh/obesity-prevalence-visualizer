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
          <ul>
            <li><a class="nav-link" href="/">Home</a></li>
            <li><a class="nav-link" href="/who">Who</a></li>
            <li><a class="nav-link" href="/eurostat">Eurostat</a></li>
            <?php if ($loggedIn): ?>
              <li><a class="nav-link" href="/administration">Administration</a></li>
              <li><a class="nav-link" href="/process-logout">Logout</a></li>
            <?php else: ?>
              <li><a class="nav-link" href="/login">Login</a></li>
            <?php endif; ?>
          </ul>
      </nav>
    </header>
    <main>
