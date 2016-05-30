<?php

namespace system\controllers;

use system\components\App;
use system\components\Controller;
use system\components\Request;
use system\components\Url;
use system\components\Validator;
use system\models\User;

class IndexController extends Controller
{

    public function lang($code)
    {
        $_SESSION['lang'] = $code;
        header('Location: ' . Url::back());
    }

    public function index()
    {
        if ($this->guestOnly() === true) {
            return $this->render('index');
        }
    }

    public function login()
    {
        $this->guestOnly();

        if (Request::isPost()) {

            $model = new User();
            $model->loadData();

            $user = $model->orWhere([
                'login' => $model->login,
                'email' => $model->login,
            ]);

            if (count($user)) {

                $user = $user[0];

                if (password_verify($model->password, $user['password'])) {
                    $model->login();
                    return header('Location: ' . Url::to("user/{$user['login']}"));
                }

                return $this->render('index', [
                    'passwordError' => true,
                    'user' => $user,
                ]);

            } else {
                return $this->render('index', [
                    'loginError' => true,
                ]);
            }
        }

        return $this->render('index');
    }

    public function registration()
    {
        $this->guestOnly();

        $user = new User();

        if (Request::isPost()) {
            $validator = $user->getValidator();
            $user->loadData();

            if ($validator->validate() === true) {

                $user->create();
                $user->login();

                header('Location: ' . Url::to("user/{$user->login}"));
            } else {
                return $this->render('index', [
                    'model' => $user,
                    'errors' => $validator->errors,
                ]);
            }
        }

        return $this->render('index', [
            'model' => $user,
        ]);

    }

    public function user($login)
    {
        $model = (new User())->andWhere([
            'login' => $login,
        ]);

        if (!count($model)) {
            return $this->notFound();
        }

        $user = new User();
        $user->loadData($model[0]);

        return $this->render('user', [
            'model' => $user,
        ]);
    }

    public function logout()
    {
        $user = new User();
        $user->logout();
        header('Location: ' . Url::to());
    }

    public function guestOnly()
    {
        if (User::isGuest() === false) {
            $user = (new User)->andWhere([
                'id' => $_SESSION['user_id'],
            ])[0];

            return header('Location: ' . Url::to('user/' . $user['login']));
        }

        return true;
    }

    public function ajaxLogin($login)
    {
        $model = new User();
        $result = $model->andWhere([
            'login' => $login,
        ]);

        $count = count($result);

        return $this->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    public function ajaxEmail($email)
    {
        $model = new User();
        $result = $model->andWhere([
            'email' => $email,
        ]);

        $count = count($result);

        return $this->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}