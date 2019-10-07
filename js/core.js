
$(document).ready(function() {

    function getCaptcha() {
        if (captchaActive ===1){
        var FormCaptcha = document.getElementById('captcha').value;
        return FormCaptcha;
        }else{
            return 0;
        }
    }
    function getToken() {
        if(csrfActive === 1){
        var FormToken = document.getElementById('token').value;
        return FormToken;
        }else{
            return 0;
        }
    }


    var url = $("#url");

    if(captchaActive === 1){
        var captcha = $("#captcha");
    }else{
        var captcha = 0;
    }



    //Функция отправки ajax-запроса
    $("form").submit(function() {
        var th = $(this);
        $.ajax({
            type: "POST",
            url: "app/validator.php",
            data:{'url': document.getElementById('url').value,
                'token': getToken(), //document.getElementById('token').value,
                'captcha': getCaptcha() //document.getElementById('captcha').value

            },
            success: function(msg){
                var splitMsg = msg.split(' ');
                if (splitMsg[0]==='succes'){
                    //Если был получен ответ succes с сервера
                        if (captchaActive === 1){
                            document.getElementById('captcha-img').src='app/captcha.php?id=' + (+new Date());
                        }
                        setCookie(splitMsg[1],splitMsg[2]);
                        clearExcessCookies();
                        addRecentlyShortLink($(url).val(), splitMsg[1]);
                        userMessage('Успешно добавленна ссылка');
                        th.trigger("reset");
                }else{
                    //Если получено сообщение об ошибке
                    errorMassage(msg);
                }
            },
        })
    });

    function errorMassage(err){
        var errMessage;
        if (err==='captcha'){
            captcha.val('').focus().addClass('wrong');
            errMessage = 'Капча заполнена неккоректно, попробуйте ещё раз';
        }else if(err==='url'){
            errMessage = 'Неправильный формат URL. Пример: http://example.com/page/';
            url.focus().addClass('wrong');
        }else if (err==='db'){
            errMessage = 'Ошибка записи в базу данных, возможно url превышает допустимую длинну (534)';
        }else{
            errMessage = err;
        }

        $('.alert').html(errMessage).fadeIn('slow').click(function() {
            $('.alert').fadeOut(400);
        });
        setTimeout(function() {
            $('.alert').fadeOut(2200);
        },4800);

        if (captchaActive===1){
            document.getElementById('captcha-img').src='app/captcha.php?id=' + (+new Date());
            captcha.val('');
        }
    }

    function userMessage(message){
        $('#message').html(message).fadeIn('slow').click(function() {
            $('#message').fadeOut(400);
        });
        setTimeout(function() {
            $('.alert').fadeOut(1200);
        },2800);
    }



    url.on("change", function () {
        validateUrlField(this);
    });

    if (captchaActive === 1){
        captcha.on("change", function () {
            validateCaptchaField(this);
        });
    }



    //Функция валидации поля captcha
    function validateCaptchaField(captcha) {
        if (/[0-9]{4}/.test(captcha.value)) {
            if ($(captcha).hasClass('wrong')) {
                $(captcha).removeClass('wrong');
            }
        } else {
            if (!$(captcha).hasClass('wrong')) {
                $(captcha).addClass('wrong');
            }
        }
    }

    //Функция валидации поля url
    function validateUrlField(url) {
        if (/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})+$/u.test(url.value)) {
            if ($(url).hasClass('wrong')) {
                $(url).removeClass('wrong');
            }
        } else {
            if (!$(url).hasClass('wrong')) {
                $(url).addClass('wrong');
            }
        }
    }

    // $('.copy-link').click(function () {
    //
    //     navigator.clipboard.writeText('Hello Alligator!')
    //         .then(() => {
    //             // Получилось!
    //         })
    //         .catch(err => {
    //             console.log('Something went wrong', err);
    //         });
    //
    // });

    function clearExcessCookies() {
        var cookie = document.cookie;
        var splitArr = cookie.split('; ');
        splitArr = splitArr.reverse();
        var count=0;
        splitArr.forEach(function(item, i, arr) {
            item = item.split('=');
            if(/^([0-9]+)$/.test(item[0])){
                count++;
                if(count>=8){
                    document.cookie = item[0] +"="+item[1]+"; max-age=0";
                }
            }
        });
        }


    function setCookie(suffix, timestamp) {
        // +1 день от текущей даты
        let date = new Date(Date.now() + 86400e3);
        date = date.toUTCString();
        document.cookie = timestamp +"="+suffix+"; expires=" + date;
    }

    var recentlyShorts = $(".recently-shorts");


    function addRecentlyShortLink(full_url, suffix) {

        if(document.getElementById('recently-shorts').childNodes.length >= 9){
            document.getElementById('recently-shorts').childNodes[7].remove();
        };

        recentlyShorts.prepend('<div class="link"><div class="full-url"> '+ full_url +' </div>' +
                                            '<div class="short-url">http://'+ document.domain+'/' + suffix +'</div>' +
                                            '<div class="delete-link"></div></div>');
    }

    var jsCuttextarea = $('.js-cuttextarea');

    recentlyShorts.on("click", ".short-url", function () {
            jsCuttextarea.val(this.innerHTML).removeClass('hidden');
            var cutTextarea = document.querySelector('.js-cuttextarea');
            cutTextarea.select();
            try {
                var successful = document.execCommand('cut');
                successful ? userMessage('Скопировано в буфер')  : errorMassage('Не получилось скопировать');
                jsCuttextarea.addClass('hidden');
            } catch(err) {
                console.log('Нечего копировать');
            }


    });

    recentlyShorts.on("click", ".full-url", function () {
        jsCuttextarea.val(this.innerHTML).removeClass('hidden');
        var cutTextarea = document.querySelector('.js-cuttextarea');
        cutTextarea.select();
        try {
            var successful = document.execCommand('cut');
            successful ? userMessage('Скопировано в буфер')  : errorMassage('Не получилось скопировать');
            jsCuttextarea.addClass('hidden');
        } catch(err) {
            console.log('Нечего копировать');
        }


    });

    recentlyShorts.on("click", ".delete-link", function () {
        var url = $(this).prev().html();
        var splitSuffix = url.split(document.domain+'/');
        var parent = $(this).parent();
        $.ajax({
            type: "POST",
            url: "app/validator.php",
            data:{'delete_url': splitSuffix[1],
            },
            success: function(msg){
            if (msg==='success_del'){
                deleteCookie(splitSuffix[1]);
                parent.remove();
                userMessage('Удалено');
            }else if(msg==='error_del'){
                errorMassage('Ошибка удаления');
            }

            }
        });
    });

    function deleteCookie(suffix) {
        var cookie = document.cookie;
        var splitArr = cookie.split('; ');
        splitArr.forEach(function(item, i, arr) {
            item = item.split('=');
            if(item[1]===suffix){
                document.cookie = item[0] +"="+item[1]+"; max-age=0";
            }
        });
    }

});
