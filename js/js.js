
$(document).ready(function() {
    var urlField = $("#url");
    var captchaField = $("#captcha");
    //Функция отправки ajax-запроса
    $("form").submit(function() {
        var th = $(this);
        $.ajax({
            type: "POST",
            url: "app/validator.php",
            data:{'name': document.getElementById('name').value,
                'email': document.getElementById('email').value,
                'message': document.getElementById('message').value,
                'token': document.getElementById('token').value,
                'captcha': document.getElementById('captcha').value
            },
            success: function(msg){
                if (msg==='succes'){
                    //Если был получен ответ succes с сервера
                    setTimeout(function() {
                        var nameInput = document.getElementById('name').value;
                        $('.alert').addClass('hidden').html(msg);
                            $('.contact-form').fadeOut('slow', function () { //Скрываем форму и выводим блок "Спасибо, имя"
                                $('.thanx').html('Спасибо, '+ nameInput).fadeIn('slow');
                            });
                        th.trigger("reset");
                        document.getElementById('captcha-img').src='app/captcha.php?id=' + (+new Date());

                    }, 500);
                }else if(msg==='captcha'){
                    //Если получен ответ сервера о неккоректной капче удаляем класс hidden с блока alert и выводим в нём сообщение
                    $('.alert').removeClass('hidden').html('Капча заполнена неккоректно, попробуйте ещё раз');
                    $('#captcha').addClass('wrong').val('').focus();
                    document.getElementById('captcha-img').src='app/captcha.php?id=' + (+new Date());
                }else{
                    //Если были получены любые другие ошибки удаляем hidden с блока alert и выводим пришедшее сообщение об ошибке
                    document.getElementById('captcha-img').src='app/captcha.php?id=' + (+new Date());
                    $('#captcha').val('');
                    $('.alert').removeClass('hidden').html(msg);
                }
            },
        })
    });
    return false;
});


$(document).ready(function() {
    //При загрузке страницы объявляем переменные полей как false и блокируем submit-кнопку
    var nameField = false;
    var emailField = false;
    var messageField = false;
    var submit = $('#submit');
    submit.attr('disabled', true);

    //Функция валидации поля name
    $("#name").on("change", function(){
        var name = this.value.replace(/\s+/g,' ').trim();
        if (/^(\p{L}|\p{Zs})+$/u.test(name) && name.length < 60 && name !== " "){
            if ($(this).hasClass('wrong')){
                $(this).removeClass('wrong');
            }
            $(this).addClass('right');
            nameField=true;
        }else{
            if ($(this).hasClass('right')){
                $(this).removeClass('right');
            }
            $(this).addClass('wrong');
            nameField=false;

        }
        checkAllFields();
    });
    //Функция валидации поля email
    $("#email").on("change", function(){
        if (/.+@.+\..+/i.test(this.value)&& this.value.length < 60){
            if ($(this).hasClass('wrong')){
                $(this).removeClass('wrong');
            }
            $(this).addClass('right');
            emailField = true;
        }else{
            if ($(this).hasClass('right')){
                $(this).removeClass('right');
            }
            $(this).addClass('wrong');
            emailField = false;

        }
        checkAllFields();
    });

    //Функция валидации поля massage
    $("#message").on("change", function(){
        var message = this.value.replace(/\s+/g,' ').trim();
        if (message !== "" && message.length<=480){
            if ($(this).hasClass('wrong')){
                $(this).removeClass('wrong');
            }
            $(this).addClass('right');
            messageField = true;
        }else{
            if ($(this).hasClass('right')){
                $(this).removeClass('right');
            }
            $(this).addClass('wrong');
            messageField = false;

        }
        checkAllFields();
    });


    //Функция проверки всех полей для блокировки или разблокировки submit-кнопки формы
    function checkAllFields() {
        if ( nameField===true && emailField === true && messageField === true){
            submit.attr('disabled', false);
        }else{
            submit.attr('disabled', true);
        }
    }

});

    //Функция вывода значения аттрибута data-tooltip в всплывающем окне рядом с курсором
    $(document).ready(function() {
    $("[data-tooltip]").mouseover(function (eventObject) {
        data_tooltip = $(this).attr("data-tooltip");
        $("#tooltip").html(data_tooltip)
            .css({
                "top" : eventObject.pageY + 5,
                "left" : eventObject.pageX + 5
            })
            .show();
    }).mouseout(function () {
        $("#tooltip").hide()
            .html("")
            .css({
                "top" : 0,
                "left" : 0
            });
    });
});



