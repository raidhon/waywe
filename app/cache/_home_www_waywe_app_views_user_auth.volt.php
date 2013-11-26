
<?php echo $this->getContent(); ?>

<script src="http://code.jquery.com/jquery-latest.js"></script>
    <div class="login-wrapper">
        <a href="/">
            <?php echo $this->tag->image(array('img/logo-waywe.png', 'class' => 'logo')); ?>
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>Авторизация</h6>
		<?php echo $this->tag->form(array('user/auth', 'valid' => '1')); ?>
		<?php echo $this->tag->textField(array('email', 'class' => 'form-control value_required', 'placeholder' => 'Ваш логин (E-mail)')); ?>
                <?php echo $this->tag->passwordField(array('password', 'class' => 'form-control value_required', 'placeholder' => 'Ваш пароль')); ?>
		<?php echo $this->tag->hiddenField(array('redirTo', 'value' => $redirVal)); ?>
		<?php echo $this->tag->linkTo(array('user/forgot', 'Забыли пароль?', 'class' => 'forgot')); ?>
                <div class="remember">
		    <?php echo $this->tag->checkField(array('remember', 'id' => 'remember-me')); ?>
                    <label for="remember-me">Запомнить меня</label>
                </div>
		<?php echo $this->tag->submitButton(array('Вход', 'class' => 'btn-glow primary login')); ?>
		</form>
            </div>
        </div>

        <div class="no-account">
            <p>У вас еще нет аккаунта?</p>
            <?php echo $this->tag->linkTo(array('user/register', 'Регистрация')); ?>
        </div>
    </div>

	<!-- scripts -->
	<?php echo $this->tag->javascriptInclude('js/fw/bootstrap.min.js'); ?>
	<?php echo $this->tag->javascriptInclude('js/fw/theme.js'); ?>
   