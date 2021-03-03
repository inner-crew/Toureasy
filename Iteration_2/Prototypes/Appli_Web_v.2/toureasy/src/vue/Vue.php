<?php

namespace toureasy\vue;
use toureasy\models\Image;
use toureasy\models\ListeMonument;
use toureasy\models\Membre;
use toureasy\models\Monument;

class Vue
{

    private $data;

    const MESSAGE = 0;

    const HOME = 1;

    const AJOUTER_MONUMENT = 2;

    const MAP = 3;

    const CONNEXION = 4;

    const VUE_ENSEMBLE = 5;

    const LISTE = 6;

    const MONUMENT = 7;

    const AJOUTER_LISTE = 8;

    const PROFIL = 9;

    const MODIFIER_MONUMENT = 10;

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

    private function pageProfil(Membre $m, Array $v): string
    {
        $html = <<<END
    
<div class="bt-top">
    <button>Contact</button>
    <button>Propos</button>
</div>
<img src="{$v['basepath']}/web/img/Logo_genial.png" class="logo">
<h1> Tour Easy </h1>
<hr>
<h2> Mon espace perso </h2>
<form method="post">
    <div class="container">
        <section class="section">
            <header>
                <h3>Profil</h3>
                <img src="{$v['basepath']}/web/img/avatar.png" class="avatar">
            </header>
            <div class="content">
                <h6>Prénom <input type="text" name="prenom" class="input-profil" readonly="readonly" value="{$m->prenom}"></h6>
                <h6>Nom <input type="text" name="nom" class="input-profil" readonly="readonly" value="{$m->nom}"></h6>
                <h6>Sexe <input type="text" name="sexe" class="input-profil" readonly="readonly" value="{$m->sexe}"></h6>
                <h6>Date de naissance <input type="date" name="naissance" class="input-profil" readonly="readonly" value="{$m->dateNaissance}"></h6>
                <h6>Token <input type="text" readonly="readonly" value="{$m->token}" ></h6>
            </div>
        </section>
    </div>
    <div class="container">
        <section class="section">
            <header>
                <h3>Coordonnées</h3>
            </header>
            <div class="content">
                <h6>Adresse e-mail <input type="text" name="mail" class="input-profil" readonly="readonly" value="{$m->email}"></h6>
            </div>
        </section>
    </div>
<div class="container">
    <section class="section">
        <header>
            <h3>Statistiques</h3>
        </header>
        <div class="content">
        </div>
    </section>
</div>
<div class="bt-bottom">
    <button id="bt-modif">Modifier</button>
    <button>Accéder à la carte</button>
</div>
</form>
<script>
    function modifie() {
        let input = document.getElementsByClassName("input-profil")

        for (let i = 0; i < input.length; i++) {
            input[i].readOnly = false;
        }

        let container = document.querySelector('.bt-bottom');
        container.innerHTML = ''

        let valider = document.createElement('input')
        valider.type = 'submit'
        container.appendChild(valider)
    }

    let bt = document.getElementById("bt-modif")

    bt.addEventListener('click', modifie);

</script>

END;
        return $html;
    }

