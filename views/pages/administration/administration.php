<div class="administration-cards-container">
    <div class="administration-card-wrapper">
        <h1>Data</h1>
        <div class="administration-card">
            <div class="data-cards-container">
                <div class="data-card-wrapper">
                    <div class="data-card">
                        <h3>World Health Organization</h3>
                        <a class="button" href="/administrationWho">Update now</a>
                    </div>
                </div>
                <div class="data-card-wrapper">
                    <div class="data-card">
                        <h3>Eurostat</h3>
                        <a class="button" href="/administrationEurostat">Update now</a>
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
                        <form action="post">
                            <h3>Change email</h3>
                            <div class="input-admin-group">
                                <label class="account-label" for="password">Password:</label>
                                <input class="account-input" type="password" name="currentPassword" id="password">
                            </div>
                            <div class="input-admin-group">
                                <label class="account-label" for="email">Email:</label>
                                <input class="account-input" type="email" name="newEmail" id="email">
                            </div>
                            <div>
                                <input class="button administration-button" type="submit" name="updateEmail" value="Change email" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="account-card-wrapper">
                    <div class="account-card">
                        <form action="post">
                            <h3>Change password</h3>
                            <div class="input-admin-group">
                                <label class="account-label" for="oldpassword">Password:</label>
                                <input class="account-input" type="password" name="oldPassword" id="oldPassword">
                            </div>
                            <div class="input-admin-group">
                                <label class="account-label" for="newpassword">New password:</label>
                                <input class="account-input" type="password" name="newPassword" id="newPassword">
                            </div>
                            <div class="input-admin-group">
                                <label class="account-label" for="newpassword">New password:</label>
                                <input class="account-input" type="password" name="confirmPassword" id="newPassword">
                            </div>
                            <div>
                                <input class="button administration-button" type="submit" name="updatePassword" value="Change password" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
