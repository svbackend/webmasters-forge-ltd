<?php

use system\components\App;
use system\components\Url;

/**
 * @var $this \League\Plates\Engine
 * @var $model \system\models\User
 * @var $errors array
 */

$title = App::t('app', 'Login & Registration test app');

$this->layout('layout', [
    'title' => $title
]);

$thumb = new \system\components\Thumbnail();

?>

    <div class="container">

        <div class="col-md-4">

            <div class="card card-container card-login">
                <img id="profile-img" class="profile-img-card"
                     src="<?= $thumb->image('files/avatars/default.png')->crop(128, 128) ?>"/>
                <form action="<?= Url::to('login') ?>" class="form signin" method="post">

                    <div class="form-group">
                        <label for="loginInputEmail"><?= App::t('app', 'Login') ?></label>
                        <input type="text" name="login" class="form-control" id="loginInputEmail"
                               value="<?= isset($user['login']) ? $this->e($user['login']) : '' ?>"
                               placeholder="<?= App::t('app', 'Login placeholder') ?>" required aria-required="true">

                        <small class="help-block">
                            <?php if (isset($loginError)): ?>
                                <div class="validationErrorMessage">
                                    <?= App::t('validation', 'User not exists') ?>
                                </div>
                            <?php endif ?>
                            <?= App::t('app', 'Login hint') ?>
                        </small>
                    </div>

                    <label for="loginInputPassword">
                        <?= App::t('app', 'Password') ?>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="loginInputPassword"
                               placeholder="<?= App::t('app', 'Password') ?>" required aria-required="true">
                        <span class="input-group-addon toggle-password" id="login-toggle-password"
                              title="<?= App::t('app', 'Show password') ?>">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </span>
                    </div>

                    <small class="help-block">
                        <?php if (isset($passwordError)): ?>
                            <div class="validationErrorMessage">
                                <?= App::t('validation', 'Invalid password') ?>
                            </div>
                        <?php endif ?>
                    </small>

                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">
                        <?= App::t('app', 'Sign in') ?>
                    </button>
                </form><!-- /form -->
            </div><!-- /card-container -->

        </div>

        <div class="col-md-8">

            <div class="card card-container card-registration">

                <div class="card-title">
                    <?= App::t('app', 'Registration') ?>

                    <div class="pull-right">
                        <div class="btn-group">
                            <a class="btn btn-primary" href="<?= Url::to('lang/ru')?>">
                                <?= App::t('app', 'Russian')?>
                            </a>
                            <a class="btn btn-success" href="<?= Url::to('lang/en')?>">
                                <?= App::t('app', 'English')?>
                            </a>
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>

                <form action="<?= Url::to('registration') ?>" method="post" class="form signup" id="registrationForm"
                      enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputFirstName" class="required">
                                <?= App::t('app', 'First Name') ?>
                            </label>
                            <input type="text" id="inputFirstName" class="form-control" required
                                   value="<?= isset($model->first_name) ? $this->e($model->first_name) : '' ?>"
                                   name="first_name"
                                   placeholder="<?= App::t('app', 'Jane') ?>">

                            <?php if (isset($errors['first_name']) && $error = $errors['first_name'][0]): ?>
                                <div id="errorsinputFirstName">
                                    <div class="validationErrorMessage">
                                        <?= $error ?>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                        <div class="col-md-6">
                            <label for="inputLastName" class="required">
                                <?= App::t('app', 'Last Name') ?>
                            </label>
                            <input type="text" id="inputLastName" class="form-control" required
                                   value="<?= isset($model->last_name) ? $this->e($model->last_name) : '' ?>"
                                   name="last_name"
                                   placeholder="<?= App::t('app', 'Doe') ?>">

                            <?php if (isset($errors['last_name']) && $error = $errors['last_name'][0]): ?>
                                <div id="errorsinputLastName">
                                    <div class="validationErrorMessage">
                                        <?= $error ?>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>

                    <label id="inputGender" class="required">
                        <?= App::t('app', 'Gender') ?>
                    </label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="gender" value="<?= \system\models\User::GENDER_MALE ?>"
                                <?= isset($model->gender) && $model->gender != $model::GENDER_FEMALE || !isset($model->gender)
                                    ? 'checked'
                                    : ''
                                ?>
                            >
                            <?= App::t('app', 'Male') ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="gender" value="<?= \system\models\User::GENDER_FEMALE ?>"
                                <?= isset($model->gender) && $model->gender == $model::GENDER_FEMALE ? 'checked' : '' ?>
                            >
                            <?= App::t('app', 'Female') ?>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="inputLogin" class="required">
                            <?= App::t('app', 'Your nickname') ?>
                        </label>
                        <input type="text" id="inputLogin" class="form-control"
                               value="<?= isset($model->login) ? $this->e($model->login) : '' ?>"
                               name="login"
                               placeholder="<?= App::t('app', 'JaneDoe') ?>" required aria-required="true">

                        <?php if (isset($errors['login']) && $error = $errors['login'][0]): ?>
                            <div id="errorsinputLogin">
                                <div class="validationErrorMessage">
                                    <?= $error ?>
                                </div>
                            </div>
                        <?php endif ?>

                        <small class="help-block">
                            <?= App::t('app', 'Nickname hint') ?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="inputFile">
                            <?= App::t('app', 'Profile picture') ?>
                        </label>
                        <input type="file" id="inputFile" name="picture">

                        <?php if (isset($errors['picture']) && $error = $errors['picture'][0]): ?>
                            <div id="errorsinputFile">
                                <div class="validationErrorMessage">
                                    <?= $error ?>
                                </div>
                            </div>
                        <?php endif ?>

                        <small class="help-block">
                            <?= App::t('app', 'Profile picture hint') ?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="inputInformation">
                            <?= App::t('app', 'Information') ?>
                        </label>
                        <textarea class="form-control full-width" name="information" id="inputInformation"
                                  cols="4"><?= isset($model->information) ? $this->e($model->information) : '' ?></textarea>
                        <small class="help-block">
                            <?= App::t('app', 'Information hint') ?>
                        </small>
                    </div>

                    <label for="inputEmail" class="required"><?= App::t('app', 'Email') ?></label>
                    <input type="email" id="inputEmail" class="form-control"
                           value="<?= isset($model->email) ? $this->e($model->email) : '' ?>"
                           name="email"
                           placeholder="<?= App::t('app', 'jane.doe@example.com') ?>" required aria-required="true">

                    <?php if (isset($errors['email']) && $error = $errors['email'][0]): ?>
                        <div id="errorsinputEmail">
                            <div class="validationErrorMessage">
                                <?= $error ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <small class="help-block">
                        <?= App::t('app', 'Email hint') ?>
                    </small>

                    <label for="inputPassword" class="required">
                        <?= App::t('app', 'Password') ?>
                    </label>
                    <div class="input-group">
                        <input type="password" id="inputPassword" class="form-control"
                               value="<?= isset($model->password) ? $this->e($model->password) : '' ?>"
                               name="password"
                               placeholder="<?= App::t('app', 'Password') ?>" required aria-required="true">
                        <span class="input-group-addon toggle-password" id="toggle-password"
                              title="<?= App::t('app', 'Show password') ?>">
                        <i class="glyphicon glyphicon-eye-open"></i>
                    </span>
                    </div>

                    <?php if (isset($errors['password']) && $error = $errors['password'][0]): ?>
                        <div id="errorsinputPassword">
                            <div class="validationErrorMessage">
                                <?= $error ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <small class="help-block">
                        <?= App::t('app', 'Password hint') ?>
                    </small>

                    <hr>

                    <button class="btn btn-lg btn-success btn-block btn-signup" type="submit">
                        <?= App::t('app', 'Sign Up!') ?>
                    </button>
                </form><!-- /form -->
            </div><!-- /card-container -->

        </div>

    </div><!-- /container -->

