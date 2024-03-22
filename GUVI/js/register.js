$(document).ready(function() {
    $("#Register").click(function(){
        $.post("php/register.php",
        {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        fullname:document.getElementById('fullname').value,
        repeat_password:document.getElementById('repeat_password').value
        },
        function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
        window.location.href = "login.html";
        });
    });
});
//document.localStorage.setItem("session_id",data )
//
//localStorage.getItem("session_id");
//window.location.href = "http://www.w3schools.com";
//
