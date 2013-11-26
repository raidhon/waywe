<?php echo $this->getContent(); ?>


 <div class="header">
        <a href="">
		<?php echo $this->tag->image(array('img/logo-waywe-small.png', 'class' => 'logo')); ?>
            
        </a>
    </div>
<div class="singup">
    	<h1>Регистрация нового пользователя</h1>
		
		
		<?php echo $this->tag->form(array('user/register', 'id' => 'registerForm', 'class' => 'form-horizontal', 'onbeforesubmit' => 'return false')); ?>
    <fieldset>
        <div class="box">
            <div class="content-wrap">
            	<label>Фалимилия</label>
                <?php echo $this->tag->textField(array('last_name', 'class' => 'form-control value_required')); ?>
                <label>Имя</label>
                <?php echo $this->tag->textField(array('first_name', 'class' => 'form-control value_required')); ?>
                <label>Отчество</label>
                <?php echo $this->tag->textField(array('patronym', 'class' => 'form-control value_required')); ?>          
            </div>  
        </div>
		<div class="box min">
            <div class="content-wrap">
			<label style="margin:0 0 10px 37%; ">Пол</label>
			<div class="male">Мужской</div>
			<div style="width:60px; float: left;
    margin: 0 0 0 40%;
    min-height: 1px;">
	            <select name="sex" id='switch-me'>
            <option value='M'>male</option>
            <option value='F'>female</option>
          </select>
	
				</div>  
				<br><br>
				<div class="female">Женский</div>
            </div>  
			 
        </div>
		<div class="box">
            <div class="content-wrap">
            	<label>Дата рождения</label>
				<?php echo $this->tag->textField(array('birthdate', 'class' => 'form-control input-datepicker value_required', 'data-date-format' => 'dd.mm.yyyy', 'readonly' => '')); ?>
                <label>Страна</label>
				<div style="margin-bottom:5px;">
				<select name="country">
			   <?php foreach ($ourCountries as $value) { ?>
          <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
			   <?php } ?>
			   </select>
			   </div>
                <label>Город</label>
				<div style="">
				<?php echo $this->tag->textField(array('location')); ?>
                  </div>            
            </div>  
        </div>
		<div class="box">
            <div class="content-wrap">
            	<label id="labemail">E-mail</label>
				<?php echo $this->tag->textField(array('email', 'class' => 'form-control value_required')); ?>
			<label>Пароль</label>
			   <?php echo $this->tag->passwordField(array('password', 'class' => 'form-control value_required')); ?>
               <label id="labrpass" >Повторите пароль</label>
			   <?php echo $this->tag->passwordField(array('repeatPassword', 'class' => 'form-control value_required')); ?>
            </div> 
        </div>
		  <div class="box max">
            <div class="content-wrap">
                <h6>Пользовательское соглашение</h6>
                <span>1. ТЕРМИНЫ И ОПРЕДЕЛЕНИЯ, ИСПОЛЬЗУЕМЫЕ НА WAYWE

В настоящем Соглашении, если из текста Соглашения прямо не вытекает иное, следующие слова и выражения, используемые при взаимодействии Сторон в ходе исполнения обязательств, будут иметь указанные ниже значения:

1.1. Интернет-сайт (Сайт) — совокупность аппаратных средств, программ для ЭВМ, обеспечивающих публикацию данных, текстовых, графических и/или аудиовизуальных произведений путём сообщения для всеобщего сведения посредством технических средств, применяемых для связи между ЭВМ в Сети Интернет.

1.2. Веб-страница — составляющая часть Интернет-сайта, отображаемая в единичном окне браузера и обладающая самостоятельным URL-адресом в структуре адресов Интернет-сайта.

1.3. Пользователь — дееспособное физическое лицо, гражданин РФ, достигший 18 лет, ранее прошедший процедуру регистрации на WAYWE в качестве Пользователя и совершивший Акцепт пользовательского соглашения, размещённого на Сайте Общества по адресу: www.waywe.ru, заключившее с Обществом Соглашение об оказании услуг по привлечению Клиентов и получившее специализированный доступ к WAYWE.

