<?php


use Web\Controller\ListController;
use Web\Controller\UserController;

require_once('Partials/volunteerHeader.php');
UserController::getInstance()->routingGuardVolunteer();
$user = UserController::getInstance()->getAuthenticatedUser();
$shoppingList = ListController::getInstance()->getAllNewShoppingLists();
?>

  <div class="mdl-grid">
      <?php foreach ($shoppingList as $list): ?>
        <div
            class="shop-card-square mdl mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-phone">
          <div class="mdl-card__title mdl-card--expand">
            <h2 class="mdl-card__title-text"> <?php echo $list->getName() ?></h2>
          </div>
          <div class="mdl-card__menu">
              <span class="mdl-chip mdl-color--red-600">
                  <span class="mdl-chip__text mdl-color-text--white">New</span>
              </span>
          </div>
          <div class="mdl-card__actions mdl-card--border mdl-grid">
            <div class="mdl-cell flex justify-center align-center mdl-cell--6-col">
              <div id="tt1" class="icon material-icons mdl-color-text--primary hoverable" title="Owner">portrait</div>&nbsp;
              <p class="mdl-card__subtitle-text mdl-color-text--primary"><?php echo UserController::getInstance()->getUserName($list->getOwnerId()); ?></p>
              <span class="mdl-layout-spacer"></span>
              <div id="tt1" class="icon material-icons mdl-color-text--primary hoverable"
                   title="Due date <?php echo date_format($list->getDueDate(), 'd.m.Y') ?>">schedule
              </div>
              <p class="mdl-card__subtitle-text mdl-color-text--primary mr-12">
                &nbsp; <?php echo date_format($list->getDueDate(), 'd.m.Y'); ?></p>
            </div>
            <div class="mdl-cell mdl-cell--6-col align-center flex-end">
              <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                      onclick="takeOverList(<?php echo $list->getId() ?>, <?php echo $user->getId() ?>)">
                Take Over
              </button>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>

  <script type="module" src="assets/JS/new-list.js"></script>

<?php require_once('Partials/footer.php');