<?php $this->start('body') ?>
    <script>

        function togglePass(button, field) {
            button.on('click', function () {
                if (field.attr('type') == 'password') {
                    button.html('<i class="glyphicon glyphicon-eye-close"></i>');
                    button.attr('title', '<?= App::t('app', 'Hide password') ?>');
                    field.attr('type', 'text');
                } else {
                    button.html('<i class="glyphicon glyphicon-eye-open"></i>');
                    button.attr('title', '<?= App::t('app', 'Show password') ?>');
                    field.attr('type', 'password');
                }
            })
        }

        var regToggleButton = $('#toggle-password');
        var regField = $('#inputPassword');
        var loginToggleButton = $('#login-toggle-password');
        var loginField = $('#loginInputPassword');

        togglePass(regToggleButton, regField);
        togglePass(loginToggleButton, loginField);

        $(document).ready(function () {
            addValidationRule('inputFirstName', ['required', '<?= App::t('validation', 'First Name required') ?>']);
            addValidationRule('inputFirstName', ['string', 2, 30, '<?= App::t('validation', 'First Name length') ?>']);

            addValidationRule('inputLastName', ['required', '<?= App::t('validation', 'Last Name required') ?>']);
            addValidationRule('inputLastName', ['string', 2, 30, '<?= App::t('validation', 'Last Name length') ?>']);

            addValidationRule('inputInformation', ['string', 0, 255, '<?= App::t('validation', 'Information length') ?>']);

            addValidationRule('inputEmail', ['email', '<?= App::t('validation', 'Email email') ?>', '<?= App::t('validation', 'Email unique') ?>']);
            addValidationRule('inputPassword', ['string', 4, 255, '<?= App::t('validation', 'Password length') ?>']);

            addValidationRule('inputLogin', ['login', 3, 50, '<?= App::t('validation', 'Login length') ?>', '<?= App::t('validation', 'Login unique') ?>']);
            addValidationRule('inputFile', ['file', 'jpg|jpeg|png|gif', '<?= App::t('validation', 'File ext') ?>']);

            formValidation('registrationForm');
        });
    </script>
<?php $this->stop() ?>