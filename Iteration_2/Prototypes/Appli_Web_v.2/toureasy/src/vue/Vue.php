<?php

namespace toureasy\vue;
use toureasy\models\ListeMonument;
use toureasy\models\Monument;

class Vue
{

    private $data;

    /**
     * constante correspondant à l'affichage d'un message de redirection
     */
    const MESSAGE = 0;

    /**
     * constante correspondant à l'affichage de la page d'accueil
     */
    const HOME = 1;

    /**
     * constante correspondant à l'affichage du formulaire d'ajout d'un monument
     */
    const AJOUTER_MONUMENT = 2;

    /**
     * constante correspondant à l'affichage de la carte
     */
    const MAP = 3;

    /**
     * constante correspondant à l'affichage de la page de connexion
     */
    const CONNEXION = 4;

    const VUE_ENSEMBLE = 5;

    const LISTE = 6;

    const MONUMENT = 7;

    const AJOUTER_LISTE = 8;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function unMessage($vars): string
    {
        $html = <<<END
<p class="message">{$vars['message']}</p>
<button onclick="window.location.href='{$vars['url']}'">Ok</button>
END;
        return $html;
    }

    /**
     * méthode affichant la page d'accueil de Toureasy
     * @param array $v variables contenant les liens des boutons présents sur la page
     * @return string code HTML de la page
     */
    private function homeHtml(array $v): string
    {
        $html = <<<END
        <div class="boutons-top" id="top-rigth">
            <button onclick="location.href='{$v['contact']}'">Nous Contacter</button>
            <button onclick="location.href='{$v['about-us']}'">A propos</button>
        </div>
        <header class="logo" id="top">
            <img src="{$v['basepath']}/web/img/Logo_genial.png"/>
            <h1>Toureasy</h1>
        </header> 
        <div class = "cadre-diapo" id="center">
	        <img class="diapo" src="{$v['basepath']}/web/img/diapo1.jpg" alt>
	        <img class="diapo" src="{$v['basepath']}/web/img/diapo2.jpg" alt>
            <img class="diapo" src="{$v['basepath']}/web/img/diapo3.jpg" alt>
            <img class="diapo" src="{$v['basepath']}/web/img/diapo4.jpg" alt>
            <button class="precedent" aria-label="précédent" onclick="boutons(-1)">❮</button>
            <button class="suivant" aria-label="suivant" onclick="boutons(1)">❯</button>
        </div> 
        <script>
            var diaporama = 1;
            affichage(diaporama);
                    
            function boutons(n) {
                affichage(diaporama += n);
            }
                    
            function affichage(n) {
                var i;
                var diapoImg = document.getElementsByClassName("diapo");
                if (n > diapoImg.length) {diaporama = 1}    
                if (n < 1) {diaporama = diapoImg.length}
                for (i = 0; i < diapoImg.length; i++) {
                    diapoImg[i].style.opacity = "0";
                }
                diapoImg[diaporama-1].style.opacity = "1";    
                indic[diaporama-1].className += " numeros";
            } 

        </script>
        </div>
        <footer class="boutons-footer" id="button">
            <button onclick="location.href='{$v['map']}'">Accéder à Toureasy</button>
        </footer>

END;
        return $html;
    }

