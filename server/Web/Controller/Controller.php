<?php


namespace Web\Controller;


interface Controller {

    public static function action(string $action, string $controller, array $params = null): string;

}
