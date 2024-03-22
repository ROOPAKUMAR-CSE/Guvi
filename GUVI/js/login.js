$(document).ready(function() {
    $("#login").click(function(){
        $.post("php/login.php",
        {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        },
        function (data) {
        if (data.success) {
            window.localStorage.setItem("sessionid",data.email);
            window.location.href = 'profile.html';
        } else {
                alert(data.msg);
        }
    });
});
});