<?php echo $this->getContent(); ?>

 <div class="login-wrapper">
        <a href="index.html">
		<?php echo $this->tag->image(array('img/logo-waywe.png', 'class' => 'logo')); ?>
            
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>Восстановление пароля</h6>
				<?php echo $this->tag->form(array('user/recpwd', 'id' => 'recpwdForm', 'onbeforesubmit' => 'return false', 'valid' => '1')); ?>
				<?php echo $this->tag->textField(array('email', 'class' => 'form-control value_required', 'placeholder' => 'Ваш логин (E-mail)')); ?>
               
				<?php echo $this->tag->submitButton(array('Отправить', 'class' => 'btn-glow success login')); ?>
                
				</form>
				<?php echo $this->tag->linkTo(array('user/auth', 'Вернуться назад', 'class' => 'back')); ?>
                
            </div>            
        </div>

        <div class="no-account">
            <p>У вас еще нет аккаунта?</p>
			<?php echo $this->tag->linkTo(array('user/register', 'Регистрация')); ?>
            
        </div>
    </div>

	

    <!-- pre load bg imgs -->
    <script type="text/javascript">
        $(function () {
            // bg switcher
            var $btns = $(".bg-switch .bg");
            $btns.click(function (e) {
                e.preventDefault();
                $btns.removeClass("active");
                $(this).addClass("active");
                var bg = $(this).data("img");

                $("html").css("background-image", "url('img/bgs/" + bg + "')");
            });

        });
    </script>