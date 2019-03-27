<div id="data">
<!-- we are using this library for creating md5 hash:
https://raw.githubusercontent.com/emn178/js-md5/master/build/md5.min.js-->
    <script src="md5.min.js"></script>
    <script>
    var path = "https://testprepaid.mobilepulsa.net/v1/legacy/index";
    var usernameTxt = "089615171517";
    var passwordTxt = "4rph1mobilepulsa";
    var signTxt = md5(usernameTxt+passwordTxt+"pl");
 
    var doc = `{
        "commands" : "pricelist",
        "username" : "089615171517",
        "sign"     : "` + signTxt + `"
    }`;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', path, true);
    xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    xhr.onload = function () {
        if (xhr.readyState == 4 && xhr.status == "200") {
            document.getElementById("data").innerHTML = xhr.responseText;
        } else {
            console.error(xhr.responseText);
        }
    }
    xhr.send(doc);
    </script>
</div>