    public function monEspace($arrayListeUtilisateur, $arrayMonumentsPrives, $arrayMonumentsPublics, $vars) {
        $basepath = $vars['basepath'];
        $html = <<<END
<section class="titre">
            <h3 class="nom">Vos Listes</h3>
            <p class="desc">Tableau regroupant toutes vos listes</p>
            <button onclick="location.href='{$vars['createListe']}'">Creer une liste</button>
        </section>
END;

        if (sizeOf($arrayListeUtilisateur)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($arrayListeUtilisateur); $i++) {
                $html .= $this->uneLigneListeMonEspace($arrayListeUtilisateur[$i][0], $basepath, $arrayListeUtilisateur[$i][1]);
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


        if (sizeOf($arrayMonumentsPrives)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($arrayMonumentsPrives); $i++) {
                $html .= $this->uneLigneMonumentMonEspace($arrayMonumentsPrives[$i][0], $basepath, $arrayMonumentsPrives[$i][1]);
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


        if (sizeOf($arrayMonumentsPublics)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($arrayMonumentsPublics); $i++) {
                $html .= $this->uneLigneMonumentMonEspace($arrayMonumentsPublics[$i][0], $basepath, $arrayMonumentsPublics[$i][1]);
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

    private function uneLigneListeMonEspace(ListeMonument $liste, $basepath, $url): string
    {
        $html = <<<END
                <tr>
                    <td><a href="$url">$liste->nom</a></td>
                    <td><p class="nom">$liste->dateCreation</p></td>
                </tr>
END;
        return $html;
    }

    private function uneLigneMonumentMonEspace(Monument $liste, $basepath, $url): string
    {
        $html = <<<END
                <tr>
                    <td><a href="$url">$liste->nomMonum</a></td>
                </tr>
END;
        return $html;
    }

    public function uneListeHtml(ListeMonument $liste, $monumentsDeCetteListe, $monumentsDeUtilisateur ,$vars): string
    {
        $html = <<<END
<section class="titreListe">
            <h3 class="nom">Nom de la liste : {$liste->nom}</h3>
            <p class="desc">Description : {$liste->description}</p>
        </section>
        </br>
END;
        if (sizeOf($monumentsDeCetteListe)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                </tr>
END;
            for ($i = 0; $i < sizeOf($monumentsDeCetteListe); $i++) {
                $html .= $this->uneLigneMonumentListe($monumentsDeCetteListe[$i][0], $monumentsDeCetteListe[$i][1]);
            }
            $html .= <<<END
                
              </table>
        </section>
END;

        } else {
            $html .= "<p>Aucuns monuments dans cette liste</p></br>";
        }

        if (sizeof($monumentsDeUtilisateur) > 0) {
            $html .= <<<END
<form method="post">
<select name="monuments" id="monument-selected">
    <option value="">--Choisissez un monument à ajouter--</option>
END;

            foreach ($monumentsDeUtilisateur as $monument) {
                $html .= "<option value='{$monument->idMonument}'>{$monument->nomMonum}</option>";
            }
            $html .= "</select> <input type='submit' value='OK'></form>";
        }

        return $html;
    }

    private function uneLigneMonumentListe(Monument $monument, $url): string {
        $html = <<<END
            
                <tr>
                    <td><a href="$url">$monument->nomMonum</a></td>
                    <td></td>
                </tr>
END;
        return $html;
    }

    public function unMonumentHtml(Monument $monument, array $images, $vars): string {
        $html = <<<END
        <section class="infos">
            <h3 class="nom">Nom : {$monument->nomMonum}</h3>
            <p class="desc">Description : {$monument->descLongue}</p>
END;

        foreach ($images as $img) {
            $html .= "<img width='50%' src='{$vars['basepath']}/{$img['urlImage']}'>";
        }

        $html .= <<<END
</section>
        <div><button onclick="window.location.href='{$vars['modifierMonument']}'">Modifier</button></div>
END;

        return $html;
    }

    public function modifierUnMonument(Monument $monument, Image $img, $vars): string
    {
        return <<<END
<form method="post" enctype="multipart/form-data" id="add">
    <p>Nom du monument : <input name="nom" value="{$monument->nomMonum}"></p>
    <div>
                <p>Description</p>
                <input  type="file" value="c:\\t.txt" name="fichier"/><input type="hidden" name="lat"/><input type="hidden" name="long"/> <br>
                <div>
                    <p><input type="button" name="text" value="B" onclick="formatText('B')" checked>
                    <input type="button" name="text" value="I" onclick="formatText('I')" checked>
                    <input type="button" name="text" value="U" onclick="formatText('U')" checked>
                    Taille : <input type="number" min="1" max="20" name="text" value="3" id="taille" onclick="formatText('T')"/>
                    </p>
                    <textarea name="desc" id="area" cols="60" rows="10" style="display:none"></textarea>
                    <iframe name="frm" id="frm"></iframe>
                    <input type="hidden" name="descr" value="{$monument->descLongue}"/>
                </div>
            </div>
            <input type="button" onclick="submitForm()" value="Valider">
</form>
<script src="{$vars['basepath']}/web/js/textEditor.js"></script>
<script>

window.onload = function ()
{
    document.getElementById('frm').contentDocument.body.innerHTML = document.getElementsByName("descr")[0].value;
    document.getElementById('frm').contentDocument.designMode = "on"
};
    
</script>
END;

    }

    private function ajoutMonumentHtml($vars): string
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
            <input type="file" multiple="multiple" name="fichier[]"/><input type="hidden" name="lat"/><input type="hidden" name="long"/> <br>
            
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
            

            <input type="button" onclick="submitForm()" value="Valider">
</form>
<script src="{$vars['basepath']}/web/js/textEditor.js"></script>
END;
        return $html;
    }

    public function ajoutListeHtml(): string
    {
        return <<<END
        <form method="post" enctype="multipart/form-data">
            <p>Nom<span class="required">*</span> : <input type="text" name="nom" required/></p>
            <p>Description<span class="required">*</span> : <input type="text" name="desc" required/></p>
            <p><input type="submit" value="OK"></p>
        </form>
END;
    }

    public function render(array $vars, int $typeAffichage): string
    {
        $content = null;
        switch ($typeAffichage) {
            case Vue::MESSAGE:
                $content = $this->unMessage($vars);
                break;
            case Vue::HOME:
                $content = $this->homeHtml($vars);
                break;
            case Vue::AJOUTER_MONUMENT:
                $content = $this->ajoutMonumentHtml($vars);
                break;
            case Vue::MAP:
                $content = $this->affichageMap($vars);
                break;
            case Vue::CONNEXION:
                $content = $this->connexionHtml();
                break;
            case Vue::VUE_ENSEMBLE:
                $content = $this->monEspace($this->data[0], $this->data[1], $this->data[2], $vars);
                break;
            case Vue::LISTE:
                $content = $this->uneListeHtml($this->data[0], $this->data[1], $this->data[2], $vars);
                break;
            case Vue::MONUMENT:
                $content = $this->unMonumentHtml($this->data[0], $this->data[1], $vars);
                break;
            case Vue::AJOUTER_LISTE:
                $content = $this->ajoutListeHtml();
                break;
            case Vue::PROFIL:
                $content = $this->pageProfil($this->data[0], $vars);
                break;
            case Vue::MODIFIER_MONUMENT:
                $content = $this->modifierUnMonument($this->data[0], $this->data[1], $vars);
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