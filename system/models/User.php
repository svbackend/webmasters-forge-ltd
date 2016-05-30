<?php

namespace system\models;

use system\components\App;
use system\components\Model;
use system\components\Url;
use system\components\Validator;

/**
 * Class User
 * @property integer $id
 * @property string $information
 * @property string $email
 * @property string $password
 * @property string $login
 * @property $picture
 * @property string $first_name
 * @property string $last_name
 * @property integer $updated_at
 * @property integer $created_at
 * @property string $access_token
 */
class User extends Model
{
    public $table = 'users';

    const GENDER_MALE = "m";
    const GENDER_FEMALE = "w";

    public function __construct()
    {
        return parent::__construct();
    }

    public function beforeSave()
    {
        if ($this->isNewRecord()) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $this->created_at = time();
        }

        $this->updated_at = time();
        parent::beforeSave();
    }

    public function afterSave()
    {
        if (!$this->picture['error']) {
            $this->uploadAvatar();
        }

        parent::afterSave();
    }

    public function uploadAvatar()
    {
        $targetFile = Url::toPath("files/avatars/{$this->id}.jpg");
        move_uploaded_file($this->picture['tmp_name'], $targetFile);
    }

    public function getAvatar()
    {
        if (file_exists(Url::toPath("files/avatars/{$this->id}.jpg"))) {
            return "files/avatars/{$this->id}.jpg";
        } else {
            return "files/avatars/default.png";
        }
    }

    public function login()
    {
        $_SESSION['user_id'] = $this->id;
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
    }

    public static function isGuest()
    {
        return !isset($_SESSION['user_id']);
    }

    public function getValidator()
    {
        $validator = new Validator();

        $validator->addRule('first_name', [
            'name' => 'string',
            'min' => 2,
            'max' => 30,
            'message' => App::t('validation', 'First Name length'),
        ]);

        $validator->addRule('last_name', [
            'name' => 'string',
            'min' => 2,
            'max' => 30,
            'message' => App::t('validation', 'Last Name length'),
        ]);

        $validator->addRule('information', [
            'name' => 'string',
            'min' => 0,
            'max' => 255,
            'message' => App::t('validation', 'Information length'),
        ]);

        $validator->addRule('email', [
            'name' => 'email',
            'message' => App::t('validation', 'Email email'),
        ]);

        $validator->addRule('email', [
            'name' => 'unique',
            'fieldName' => 'email',
            'message' => App::t('validation', 'Email unique'),
        ]);

        $validator->addRule('password', [
            'name' => 'string',
            'min' => 4,
            'max' => 255,
            'message' => App::t('validation', 'Password length'),
        ]);

        $validator->addRule('login', [
            'name' => 'login',
            'min' => 3,
            'max' => 50,
            'message' => App::t('validation', 'Login length'),
        ]);

        $validator->addRule('login', [
            'name' => 'unique',
            'fieldName' => 'login',
            'message' => App::t('validation', 'Login unique'),
        ]);

        $validator->addRule('picture', [
            'name' => 'file',
            'ext' => ['jpg', 'jpeg', 'png', 'gif'],
            'message' => App::t('validation', 'File ext'),
        ]);

        return $validator;
    }
}