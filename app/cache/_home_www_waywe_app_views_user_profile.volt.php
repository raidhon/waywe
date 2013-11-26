	<!-- main -->
 <script type="text/javascript">
	<?php echo $loc; ?>
	$(document).ready(function(){
		$('[name=country]').on('change' , function(){
			$('[name=location]').typeahead ("destroy");
			$('[name=location]').typeahead (
			{
				name:$('[name=country]').val(),
        		limit: 10,
        		local: loc[$('[name=country]').val()]
			});
 		});
		$('[name=country]').change();

            // add uniform plugin styles to html elements
        $("input:checkbox, input:radio").uniform();

            // select2 plugin for select elements
        $("select").select2({
            placeholder: "Выберите страну"
        });

            // datepicker plugin
        $('.input-datepicker').datepicker().on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
	    $('[name=patronym], [name=first_name], [name=last_name]').on('keyup',function () {
			$('.checkbox-inline strong').text($('[name=last_name]').val() + ' ' + $('[name=first_name]').val() + ' ' + $('[name=patronym]').val());
		})
		
		$('.personal-image input').on('change',function () {
			var fd = new FormData($('.personal-image form')[0]);
			$.ajax({
            	type: "POST",
            	url: "upload",
            	data: fd,
            	success: function (html) {
                	$('.avatar').attr("src",html);
                	$('.personal-image input').attr({'class':''});
	            },
	            error: function () {
	            	$('.avatar').attr("src","/waywe/img/personal-info.png");
	            	$('.personal-image input').attr({'class':'form-control  error'});
	            },
	            cache       : false,
           		contentType : false,
            	processData : false
	    	});
        });
        $('.img-delete').on('click',function () {
			$.ajax({
            	type: "POST",
            	url: "deletephoto",
            	success: function (html) {
                	$('.avatar').attr("src","/waywe/img/personal-info.png");
	            },
	            error: function () {
	            	console.log("Error");
	            },
	            cache       : false,
           		contentType : false,
            	processData : false
	    	});
        });
        $('[name=email]').live('change' , function(){
			var ourEmail = $('[name=email]').val();
			$.ajax({
				type: "post",
				url: "/waywe/user/validemail",
				data:{email:ourEmail},
				success: function(data ,textStatus, jqXHR){
					if (jqXHR.status == 201) 
					{
						$('form').attr('valid', 0);
						$('#email').tooltip('destroy').addClass('error').tooltip({'title':'Данный email существует' ,'delay': { show: 500, hide: 10000 },'trigger':'manual'}).tooltip('show');
					} else {
						$('form').attr('valid', 1);
						$('#email').tooltip('destroy').removeClass('error');
					}
				}
			});
		});
    });
 </script>
    <div class="content">
        <div class="settings-wrapper" id="pad-wrapper">
            <div class="row">
                <!-- avatar column -->
                <div class="col-md-3 col-md-offset-1 avatar-box">
                    <div class="personal-image">
                    <?php echo $this->tag->form(array('enctype' => 'multipart/form-data', 'valid' => '1')); ?>
						<?php echo $this->tag->image(array(($user->photo_medium ? 'img/user/' . $user->id . '/' . $user->photo_medium : 'img/personal-info.png'), 'class' => 'avatar img-circle')); ?>
               	        <p>Загрузите свою фотографию...</p>
           	          	<span class="small">Максимальный размер файла 5 Мб, формат JPG, PNG или GIF.</span></br></br>
               	    	<input name="photo" type="file" /><br><br>
               	       &times;<a href="#" class="img-delete">Удалить изображение</a>
               	    </form>
                    </div>
                </div>

                <!-- edit form column -->
                <div class="col-md-7 personal-info">
                    <h5 class="personal-title">Персональная информация</h5>
                    <?php echo $this->tag->form(array('test/profile', 'class' => 'form-horizontal', 'valid' => '1')); ?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Фамилия*</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('last_name', 'class' => 'form-control', 'value' => $user->last_name)); ?>          
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Имя*</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('first_name', 'class' => 'form-control', 'value' => $user->first_name)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Отчество*</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('patronym', 'class' => 'form-control', 'value' => $user->patronym)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Пол</label>
                            <div class="col-md-8">
                                <label class="radio">
                                    	<input type=radio name='sex' value='M' <?php echo ($user->sex == 'M' ? 'checked' : ''); ?>>
                                	<span class="radio">Мужской</span>
                                </label>
                                <label class="radio">
                                    	<input type=radio name='sex' value='F' <?php echo ($user->sex == 'F' ? 'checked' : ''); ?>>
                                    	<span class="radio">Женский</span>
                                </label>
                            </div>    
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Дата рождения*</label>
                            <div class="col-lg-8">
	                            <?php echo $this->tag->textField(array('birthdate', 'class' => 'form-control input-datepicker', 'value' => $user->birthdate)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Cтрана*</label>
                            <div class="col-lg-8">
                                <select name="country" class="select2">
			   		<?php foreach ($ourCountries as $value) { ?>
          					<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
			   		<?php } ?>
			   	</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Город*</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('location', 'class' => 'form-control', 'value' => $user->location)); ?>
                            </div>
                        </div>
                        
                        <h6 class="personal-title">Контактная информация</h6>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">E-mail*</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('email', 'class' => 'form-control', 'value' => $user->email)); ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Телефон</label>
                            <div class="col-lg-8">
                            <table width="100%">
                            	<tr>
                            		<td width="65%"><?php echo $this->tag->textField(array('phone', 'class' => 'form-control', 'value' => $user->phone)); ?></td>
                            		<td width="35%" align="center"><a href="#" class="btn-glow primary" >Выслать код</a></td>
                            	</tr>
                            </table>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Код</label>
                            <div class="col-lg-8">
                            <table width="100%">
                            	<tr>
                            		<td width="65%"><?php echo $this->tag->passwordField(array('forget_hash', 'class' => 'form-control')); ?></td>
                            		<td width="35%" align="center" class="alert-msg-success"><i class="icon-ok-sign"></i> Код верный</td>
                            		<!--- <td width="35%" align="center" class="alert-msg-wrong"><i class="icon-remove-sign"></i> Код неверный</td>    --->
                            	</tr>
                            	<tr>
                            		<td width="100%" colspan="2">
                            		<label class="checkbox-inline sms-check">
	                            	<?php echo $this->tag->checkField(array('allow_sms', 'value' => 'Y')); ?> Я, <strong><?php echo $user->last_name; ?> <?php echo $user->first_name; ?> <?php echo $user->patronym; ?></strong>, согласен получать смс-уведомления. С условиями и порядком использования смс-уведомления ознакомлен.
                 					</label>
                 					</td>
                            	</tr>
                            </table>
                            </div>
                        </div>
                        
                        <h6 class="personal-title">Дополнительная информация</h6>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Skype</label>
                            <div class="col-lg-8">
                               <?php echo $this->tag->textField(array('skype', 'class' => 'form-control', 'value' => $user->skype)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">ICQ</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('icq', 'class' => 'form-control', 'value' => $user->icq)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Vkontakte</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('vkontakte', 'class' => 'form-control', 'value' => $user->vkontakte)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Facebook</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->textField(array('facebook', 'class' => 'form-control', 'value' => $user->facebook)); ?>
                            </div>
                        </div>

                        <h6 class="personal-title">Смена пароля</h6>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Старый пароль</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->passwordField(array('old_password', 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Пароль</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->passwordField(array('password', 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Повторите пароль</label>
                            <div class="col-lg-8">
                                <?php echo $this->tag->passwordField(array('repeatPassword', 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="actions">
                            <input type="submit" class="btn-glow middle-button primary" value="Сохранить">
                        </div>
                    </form>
                </div>
            </div>            
        </div>
    </div>
    <!-- end main container -->