// Валидация каждой формы на каждой странице проекта. Обязательное поле выделяется классом value_required.


$(document).ready(function(){
    $('form').on('submit',function () {
			
        var res = true;

        $(this).find('input, textarea, select').each(function (){

            if ($(this).attr('name') == 'email') {
                if ($(this).val().search(/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i)) {
                    $(this).addClass('error').tooltip('destroy').tooltip({'title':'Неправильный email' ,'delay': { 'show': 0, 'hide': 10000, 'trigger': true}}).tooltip('show');
                    res = false;
                } else if (res) $(this).removeClass('error').tooltip('destroy');
            } else
            if ($(this).val().match('[^- _0-9a-zA-Z\'!:#$%&*\/?\^_`{|}~@.АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯІЇЄЃабвгдеёжзийклмнопрстуфхцчшщьыъэюяабвгдеёжзийклмнопрстуфхцчшщьыъэюяіїєѓ]'))
            {
                $(this).addClass('error').tooltip('destroy').tooltip({'title':'Неправильный символ' ,'delay': { 'show': 0, 'hide': 10000, 'trigger': true}}).tooltip('show');
                res = false;
            } else if (res) $(this).removeClass('error').tooltip('destroy');
        });
        if ($(this).find('#repeatPassword').length) {
            if ($(this).find('#repeatPassword').val() != $(this).find('#password').val())
            {
                $(this).find('#repeatPassword').addClass('error').tooltip('destroy').tooltip({'title':'Пароли не совпадают' ,'delay': { 'show': 0, 'hide': 10000, 'trigger': true}}).tooltip('show');
                res = false;
            } else if (res) $(this).find('#repeatPassword').removeClass('error').tooltip('destroy');
        }
        $(this).find('.value_required').each(function (){

            if ($(this).val().match(/^\s*$/)) {
                $(this).addClass('error').tooltip('destroy').tooltip({'title':'Обязательное поле' ,'delay': { 'show': 0, 'hide': 10000, 'trigger': true}}).tooltip('show');
                res = false;
            } else if (res) $(this).removeClass('error').tooltip('destroy');
        });

        $(this).find('.checkbox_required').each(function (){

            if ($(this).prop('checked') == false) {
                $(this).addClass('error').tooltip('destroy').tooltip({'title':'Обязательное поле' ,'delay': { 'show': 0, 'hide': 10000, 'trigger': true}}).tooltip('show');
                res = false;
            } else if (res) $(this).removeClass('error').tooltip('destroy');
        });

        $(this).find('[errortext]').each(function (){

            $(this).addClass('error').tooltip('destroy').tooltip({'title':$(this).attr('errortext') ,'delay': { 'show': 0, 'hide': 10000, 'trigger': true}}).tooltip('show');
            res = false;
        });

        return res;
    });
});