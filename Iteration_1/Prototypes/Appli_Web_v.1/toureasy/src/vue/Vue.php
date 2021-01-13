<?php

namespace toureasy\vue;
class Vue
{

    private $data;

    /**
     * constante correspondant à l'affichage de la page d'accueil
     */
    const HOME = 1;

    /**
     * constante correspondant à l'affichage du formulaire d'ajout d'un monument
     */
    const AJOUTER_MONUMENT = 2;

    /**
     * méthode affichant la page d'accueil de Toureasy
     * @param array $v variables contenant les liens des boutons présents sur la page
     * @return string code HTML de la page
     */
    private function homeHtml(array $v): string
    {
        $html = <<<END
        <body class="boutons-bottom">
            {$v['contact']}
            {$v['about-us']}
        </body>
        <header class="logo">
            <img src="{$v['basepath']}/web/img/Logo_genial.png"/>
            <h1>Toureasy</h1>
        </header> 
        <div class="fadein">
	        <img id="photo" src="{$v['basepath']}/web/img/diapo1.jpg">
	        <input type="hidden" id="lien" name="passvar"  value= "{$v['basepath']}/web/img/" />
            <script>
                var lien = document.getElementById("lien").value;
                
                window.addEventListener("load", ()=>{
                    //sources[i] est le nom du fichier image de l'image numero i
                    let sources = new Array('diapo1.jpg','diapo2.jpg','diapo3.jpg','diapo4.jpg');
                
                    let i=0; //indice de l'image courante
                
                    let img = document.getElementById("photo");
                    img.addEventListener("click", () => {suivante()});
                
                //Affiche l'image suivant l'image courante
                function suivante(){
                    img.src=lien+sources[i];
                    if(i==3)i=0;
                    else i++;
                }
                
                setInterval(() => {suivante(); }, 3000);
                });
                
            </script>
        </div>
        <footer class="boutons-centre">
            {$v['connexion']}
            {$v['map']}
        </footer>

END;
        return $html;
    }

    private function ajoutMonumentHtml(): string
    {
        $html = <<<END
<form method="post" enctype="multipart/form-data">
            <p>Nom du monument<span class="required">*</span> : <input type="text" name="nom" required/></p>
            <input type="file" name="fichier"/><input type="hidden" name="lat"/><input type="hidden" name="long"/> <br>
            <p>Description<span class="required">*</span> : <input type="text" name="desc" required/></p>
            <p><input type="submit" value="Valider" </p>
        </form>
<script>

    window.onload=function getPos(){
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
    
</script>
END;
        return $html;
    }

    /**
     * @param array $vars array de variables (htmlvars)
     * @param int $typeAffichage valeur correspondant à une constante de cette classe
     * @return string code HTML de la page
     */
    public function render(array $vars, int $typeAffichage): string
    {
        $content = null;
        switch ($typeAffichage)
        {
            // affichage de la page d'accueil de Toureasy
            case Vue::HOME:
                $content = $this->homeHtml($vars);
                break;
            // affichage de la page permettant d'ajouter un monument
            case Vue::AJOUTER_MONUMENT:
                $content = $this->ajoutMonumentHtml();
                break;
        }
        $html = <<<END
<!DOCTYPE html>
<html>
    <head>
        <title>Toureasy</title>
        <link rel="stylesheet" href="{$vars['basepath']}/web/css/index.css">
    </head>
    <body>
        $content
    </body>
</html>
END;
        return $html;
    }

}