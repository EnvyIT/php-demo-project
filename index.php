<?php


use Common\Router\Param;
use Web\Controller\ListController;
use Web\Controller\UserController;

require_once('inc/bootstrap.inc.php');

$view = 'login';
$viewDir = 'server/Web/View/';

if (isset($_REQUEST[Param::VIEW]) && file_exists($viewDir . $_REQUEST[Param::VIEW] . '.php')) {
    $view = $_REQUEST[Param::VIEW];
}

$userControllerPost = $_REQUEST[UserController::CONTROLLER] ?? null;
$listControllerPost = $_REQUEST[ListController::CONTROLLER] ?? null;


if ($userControllerPost != null) {
    UserController::getInstance()->post();
}
if ($listControllerPost != null) {
    ListController::getInstance()->post();
}

require_once($viewDir . $view . '.php');