    private function ajoutMonumentHtml(): string
    {
        $html = <<<END
<form method="post" enctype="multipart/form-data" id="add">
            <p>Nom du monument<span class="required">*</span> : <input type="text" name="nom" required/></p>
            <div>
                <input type="radio" id="private" name="visibilite" value="private" checked>
                <label for="huey">Privé</label>
                <input type="radio" id="public" name="visibilite" value="public">
                <label for="dewey">Publique</label>
            </div></br>
            <input type="file" name="fichier"/><input type="hidden" name="lat"/><input type="hidden" name="long"/> <br>
            
            <div>
                <p>Description</p>
                <div>
                    <p><input type="button" name="text" value="B" onclick="formatText('B')" checked>
                    <input type="button" name="text" value="I" onclick="formatText('I')" checked>
                    <input type="button" name="text" value="U" onclick="formatText('U')" checked>
                    Taille : <input type="number" min="1" max="20" name="text" value="3" id="taille" onclick="formatText('T')"/>
                    </p>
                    <textarea name="desc" id="area" cols="60" rows="10" style="display:none"></textarea>
                    <iframe name="frm" id="frm"></iframe>
                </div>
            </div>
            

            <input type="button" onclick="submitForm()" value="Valider"
</form>
<script>

    window.onload = function ()
    {
        getPos()
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
    
</script>
END;
        return $html;
    }

    private function affichageMap(array $v): string
    {
        $html = <<<END
    <script src="{$v['basepath']}/web/carteSetting/js/API_MapBox/main_mapbox-gl.js"></script>                                       <!--Le js principal de la l'api mapBox-->
    <script type="text/javascript" src="{$v['basepath']}/web/carteSetting/js/API_MapBox/soleil_suncalc.min.js"></script>            <!--Le script pour calculer la pos du soleil pour faire le ciel-->
    <script src="{$v['basepath']}/web/carteSetting/js/API_MapBox/doubleMap_mapbox-gl-compare.js"></script>                          <!-- le script qui fait fonctionner le swap entre les 2 maps-->
    <script src="{$v['basepath']}/web/carteSetting/js/API_MapBox/barreDeRecherche_mapbox-gl-geocoder.min.js"></script>              <!--le script de la recherche de lieu (geocoder)-->
    <script src="{$v['basepath']}/web/carteSetting/js/index.js"></script>                                                           <!--le script de toureasy-->

    <link href="{$v['basepath']}/web/carteSetting/css/main_mapbox-gl.css" rel="stylesheet"/>                                        <!--Le css principal de la l'api mapBox-->
    <link href="{$v['basepath']}/web/carteSetting/css/index.css" rel="stylesheet"/>                                                 <!--Le css principal de la page web-->
    <link rel="stylesheet" href="{$v['basepath']}/web/carteSetting/css/doubleMap_mapbox-gl-compare.css" type="text/css"/>           <!--le css qui gère la transition des 2 maps-->
    <link rel="stylesheet" href="{$v['basepath']}/web/carteSetting/css/barreDeRecherche_mapbox-gl-geocoder.css" type="text/css"/>   <!--css de la recherche de lieu-->

<div id="comparison-container">
    <div id="before" class="map"></div>
    <pre id="geoPos"></pre>
    <div id="after" class="map"></div>
</div>
<input type= "image" id="btn" src="{$v['basepath']}/web/carteSetting/data/images/mark.png" width="100px" height="200px"/>

<script src="{$v['basepath']}/web/carteSetting/js/index.js"></script>   <!--le script de toureasy (il est lourd)-->
END;
        return $html;
    }

    public function connexionHtml(): string
    {
        $html = <<<END
<form method="post">
    <p>J'ai un token : <input type="text" name="token"/><input type="submit" name="action" value="OK" /></p>
    <input type="submit" name="action" value="Obtenir un token" />
</form>
END;
        return $html;
    }

    public function vueEnsembleHtml($vars) {
        $html = <<<END
<section class="titre">
            <h3 class="nom">Vos Listes</h3>
            <p class="desc">Tableau regroupant toutes vos listes</p>
            <button onclick="location.href='{$vars['createListe']}'">Creer une liste</button>
        </section>
END;

        if (sizeOf($vars['listes'])>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($vars['listes']); $i++) {
                $html .= $this->uneLigneListeHtml($vars['listes'][$i][0], $vars['basepath'], $vars['listes'][$i][1]);
            }
            $html .= <<<END
                
              </table>
          </section>
END;


        } else {
            $html .= <<<END
<p>Vous n'avez pas encore créé de liste </p>
END;

        }

        $html .= <<<END
<section class="titre">
            <h3 class="nom">Vos Monuments Privés</h3>
            <p class="desc">Tableau regroupant tous vos monuments privés</p>
            <button onclick="location.href='{$vars['createMonument']}'">Creer un monument</button>
        </section>
END;


        if (sizeOf($vars['monumentsPrivate'])>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($vars['monumentsPrivate']); $i++) {
                $html .= $this->uneLigneMonumentPrivateHtml($vars['monumentsPrivate'][$i][0], $vars['basepath'], $vars['monumentsPrivate'][$i][1]);
            }
            $html .= <<<END
                
              </table>
          </section>
END;


        } else {
            $html .= "<p>Vous n'avez pas encore créé de monument privé</p>";
        }

