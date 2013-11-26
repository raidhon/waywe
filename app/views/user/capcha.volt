{{ content() }}

<script src="http://code.jquery.com/jquery-latest.js"></script>
    <div class="login-wrapper">
        <a href="/">
            <img class="logo" src="../img/logo-waywe.png">
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>Авторизация</h6>
		{{ form('user/auth') }}
		{{ text_field('email', 'class': "form-control" , 'placeholder': "Ваш логин (E-mail)") }}
                {{ password_field('password', 'class': "form-control" , 'placeholder': "Ваш пароль") }}
 <script type="text/javascript"
		 src="http://www.google.com/recaptcha/api/challenge?k=6LcvXukSAAAAAKiET3AS76qPBaIKtV_5R-N523wS">
	  </script>
	<noscript>
	 <iframe src="http://www.google.com/recaptcha/api/noscript?k=6LcvXukSAAAAAKiET3AS76qPBaIKtV_5R-N523wS"
		 height="300" width="500" frameborder="0"></iframe><br>
	 <textarea name="recaptcha_challenge_field" rows="3" cols="40">
	 </textarea>
		 <input type="hidden" name="recaptcha_response_field"
		 value="manual_challenge">
	  </noscript>
		{{ hidden_field('redirTo', 'value': redirVal) }}
		{{ link_to('user/forgot', 'Забыли пароль?', 'class': 'forgot') }}
                <div class="remember">
		    {{ check_field('remember', 'id': "remember-me") }}
                    <label for="remember-me">Запомнить меня</label>
                </div>
		{{ submit_button('Вход', 'class': 'btn-glow primary login') }}
		</form>
            </div>
        </div>

        <div class="no-account">
            <p>У вас еще нет аккаунта?</p>
            {{ link_to('user/register', 'Регистрация') }}
        </div>
    </div>

	<!-- scripts -->
	{{ javascript_include('js/fw/bootstrap.min.js') }}
	{{ javascript_include('js/fw/theme.js') }}
   