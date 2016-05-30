<?php

use system\controllers\IndexController;

return [
    // Ajax validation
    '/ajax/login/(\w+)' => function ($login) {
        (new IndexController)->ajaxLogin($login);
    },
    '/ajax/email/(.*)' => function ($email) {
        (new IndexController)->ajaxEmail($email);
    },
    // User profile
    '/user/(\w+)' => function ($login) {
        (new IndexController)->user($login);
    },
    // Language switcher
    '/lang/(\w+)' => function ($lang) {
        (new IndexController)->lang($lang);
    },
    // After submit form
    '/login' => function () {
        (new IndexController)->login();
    },
    '/registration' => function () {
        (new IndexController)->registration();
    },
    '/logout' => function () {
        (new IndexController)->logout();
    },
    // Main page
    '/index.php|/' => function () {
        (new IndexController)->index();
    },
];