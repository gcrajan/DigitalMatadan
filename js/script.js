function sendOTP() 
{
    document.getElementById("error_OTP").innerText = ""

    document.getElementById("OTP_button").style.display = "none"
    setTimeout(() => { document.getElementById("OTP_button").style.display = "block" }, 5000)

    var xmlreq = new XMLHttpRequest();
    xmlreq.open("POST", "php/sendotp.php")
    xmlreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlreq.onload = () => {
        if (xmlreq.responseText.includes("Error:")) { document.getElementById("error_OTP").innerText = xmlreq.responseText }
    }
    xmlreq.send("VC=" + document.getElementById("VID").value);
}


// ------------for shop menu-------------------------- 
var shopModal = document.getElementById("voteModal");
var shopBtn = document.getElementById("createBtn");
var shopSpan = document.getElementsByClassName("close-voteModal")[0];

shopBtn.onclick = function () {
    shopModal.style.display = "block";
}
shopSpan.onclick = function () {
    shopModal.style.display = "none";
}
window.onclick = function (event) {
    if (event.target == shopModal) {
        shopModal.style.display = "none";
    }
}
