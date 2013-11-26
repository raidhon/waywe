
{{ content() }}

<script src="http://code.jquery.com/jquery-latest.js"></script>
    <div class="login-wrapper">
        <a href="/">
            {{ image('img/logo-waywe.png', 'class' : 'logo') }}
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>Авторизация</h6>
		{{ form('user/auth') }}
		{{ text_field('email', 'class': "form-control value_required" , 'placeholder': "Ваш логин (E-mail)") }}
                {{ password_field('password', 'class': "form-control value_required" , 'placeholder': "Ваш пароль") }}
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
   