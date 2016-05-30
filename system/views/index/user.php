<?php

use system\components\App;
use system\components\Url;

/**
 * @var $this \League\Plates\Engine
 * @var $model \system\models\User
 * @var $errors array
 */

$title = $this->e($model->login);

$this->layout('layout', [
    'title' => $title
]);

?>

    <div class="container">

        <div class="center-block">

            <div class="card card-container card-login">
                <img id="profile-img" class="profile-img-card" src="<?= \system\components\Thumbnail::getThumb($model->getAvatar(), 96, 96) ?>" />

                <div class="text-center">
                    <?= $this->e($model->first_name) ?>
                    "<?= $this->e($model->login) ?>"
                    <?= $this->e($model->last_name) ?>
                </div>

                <hr>

                <?php if (!empty($model->information)): ?>
                    <?= $this->e($model->information) ?>
                    <hr>
                <?php endif ?>

                <a href="<?= Url::to('logout') ?>" class="btn btn-danger">
                    <?= App::t('app', 'Logout') ?>
                </a>
            </div><!-- /card-container -->

        </div>

    </div><!-- /container -->

<?php $this->start('body') ?>

<?php $this->stop() ?>