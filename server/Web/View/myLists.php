<?php

use Web\Controller\ListController;
use Web\Controller\UserController;

require_once('Partials/header.php');
UserController::getInstance()->routingGuardHelpSeeker();
$shoppingList = ListController::getInstance()->getAllShoppingLists();
?>

  <div class="mdl-grid">
    <div
        class="dummy-card shop-card-square mdl mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-phone justify-center align-center">
      <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--primary"
              onclick="newList()" title="Add new list"
      >
        <i class="material-icons">add</i>
      </button>
    </div>
      <?php foreach ($shoppingList as $list) : ?>
        <div
            class="shop-card-square mdl mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-phone">
          <div class="mdl-card__title mdl-card--expand">
            <h2 class="mdl-card__title-text"> <?php echo $list->getName() ?></h2>
          </div>
          <div class="mdl-card__menu">
              <?php if ($list->isNew()) { ?><span class="mdl-chip mdl-color--red-600"><span
                    class="mdl-chip__text mdl-color-text--white">New</span></span> <?php } ?>
              <?php if ($list->isUnpublished()) { ?> <span class="mdl-chip mdl-color--grey-600"><span
                    class="mdl-chip__text mdl-color-text--white">Unpublished</span></span><?php } ?>
              <?php if ($list->isInProgress()) { ?><span class="mdl-chip mdl-color--yellow-600"><span
                    class="mdl-chip__text mdl-color-text--black">In Progress</span></span> <?php } ?>
              <?php if ($list->isDone()) { ?> <span class="mdl-chip mdl-color--light-green-600"><span
                    class="mdl-chip__text mdl-color-text--white">Done</span></span><?php } ?>
          </div>
          <div class="mdl-card__actions mdl-card--border mdl-grid flex align-center card-bottom">
              <?php if ($list->isDone()) { ?>
                <div class="mdl-cell align-center flex mdl-cell--8-col">
                  <div id="tt1" class="icon material-icons mdl-color-text--primary hoverable" title="Owner">portrait</div>&nbsp;
                  <p class="mdl-card__subtitle-text mdl-color-text--primary"><?php echo UserController::getInstance()->getUserName($list->getOwnerId()); ?></p>
                  <span class="mdl-layout-spacer"></span>
                  <div id="tt1" class="material-icons mdl-color-text--primary hoverable" title="Volunteer doing the shopping">
                    local_grocery_store
                  </div>&nbsp;
                  <p class="mdl-card__subtitle-text mdl-color-text--primary"><?php echo UserController::getInstance()->getUserName($list->getVolunteerId()); ?></p>
                  <span class="mdl-layout-spacer"></span>
                  <div id="tt1" class="icon material-icons mdl-color-text--primary hoverable"
                       title="Due date <?php echo date_format($list->getDueDate(), 'd.m.Y') ?>">schedule
                  </div>
                  <p class="mdl-card__subtitle-text mdl-color-text--primary mr-12">
                    &nbsp; <?php echo date_format($list->getDueDate(), 'd.m.Y'); ?></p>
                </div>
                <div class="mdl-cell mdl-grid flex-end">
                  <div id="tt1" class="icon material-icons mdl-color-text--primary hoverable"
                       title="Total costs">euro
                  </div>&nbsp;
                  <p class="mdl-card__subtitle-text mdl-color-text--primary mdl-typography--font-bold"><?php echo $list->getTotal(); ?></p>
                </div>
              <?php } ?>
              <?php if ($list->isInProgress()) { ?>
                <div class="mdl-cell align-center flex mdl-cell--8-col">
                  <div id="tt1" class="icon material-icons mdl-color-text--primary hoverable" title="Owner">portrait</div>&nbsp;
                  <p class="mdl-card__subtitle-text mdl-color-text--primary"><?php echo UserController::getInstance()->getUserName($list->getOwnerId()); ?></p>
                  <span class="mdl-layout-spacer"></span>
                  <div id="tt1" class="material-icons mdl-color-text--primary hoverable" title="Volunteer doing the shopping">
                    local_grocery_store
                  </div>&nbsp;
                  <p class="mdl-card__subtitle-text mdl-color-text--primary"><?php echo UserController::getInstance()->getUserName($list->getVolunteerId()); ?></p>
                  <span class="mdl-layout-spacer"></span>
                  <div id="tt1" class="icon material-icons mdl-color-text--primary hoverable"
                       title="Due date <?php echo date_format($list->getDueDate(), 'd.m.Y') ?>">schedule
                  </div>
                  <p class="mdl-card__subtitle-text mdl-color-text--primary mr-12">
                    &nbsp; <?php echo date_format($list->getDueDate(), 'd.m.Y'); ?></p>
                </div>
              <?php } ?>
              <?php if ($list->isUnpublished() || $list->isNew()) { ?>
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
                <div class="flex-1 flex-end">
                    <a onclick="editList(<?php echo $list->getId()?>)"
                       class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--primary"
                       title="Edit Shopping List"> Edit
                    </a>
                    <a onclick="deleteList(<?php echo $list->getId()?>)"
                       class="mdl-button mdl-color-text--red-500 mdl-js-button mdl-js-ripple-effect"
                       title="Delete Shopping List">
                      Delete
                    </a>
                </div>
              <?php } ?>
          </div>
        </div>
      <?php endforeach; ?>

  </div>

  <script type="module" src="assets/JS/my-list.js"></script>
<?php
require_once('Partials/footer.php');
