<div class="administration-cards-container">
  <div class="administration-card-wrapper">
    <h1>Data</h1>
    <div class="administration-card">
      <div class="data-cards-container">
        <div class="data-card-wrapper">
          <div class="data-card">
            <h3>World Health Organization</h3>
            <a class="button" href="/administration/who">Update now</a>
          </div>
        </div>
        <div class="data-card-wrapper">
          <div class="data-card">
            <h3>Eurostat</h3>
            <a class="button" href="/administration/eurostat">Update now</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="administration-card-wrapper">
    <h1>Account</h1>
    <div class="administration-card">
      <div class="account-cards-container">
        <div class="account-card-wrapper">
          <div class="account-card">
            <form id="username-form" action="post">
              <div class="input-admin-group">
                <label class="account-label" for="password">Password:</label>
                <input class="account-input" type="password" name="password" id="password">
              </div>
              <div class="input-admin-group">
                <label class="account-label" for="username">Username:</label>
                <input class="account-input" type="username" name="username" id="username">
              </div>
              <input class="button administration-button" type="submit" value="Change username">
              <p class="error-message" id="username-error-message">Invalid password</p>
            </form>
          </div>
        </div>
        <div class="account-card-wrapper">
          <div class="account-card">
            <form id="password-form" action="post">
              <div class="input-admin-group">
                <label class="account-label" for="old-password">Old password:</label>
                <input class="account-input" type="password" id="old-password" name="old-password" id="old-password">
              </div>
              <div class="input-admin-group">
                <label class="account-label" for="new-password">New password:</label>
                <input class="account-input" type="password" id="new-password" name="new-password" id="new-password">
              </div>
              <input class="button administration-button" type="submit" value="Change password">
              <p class="error-message" id="password-error-message">Invalid password</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/js/account.js"></script>
