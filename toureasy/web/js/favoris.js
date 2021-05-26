function chk($vars, $token) {
    var checked = document.getElementById("checkbox").checked;
    $.ajax({
        type: "post",
        url: $vars + "/web/js/postFav.php",
        data: {
            fav : checked,
            token: $token
        },
        cache: false,
        success: function (html) {
            $('#msg').html(html);
        }
    });
    return false;

}