        $html .= <<<END
<section class="titre">
            <h3 class="nom">Vos Monuments Publics</h3>
            <p class="desc">Tableau regroupant tous vos monuments publics</p>
            <button onclick="location.href='{$vars['createMonument']}'">Creer un monument</button>
        </section>
END;


        if (sizeOf($vars['monumentsPublic'])>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($vars['monumentsPublic']); $i++) {
                $html .= $this->uneLigneMonumentPrivateHtml($vars['monumentsPublic'][$i][0], $vars['basepath'], $vars['monumentsPublic'][$i][1]);
            }
            $html .= <<<END
                
              </table>
          </section>
END;


        } else {
            $html .= "<p>Vous n'avez pas encore créé de monument publics</p>";
        }

        return $html;

    }

    private function uneLigneListeHtml(ListeMonument $liste, $basepath, $url): string
    {
        $html = <<<END
                <tr>
                    <td><a href="$url">$liste->nom</a></td>
                    <td><p class="nom">$liste->dateCreation</p></td>
                </tr>
END;
        return $html;
    }

    private function uneLigneMonumentPrivateHtml(Monument $liste, $basepath, $url): string
    {
        $html = <<<END
                <tr>
                    <td><a href="$url">$liste->nomMonum</a></td>
                </tr>
END;
        return $html;
    }

    public function uneListeHtml(ListeMonument $liste, $vars) {
        $html = <<<END
<section class="titreListe">
            <h3 class="nom">{$liste->nom}</h3>
            <p class="desc">{$liste->description}</p>
        </section>
END;
        if (sizeOf($vars['objets'])>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($vars['objets']); $i++) {
                $html .= $this->unMonument($vars['objets'][$i][0], $vars['objets'][$i][1]);
            }
            $html .= <<<END
                
              </table>
        </section>
END;

        } else {
            $html .= "<p>Aucuns items dans cette liste</p>";
        }
        return $html;
    }

    private function unMonument(Monument $monument, $url): string {
        $html = <<<END
            
                <tr>
                    <td><a href="$url">$monument->nomMonum</a></td>
                </tr>
END;
        return $html;
    }

    public function unMonumentHtml(Monument $monument, $url): string {
        $html = <<<END
            
                <section class="infos">
            <h3 class="nom">{$monument->nomMonum}</h3>
            <p class="desc">{$monument->descLongue}</p>
        </section>
END;
        return $html;
    }

    public function ajoutListeHtml() {
        $html = <<<END
        <form method="post" enctype="multipart/form-data">
            <p>Nom<span class="required">*</span> : <input type="text" name="nom" required/></p>
            <p>Description<span class="required">*</span> : <input type="text" name="desc" required/></p>
            <p><input type="submit" value="OK"></p>
        </form>
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
        switch ($typeAffichage) {
            // affichage d'un message de redirection
            case Vue::MESSAGE:
                $content = $this->unMessage($vars);
                break;
            // affichage de la page d'accueil de Toureasy
            case Vue::HOME:
                $content = $this->homeHtml($vars);
                break;
            // affichage de la page permettant d'ajouter un monument
            case Vue::AJOUTER_MONUMENT:
                $content = $this->ajoutMonumentHtml();
                break;
            // affichage de la carte
            case Vue::MAP:
                $content = $this->affichageMap($vars);
                break;
            // affichage de la page de connexion
            case Vue::CONNEXION:
                $content = $this->connexionHtml();
                break;
            case Vue::VUE_ENSEMBLE:
                $content = $this->vueEnsembleHtml($vars);
                break;
            case Vue::LISTE:
                $content = $this->uneListeHtml($this->data[0], $vars);
                break;
            case Vue::MONUMENT:
                $content = $this->unMonumentHtml($this->data[0], $vars);
                break;
            case Vue::AJOUTER_LISTE:
                $content = $this->ajoutListeHtml();
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