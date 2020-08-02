<?php

namespace Common\Router;

class Router {

    public static function redirect(string $page = Route::LOGIN) {
        header("Location: ?view=" . $page);
        exit();
    }

}
