$(document).ready(function () {
    listMessage();
    $('#comment_message').on('keyup', function (ev) {
        if ($(this).val() !== "" && $('#comment_email').val() !== "") {
            $('#btn_post').prop('disabled', false);
        } else {
            $('#btn_post').prop('disabled', true);
        }
    });
    $('#btn_post').on('click', function (ev) {
        let email = $('#comment_email').val()
        let message = $('#comment_message').val()
        $.ajax({
            method: 'get',
            dataType: 'json',
            data: {
                email: email,
                message: message
            },
            url: url_post_message,
            success: function () {
                listMessage();
                emptyField()
            }
        })
    });
});

/**
 * function affichage list de message
 */
function listMessage() {
    $.ajax({
        method: 'get',
        dataType: 'json',
        url: url_comment_list,
        success: function (results) {
            let output = "";
            $.each(results, function (i, item) {
                let date_add_formt = moment(item.date_add).format('DD/MM/Y  H:mm:ss');
                output += ' <div class="d-flex flex-row align-items-center commented-user">' +
                    ' <h5 class="mr-2">' + item.username + '</h5><span class="dot mb-1"></span>' +
                    ' <span class="mb-1 ml-2">(' + date_add_formt + ')</span>' +
                    ' </div>' +
                    ' <div class="comment-text-sm">' +
                    ' <span>' + item.message + '</span>' +
                    ' </div><hr>';
            })
            $('#list-comment').html(output);
        }
    })
}

/**
 * function vidage des champs apres l'envoie
 */
function emptyField() {
    $('#comment_email').val('');
    $('#comment_message').val('');
}