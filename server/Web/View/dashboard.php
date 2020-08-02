<?php

use Core\Domain\Role\RoleType;
use Web\Controller\UserController;

$user = UserController::getInstance()->getAuthenticatedUser();

if ($user->hasAccess(RoleType::HELP_SEEKER)) {
    require_once('myLists.php');
} else if ($user->hasAccess(RoleType::VOLUNTEER)) {
    require_once('newLists.php');
}

