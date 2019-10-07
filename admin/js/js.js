
$(document).ready(function() {

    $('#captcha').on("change", function () {
        if ($('#captcha').is(":checked"))
        {
            editSettings('captcha', 1)
        }else{
            editSettings('captcha', 0)
        }
    });

    $('#csrf').on("change", function () {
        if ($('#csrf').is(":checked"))
        {
            editSettings('csrf', 1)
        }else{
            editSettings('csrf', 0)
        }
    });

    $('#canvas').on("change", function () {
        if ($('#canvas').is(":checked"))
        {
            editSettings('canvas', 1)
        }else{
            editSettings('canvas', 0)
        }
    });

    $('#admin_canvas').on("change", function () {
        if ($('#admin_canvas').is(":checked"))
        {
            editSettings('admin_canvas', 1)
        }else{
            editSettings('admin_canvas', 0)
        }
    });



    function editSettings(setting, value) {
        $.ajax({
            type: "POST",
            url: "app.php",
            data: {
                'edit_setting': setting,
                'value':value
            },
            success: function (msg) {
                userMessage(msg);
            }
        });
    }


    function userMessage(message) {
        $('#message').html(message).fadeIn('slow').click(function () {
            $('#message').fadeOut(400);
        });
        setTimeout(function () {
            $('.alert').fadeOut(1200);
        }, 2800);
    }

    var recentlyShorts = $(".recently-shorts");
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
            successful ? userMessage('Скопировано в буфер')  : userMessage('Не получилось скопировать');
            jsCuttextarea.addClass('hidden');
        } catch(err) {
            console.log('Нечего копировать');
        }


    });


    if (window.location.pathname==='/admin/list/'){
        var app = "../app.php";
    }else if(window.location.pathname==='/admin/'){
        var app = "app.php";
    }

    recentlyShorts.on("click", ".delete-link", function () {
        var url = $(this).prev().html();
        var splitSuffix = url.split(document.domain+'/');
        var parent = $(this).parent();
        $.ajax({
            type: "POST",
            url: app,
            data:{'delete_url': splitSuffix[1],
            },
            success: function(msg){
                if (msg==='success_del'){
                    parent.remove();
                    userMessage('Удалено');
                }else if(msg==='error_del'){
                    userMessage('Ошибка удаления');
                }
            }
        });
    });

});