1.1. Интернет-сайт (Сайт) — совокупность аппаратных средств, программ для ЭВМ, обеспечивающих публикацию данных, текстовых, графических и/или аудиовизуальных произведений путём сообщения для всеобщего сведения посредством технических средств, применяемых для связи между ЭВМ в Сети Интернет.

1.2. Веб-страница — составляющая часть Интернет-сайта, отображаемая в единичном окне браузера и обладающая самостоятельным URL-адресом в структуре адресов Интернет-сайта.

1.3. Пользователь — дееспособное физическое лицо, гражданин РФ, достигший 18 лет, ранее прошедший процедуру регистрации на WAYWE в качестве Пользователя и совершивший Акцепт пользовательского соглашения, размещённого на Сайте Общества по адресу: www.waywe.ru, заключившее с Обществом Соглашение об оказании услуг по привлечению Клиентов и получившее специализированный доступ к WAYWE.
			</span>   
                 <label class="checkbox-inline">
                 <input class="checkbox_required" type="checkbox" id="inlineCheckbox1" value="option1"> Я, <strong id="user_fio"></strong>, принимаю условия пользовательского соглашения, и даю согласие на обработку персональных данных согласно требованиям РФ
                 </label>
                         
            </div>            
        </div>
		<div style="clear:both;">
        <div class="action">
           <?php echo $this->tag->submitButton(array('Регистрация', 'class' => 'btn-glow primary big-button')); ?>
            <div class="already">
            <p>Eсть уже свой аккаунт? <a href="">Авторизуйтесь</a></p>
            
            </div>
        </div> 

    </div>
        
    </fieldset>
</form>

<script type="text/javascript"> 

$(function () {

		$('#switch-me').switchy();

$('#switch-me').on('change', function(){
    
    // Animate Switchy Bar background color
    var bgColor = '#ccb3dc';

    if ($(this).val() == 'M'){
	
      bgColor = '#7fcbea';
    } else if ($(this).val() == 'F'){
      bgColor = '#ed7ab0';
    }

    $('.switchy-bar').animate({
      backgroundColor: bgColor
    });

    // Display action in console
    
  });

            // add uniform plugin styles to html elements
            $("input:checkbox, input:radio").uniform();

            // select2 plugin for select elements
            $("[name=country]").select2({
                placeholder: "страна"
            });

            // datepicker plugin
            $('.input-datepicker').datepicker().on('changeDate', function (ev) {
			
                $(this).datepicker('hide');
				
            });
        });




<?php echo $jsLocBase; ?>
<?php echo $jsDetectedLoc; ?>


		
// Выбор страны
var country = $('[name=country]').val();


//console.log(country);

$(document).ready(function(){


$('[name=country]').on('change' , function(){

//country = $('[name=country]').val();
$('[name=location]').typeahead ("destroy");
$('[name=location]').typeahead (
		{
		
		name:$('[name=country]').val(),
        limit: 10,
        local: jsLocBase[$('[name=country]').val()]
		
		});

 });
$('[name=country]').change();
$('[name=location]').attr({'placeholder':jsDetectedLoc['city'], 'value':jsDetectedLoc['city']});

	
});

// Валидация email

$('#registerForm [name=email]').live('change' , function(){

    var ourEmail = $('[name=email]').val();

	$.ajax({
		type: "post",
		url: "/waywe/user/validemail",
		data:{email:ourEmail},
		success: function(data ,textStatus, jqXHR){
		  
		  //console.log(jqXHR.status);
			if (jqXHR.status == 201) 
			{
                $('[name=email]').attr('errortext', 'Данный email существует');
                $('[name=email]').addClass('error').tooltip('destroy').tooltip({'title': $('[name=email]').attr('errortext') ,'delay': { 'show': 0, 'hide': 10000, 'trigger': true}}).tooltip('show');
			} else {
                $('[name=email]').removeAttr('errortext');
                $('[name=email]').removeClass('error').tooltip('destroy');
			}
		}
	});
});





///Создание строки Фамилия Имя Отчество
$('[name=patronym], [name=first_name], [name=last_name]').on('keyup change',function () {
			$('#user_fio').text($('[name=last_name]').val() + ' ' + $('[name=first_name]').val() + ' ' + $('[name=patronym]').val());
		});
		

</script>