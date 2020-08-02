<?php

require_once('Partials/loginHeader.php'); ?>
  <div class="grid-container flex-center wrap">
    <div class="login-card-wide mdl-card mdl-shadow--2dp">
      <div class="mdl-card__title">
        <h2 class="mdl-card__title-text mdl-color-text--primary-dark">LOGIN</h2>
      </div>
      <div class="mdl-card__supporting-text login-card__text">
        <div class="login--center">
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="username" pattern="^(?!\s*$).+" autofocus required>
            <label class="mdl-textfield__label" for="username">Username</label>
            <span class="mdl-textfield__error">Username is required!</span>
          </div>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" id="password" required>
            <label class="mdl-textfield__label" for="password">Password</label>
            <span class="mdl-textfield__error">Password is required!</span>
          </div>
        </div>
        <div class="mdl-card--border flex-center">
          <button id="login-button"
                  class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary flex-1"
                  onclick="login()"
          >
            Login
          </button>
        </div>
      </div>
    </div>
  </div>

  <script type="module" src="assets/JS/login.js"></script>
<?php
require_once('Partials/footer.php');
