
{{ content() }}

<script src="http://code.jquery.com/jquery-latest.js"></script>
    <div class="login-wrapper">
        <a href="/">
            {{ image('img/logo-waywe.png', 'class' : 'logo') }}
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>Авторизация</h6>
        Для авторизации перейдите по ссылке
		{{ link_to('user/auth', 'Авторизация', 'class': 'forgot') }}


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
