function getUserAgent() {
  return navigator.userAgent;
}
function switchCSS() {
var userAgent = getUserAgent();

if (userAgent.includes("iPhone")) {
  var newCSS = "css/smp.css";
    document.getElementById("maincss").setAttribute("href", newCSS);
  }
}

window.onload = switchCSS;
