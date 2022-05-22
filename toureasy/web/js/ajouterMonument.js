window.onload = function ()
{
    document.getElementById('frm').contentDocument.designMode = "on"
    document.getElementById("file-input").addEventListener("change", function() {
        setGrid()
        readURL(this);
    })
};

var arrayFiles = []

function setGrid() {
    document.getElementById('galerie').className = "wrapper";
    document.getElementById('smallImageUpload').style.display = 'none';
}

function readURL(input) {
    var paras = document.getElementsByClassName('added');

    while(paras[0]) {
        paras[0].parentNode.removeChild(paras[0]);
    }
    for (var i = 0; i < input.files.length; i++) {
        arrayFiles.push(input.files[i])
        var reader = new FileReader();
        reader.onload = function (e) {
            div = document.createElement("div")
            div.className = "cell added"
            img = document.createElement("img")
            img.src = e.target.result
            div.appendChild(img)
            let galerie = document.getElementById('galerie');
            let nbChild = galerie.childNodes.length
            galerie.insertBefore(div, galerie.childNodes[nbChild-2])
        }
        reader.readAsDataURL(input.files[i]);
    }
}