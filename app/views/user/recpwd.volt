{{ content() }}

 <div class="login-wrapper">
        <a href="index.html">
		{{ image('img/logo-waywe.png' , 'class': 'logo')  }}
            
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>Восстановление пароля</h6>

				{{ form('user/recpwd' ,'id': 'recpwdForm',  'onbeforesubmit': 'return false','valid': '1') }}
				{{ text_field('email', 'class': 'form-control value_required' ,'placeholder':'Ваш логин (E-mail)') }}
               
				{{ submit_button('Отправить', 'class': 'btn-glow success login') }}
                
				</form>
				{{ link_to('user/auth' , 'Вернуться назад','class':'back')  }}

            </div>            
        </div>

        <div class="no-account">
            <p>У вас еще нет аккаунта?</p>
			{{ link_to('user/register' , 'Регистрация' )  }}
            
        </div>
    </div>
