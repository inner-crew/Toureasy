var leBouton = document.querySelector("#b");
var laCarte = document.querySelector("#b1");
var divTexte = document.getElementById("message");
var divBtnCarte = document.getElementById("ouvrirCarte");
var pos;

divBtnCarte.style.display = "none";

leBouton.onclick = () => {
    if ("geolocation" in navigator) {
        /* la géolocalisation est disponible */
        navigator.geolocation.getCurrentPosition(function(position) {
            var txt = "Position obtenu : (";
            pos = position.coords.latitude + "/" + position.coords.longitude;
            txt += pos + ")";
            divTexte.innerHTML = "<p>" + txt + "</p>";
            divBtnCarte.style.display = "block";
        });
    } else {
        /* la géolocalisation n'est pas disponible */
        alert("Problem guy's");
    }
}

laCarte.onclick = () => {
    window.location.href="https://www.openstreetmap.org/#map=20/" + pos;    
}
