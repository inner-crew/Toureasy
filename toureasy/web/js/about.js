const lesPP = document.querySelectorAll('.pp');
const basepath = lesPP[0].getAttribute("src").slice(0, lesPP[0].getAttribute("src").lastIndexOf("/"));

document.addEventListener('keydown', (key) => {
    lesPP.forEach((unePP) => {
        if (key.key === "Control" && unePP.getAttribute("src").length !== 6) {
            unePP.setAttribute("src", basepath + "lesPPs/" + unePP.getAttribute("id") + ".png");
        }
    });
});

document.addEventListener('keyup', (key) => {
    lesPP.forEach((unePP) => {
        if (key.key === "Control" && unePP.getAttribute("src").length === 6) {
            let fileName = "";
            switch (unePP.getAttribute("id")) {
                case "sb" :
                    fileName = "Silvio";
                    break;
                case "rz" :
                    fileName = "Remrem";
                    break;
                case "nf" :
                    fileName = "Nicolixxx";
                    break;
                case "am" :
                    fileName = "Jock";
                    break;
            }
            unePP.setAttribute("src", basepath + fileName + ".png");
        }
    })
});