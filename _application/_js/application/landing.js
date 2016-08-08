/**
 * Created by Анатоли on 28.07.2016.
 */
$("#getFullAccess, a").click(function(e) {
    notif({
        msg: 'Данная функция временно недоступна',
        type: "warning",
        position: "center",
        width:'400',

        timeout: 5000,
        autohide: true,
        opacity: 0.9,
        bgcolor: "green",
        fade: true
    });
    return false;
});

$("#feedBack").click(function(e) {
    notif({
        msg: 'Данная кнопка в дальнейшем будет открывать форму для вопроса специалисту<br><br>Куда можно будет написать вопросы или что-то еще!<hr> Спасибо',
        type: "warning",
        position: "center",
        width:'400',

        timeout: 8000,
        autohide: true,
        opacity: 0.9,
        bgcolor: "orange",
        color: "black",
        fade: true
    });
    return false;
})







