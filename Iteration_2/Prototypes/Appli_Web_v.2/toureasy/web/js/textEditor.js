window.onload = function ()
{
    if ((document.getElementsByName("lat").length > 0)) {
        getPos()
    }
    loadFrame()
};

function submitForm() {
    form = document.getElementById("add")
    area = document.getElementById("area")

    area.value = window.frames['frm'].document.body.innerHTML.toString()
    form.submit()
}

function getPos(){
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(position => {
            document.getElementsByName("lat")[0].value = position.coords.latitude;
            document.getElementsByName("long")[0].value = position.coords.longitude;
        });
    } else {
        document.getElementsByName("lat")[0].value="error";
        document.getElementsByName("long")[0].value="error";
    }
}

function loadFrame() {
    frame = document.getElementById("frm");
    frame.contentDocument.designMode = "on"
}

function formatText(bouton) {
    frame = frm.document;

    switch (bouton) {
        case 'B':
            frame.execCommand('bold', false, null)
            break;
        case 'I':
            frame.execCommand('italic', false, null)
            break;
        case 'U':
            frame.execCommand('underline', false, null)
            break;
        case 'T':
            taille = document.getElementById("taille").value;
            frame.execCommand('fontSize', false, taille)
            break;

    }
}
