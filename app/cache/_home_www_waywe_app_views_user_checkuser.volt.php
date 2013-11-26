
<?php echo $this->getContent(); ?>

<script src="http://code.jquery.com/jquery-latest.js"></script>
    <div class="login-wrapper">
        <a href="/">
            <?php echo $this->tag->image(array('img/logo-waywe.png', 'class' => 'logo')); ?>
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>Авторизация</h6>
        Для авторизации перейдите по ссылке
		<?php echo $this->tag->linkTo(array('user/auth', 'Авторизация', 'class' => 'forgot')); ?>


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
