$(document).ready(function() {
    $userid=window.localStorage.getItem("sessionid");
    console.log($userid);
    $.ajax({
    url: 'php/profile.php/?userid='+$userid,
    method: 'GET',
    dataType: 'json',})
    .done(function(data){
        document.getElementById('email').value=data._id;
        document.getElementById('fullname').value=data.fullname;
        document.getElementById('address').value=data.address;
        document.getElementById('phno').value=data.phno;
        document.getElementById('dob').value=data.dob;
        if(data.gender=='male'){
        document.getElementById('male').checked=true;
        document.getElementById('female').checked=false;
        }
        else{
        document.getElementById('male').checked=false;
        document.getElementById('female').checked=true;
        }
        // document.getElementsByName('gender').value=data.gender;

    });
    $("#save").click(function(){
        var gender="male";
        if(document.getElementById('male').checked==true){
            gender='male';
        }
        else{
        gender='female';
        }
        $.post("php/profile.php",
        {
            _id:document.getElementById("email").value,
            dob: document.getElementById('dob').value,
            phno: document.getElementById('phno').value,
            address:document.getElementById('address').value,
            gender:gender
        },
        function(data, status){
            window.location.href = 'profile.html';
        });
        });
        $("#logout").click(function(){
        window.localStorage.clear();
        window.location.href = 'login.html';
        });
    });