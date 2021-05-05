<div class="login-container">
  <div class="login-header">
    <h2>Admin</h2>
  </div>
  <form class="login-form" method="post"> <!--action="administration" -->
    <div class="input-login-group">
      <label class="login-label" for="email">Email</label>
      <input class="login-input" type="email" name="email" id="email">
    </div>
    <div class="input-login-group">
      <label class="login-label" for="password">Password</label>
      <input class="login-input" type="password" name="password" id="password">
    </div>
      <?php

      if (isset($_SESSION['ERR'])) {
          echo '<div class="alert alert-danger" role="alert">' . $_SESSION['ERR'] . '</div>';
          unset($_SESSION['ERR']);
      }
        ?>
    <div class="input-login-group">
      <input class="button login-button" type="submit" name="verifyLogin" value="Login">
    </div>
  </form>
</div>

