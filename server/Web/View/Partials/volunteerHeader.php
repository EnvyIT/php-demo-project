<?php

use Common\Router\Route;
use Common\Util\StringUtils;
use Core\Domain\Role\RoleType;
use Web\Controller\UserController;

$user = UserController::getInstance()->getAuthenticatedUser();
if (isset($_GET["errors"])) {
    $errors = unserialize(urldecode($_GET["errors"]));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="A shopping list app for high-risk patients during COVID-19 crisis.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <title>Shopping List</title>

  <link rel="shortcut icon" href="assets/media/favicon.png">
  <link rel="stylesheet" href="assets/material.min.css">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href="assets/main.css" rel="stylesheet">
  <script src="assets/JS/jquery-3.5.0.min.js"></script>
</head>

<body>

<div class="mdl-layout mdl-js-layout">
  <header class="mdl-layout__header mdl-layout__header--scroll">
    <div class="mdl-layout__header-row">
      <span class="mdl-layout-title">Shopping List <img src="assets/media/favicon.png" height="25px" width="25px"
                                                        alt="logo "></span>
      <div class="mdl-layout-spacer"></div>
      <nav class="mdl-navigation">
        <div class="mdl-grid flex-center">
          <span>Hello, <?php echo StringUtils::escape($user->getFirstName()) ?> !</span>
          <div class="mdl-layout-spacer"></div>
          <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
            <i class="material-icons" role="presentation">arrow_drop_down</i>
          </button>
          <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
            <li class="mdl-menu__item">
              <form method="post"
                    action="<?php echo UserController::action(UserController::LOGOUT, UserController::CONTROLLER); ?>">
                <input class="mdl-button mdl-js-button mdl-button--raised mdl-button--primary" role="button" type="submit"
                       value="Logout"/>
              </form>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>
  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title">Shopping List <img src="assets/media/favicon.png" height="25px" width="25px"
                                                      alt="logo "></span>
    <nav class="mdl-navigation">
        <?php if ($user->hasAccess(RoleType::VOLUNTEER)) { ?>
          <a <?php if ($view === Route::NEW_LISTS) { ?>class="mdl-tabs__tab is-active"<?php } ?> class="mdl-tabs__tab"
             href="index.php?view=<?php echo Route::NEW_LISTS ?>">New</a>
          <a <?php if ($view === Route::IN_PROGRESS) { ?>class="mdl-tabs__tab is-active"<?php } ?> class="mdl-tabs__tab"
             href="index.php?view=<?php echo Route::IN_PROGRESS ?>">In Progress</a>
          <a <?php if ($view === Route::DONE_LISTS) { ?>class="mdl-tabs__tab is-active"<?php } ?> class="mdl-tabs__tab"
             href="index.php?view=<?php echo Route::DONE_LISTS ?>">Done</a>
        <?php } ?>
      <form method="post" class=" mdl-navigation__link active"
            action="<?php echo UserController::action(UserController::LOGOUT, UserController::CONTROLLER); ?>">
        <input class="mdl-button mdl-js-button mdl-button--raised mdl-button--primary full-width" role="button" type="submit"
               value="Logout"/>
      </form>
    </nav>
  </div>
    <?php if ($user->hasAccess(RoleType::VOLUNTEER)) { ?>
      <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
        <div class="mdl-tabs__tab-bar">
          <a <?php if ($view === Route::NEW_LISTS) { ?>class="mdl-tabs__tab is-active"<?php } ?> class="mdl-tabs__tab"
             href="index.php?view=<?php echo Route::NEW_LISTS ?>">New</a>
          <a <?php if ($view === Route::IN_PROGRESS) { ?>class="mdl-tabs__tab is-active"<?php } ?> class="mdl-tabs__tab"
             href="index.php?view=<?php echo Route::IN_PROGRESS ?>">In Progress</a>
          <a <?php if ($view === Route::DONE_LISTS) { ?>class="mdl-tabs__tab is-active"<?php } ?> class="mdl-tabs__tab"
             href="index.php?view=<?php echo Route::DONE_LISTS ?>">Done</a>
        </div>
      </div>
    <?php } ?>

  <main class="mdl-layout__content mdl-color--grey-100">
