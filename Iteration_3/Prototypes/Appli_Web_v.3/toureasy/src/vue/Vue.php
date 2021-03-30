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

    const TEST = 11;

    const DEMANDE_AMI = 12;

    const MODIFIER_LISTE = 13;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function unMessage($vars): string
    {
        $html = <<<END
    <header>
        <div class="menu-btn">
            <div class="menu-btn__burger"></div>
        </div>
        <h1 id="name">Toureasy</h1>
    </header>
    <div class="container">
    <div>
            <ul id="menu">
END;

        $html .= $this->insererMenu($vars);

        $html .= <<<END
        </div>
        <div class="message">
            <h1>{$vars['message']}</h1>
            <div class="box">
            <button onclick="window.location.href='{$vars['url']}'">Ok</button>
</div>
            
        </div>
        
    </div>

END;
        return $html;
    }

    private function homeHtml(array $v): string
    {
        $html = <<<END
        <header>
            <div class="menu-btn">
                <div class="menu-btn__burger"></div>
            </div>
            <h1 id="name">Toureasy</h1>
        </header>
        
        
        
        <div class="container">
            <div>
                <ul id="menu">
END;

        $html .= $this->insererMenu($v);

        $html .= <<<END
            </div>
            <div id="home">
                <img id="logo-small" src='{$v['basepath']}/web/img/logo2.png'>
                <div class="box">
                    <button id="homeBouton" onclick="location.href='{$v['map']}'">Accéder à Toureasy</button>
                </div>
                <p id="slogan">Découvrez des monuments du monde entier en un clique !</p>
            </div>
        </div>
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

    <link href="{$v['basepath']}/web/carteSetting/css/main_mapbox-gl.css" rel="stylesheet"/>                                        <!--Le css principal de la l'api mapBox-->
    <link href="{$v['basepath']}/web/carteSetting/css/index.css" rel="stylesheet"/>                                                 <!--Le css principal de la page web-->
    <link rel="stylesheet" href="{$v['basepath']}/web/carteSetting/css/doubleMap_mapbox-gl-compare.css" type="text/css"/>           <!--le css qui gère la transition des 2 maps-->
    <link rel="stylesheet" href="{$v['basepath']}/web/carteSetting/css/barreDeRecherche_mapbox-gl-geocoder.css" type="text/css"/>   <!--css de la recherche de lieu-->

<div id="comparison-container">
    
</div>
<label class="switch">
  <input id="3dSwitch" type="checkbox">
  <span class="slider"></span>
</label>
<p id="txt3D">3D On/Off</p>
<!--<input type= "image" id="btn" src="{$v['basepath']}/web/carteSetting/data/images/mark.png" width="100px" height="200px"/>-->
<select id="monumentAfficher">
  
</select>

<script type="module" src="{$v['basepath']}/web/carteSetting/js/index.js"></script>   <!--le script de toureasy (il est lourd)-->
END;
        return $html;
    }

    public function connexionHtml($v): string
    {
        $html = <<<END
<header>
        <div class="menu-btn">
            <div class="menu-btn__burger"></div>
        </div>
        <h1 id="name">Toureasy</h1>
    </header>
<div class="container">
<div>
            <ul id="menu">
END;
        $html .= $this->insererMenu($v);
        $html .= <<<END
        </div>
    <div id="formConnexion">
        <h1>J'ai un code d'identification</h1>
        <form method="post">
            <p>
                <div class="box">
                    <input type="text" name="token"/> 
                    <input type="submit" name="action" value="OK" />
                </div>
            </p>
            <h1>C'est ma première fois</h1>
            <div class="box">
                <input type="submit" name="action" value="Obtenir un token" />
            </div>
        </form>
    </div>
</div>

END;
        return $html;
    }

    private function pageProfil(Membre $m, Array $v): string
    {
        $html = <<<END
<header>
        <div class="menu-btn">
            <div class="menu-btn__burger"></div>
        </div>
        <h1 id="name">Toureasy</h1>
</header>

<div class="container">
    <div>
        <ul id="menu">
END;
        $html .= $this->insererMenu($v);
        $html .= <<<END
    </div>
<div id="formProfil">
    
    <form method="post">
        <div>
        <h3> Mon Profil </h3>
            <section class="section">
                <div class="content">
                    <h5>Prénom </h5><input type="text" name="prenom" class="input-profil longInputAvecMargin"  readonly="readonly" value="{$m->prenom}">
                    <h5>Nom</h5><input type="text" name="nom" class="input-profil longInputAvecMargin" readonly="readonly" value="{$m->nom}">
                    <h5>Sexe</h5>
                    <select name="sexe" disabled="true" id="select" class="input-profil longSelectAvecMargin">
                    <option value="" >--Choisissez une option--</option>
END;
        if ($m->sexe === "non-renseigné") {
            $html .= <<<END
 <option value='x' selected="">Non-renseigné</option>
 END;
        } else {
            $html .= <<<END
 <option value='x'>Non-renseigné</option>
 END;
        }

        if ($m->sexe === "homme") {
            $html .= <<<END
 <option value='m' selected="">Homme</option>
 END;
        } else {
            $html .= <<<END
 <option value="m">Homme</option>
 END;
        }

        if ($m->sexe === "femme") {
            $html .= <<<END
 <option value='f' selected="">Femme</option>
 END;
        } else {
            $html .= <<<END
<option value="f">Femme</option>
END;
        }
        $html .= <<<END
                    </select>
                    <h5>Date de naissance</h5><input type="date" name="naissance" class="input-profil longInputAvecMargin" readonly="readonly" value="{$m->dateNaissance}">
                    <h5>Token</h5><input type="text" readonly="readonly" class="input-token longInputAvecMargin" value="{$m->token}" >
                </div>
            </section>
        </div>
        <div>
        <h3>Coordonnées</h3>
            <section class="section">
                <div class="content">
                    <h5>Adresse e-mail </h5><input type="text" name="mail" class="input-profil longInputAvecMargin" readonly="readonly" value="{$m->email}">
                </div>
            </section>
        </div>
        <div class="bt-bottom box">
        <button id="bt-modif">Modifier</button>
        </div>
        </form>
    </div>
</div>


<script>
    function modifie() {
        let input = document.getElementsByClassName("input-profil")

        for (let i = 0; i < input.length; i++) {
            input[i].readOnly = false;
        }
        
        document.getElementById('select').disabled = false;

        let container = document.querySelector('.bt-bottom');
        container.innerHTML = ''

        let valider = document.createElement('input')
        valider.type = 'submit'
        valider.className = 'box'
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
<header>
        <div class="menu-btn">
            <div class="menu-btn__burger"></div>
        </div>
        <h1 id="name">Toureasy</h1>
</header>

<div class="container">
    <div>
        <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= <<<END
    </div>
<section class="sectionEspace">
    
    <div class="boxsmall">
    <h3 class="nomSection">Vos Listes</h3>
        <button onclick="location.href='{$vars['createListe']}'">+</button>
    </div>

END;

        if (sizeOf($arrayListeUtilisateur)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Lien de partage</th>
                    </tr>
                </thead>
                <tbody>
END;
            for ($i = 0; $i < sizeOf($arrayListeUtilisateur); $i++) {
                $html .= $this->uneLigneListeMonEspace($arrayListeUtilisateur[$i][0], $basepath, $arrayListeUtilisateur[$i][1]);
            }
            $html .= <<<END
                
              </tbody></table>
          </section></section>
END;


        } else {
            $html .= <<<END
<p>Vous n'avez pas encore créé de liste </p></section>
END;

        }

        $html .= <<<END
<section class="sectionEspace">
    <div class="boxsmall">
        <h3 class="nomSection">Vos Monuments Privés</h3>
        <button onclick="location.href='{$vars['createMonument']}'">+</button>
    </div>

END;


        if (sizeOf($arrayMonumentsPrives)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table class="content-table">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                </tr>
                </thead>
                <tbody>
END;
            for ($i = 0; $i < sizeOf($arrayMonumentsPrives); $i++) {
                $html .= $this->uneLigneMonumentMonEspace($arrayMonumentsPrives[$i][0], $basepath, $arrayMonumentsPrives[$i][1]);
            }
            $html .= <<<END
                
              </tbody></table>
          </section></section>
END;


        } else {
            $html .= "<p>Vous n'avez pas encore créé de monument privé</p></section>";
        }

        $html .= <<<END
<section class="sectionEspace">
    <div class="boxsmall">
        <h3 class="nomSection">Vos Monuments Publics</h3>
        <button onclick="location.href='{$vars['createMonument']}?publique=1'">+</button>
    </div>
END;


        if (sizeOf($arrayMonumentsPublics)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table class="content-table">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Lien de partage</th>
                    </tr>
                </thead>
                <tbody>
END;
            for ($i = 0; $i < sizeOf($arrayMonumentsPublics); $i++) {
                $html .= $this->uneLigneMonumentMonEspace($arrayMonumentsPublics[$i][0], $basepath, $arrayMonumentsPublics[$i][1]);
            }
            $html .= <<<END
                
              </tbody></table>
          </section></div></section>
END;


        } else {
            $html .= "<p>Vous n'avez pas encore créé de monument publics</p></div></section>";
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
<header>
    <div class="menu-btn">
        <div class="menu-btn__burger"></div>
    </div>
    <h1 id="name">Toureasy</h1>
</header>

<div class="container">
<div>
        <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= <<<END
    </div>
<section class="titreListe">
            <h3 class="nomSection">{$liste->nom}</h3>
            <i class="desc">{$liste->description}</i>
        
        <div class="box"><button id="modifier" onclick="window.location.href='{$vars['modifierListe']}'">Modifier</button></div>
        </section></br>
END;
        if (sizeOf($monumentsDeCetteListe)>0) {
            $html .= <<<END
                
        <section class="tableau">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Lien de partage</th>
                    </tr>
                </thead>
                <tbody>
                
END;
            for ($i = 0; $i < sizeOf($monumentsDeCetteListe); $i++) {
                $html .= $this->uneLigneMonumentListe($monumentsDeCetteListe[$i][0], $monumentsDeCetteListe[$i][1]);
            }
            $html .= <<<END
                </tbody>
              </table>
        </section>
END;

        } else {
            $html .= "<p id='message'>Aucuns monuments dans cette liste</p></br>";
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
            $html .= "</select> <div id='center'><input type='submit' value='OK'></div></form>";
        }
        return $html .= <<<END
<div id="back"><img onclick="back('{$vars['back']}')" src="{$vars['basepath']}/web/img/back.png"/></div></div>
</div>
<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;
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

    private function modifierUneListe(ListeMonument $liste, $vars): string {
        $html = <<<END
<header>
    <div class="menu-btn">
        <div class="menu-btn__burger"></div>
    </div>
    <h1 id="name">Toureasy</h1>
</header>

<div class="container">
<div>
        <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= <<<END
                <section class="titreListe">
                    <form method="post">
                        <h3 class="nomSection">Nom : <input id="lessMargin" type="text" name="nom" value="{$liste->nom}" /></h3>
                        <h3 class="nomSection">Description :<input id="lessMargin" type="text" name="desc" value="{$liste->description}" /></h3>
                        <div id="center"><input id="lessMargin" type="submit"  value="Valider" /></div>
                    </form>
                </section>
END;
        return $html;
    }

    public function unMonumentHtml(Monument $monument, array $images, $vars): string {
        $html = <<<END
<header>
    <div class="menu-btn">
        <div class="menu-btn__burger"></div>
    </div>
    <h1 id="name">Toureasy</h1>
</header>

<div class="container">
<div>
        <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= <<<END
    </div>
        <section class="infos">
            <h3 id="nom">{$monument->nomMonum}</h3>
            <p class="desc" id="descMonu">{$monument->descLongue}</p>
END;
        if (sizeof($images) > 0) {
            $html .= "<div class='wrapper' id='galerie'>";
            foreach ($images as $img) {
                $html .= <<<END
<div class='cell'>
    <img src='{$vars['basepath']}/{$img['urlImage']}'>
</div>
END;
            }
            $html .= "</div>";
        }


        $html .= <<<END
</section>
        <div class="box"><button onclick="window.location.href='{$vars['modifierMonument']}'">Modifier</button></div>
<div id="back"><img onclick="back('{$vars['back']}')" src="{$vars['basepath']}/web/img/back.png"/></div></div>
<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;

        return $html;
    }

    public function modifierUnMonument(Monument $monument, array $arrayImg, $vars): string
    {
        $html = <<<END
<header>
    <div class="menu-btn">
        <div class="menu-btn__burger"></div>
    </div>
    <h1 id="name">Toureasy</h1>
</header>
<div class="container">
<div>
        <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= <<<END
    </div>
<form method="post" enctype="multipart/form-data" id="add">
    <input type="hidden" id="delete" name="delete"/>
    <h3 id="modifNom">Nom du monument : <input name="nom" id='lessMargin' value="{$monument->nomMonum}" required></h3>
END;
            $html .= "<div class='wrapper' id='galerie'>";
            foreach ($arrayImg as $img) {
                $html .= <<<END
<div class='cell'>
    <div id="content">
        <img src="{$vars['basepath']}/web/img/cross.png" class="cross"/>
    </div>
    <img id="{$img['numeroImage']}" src='{$vars['basepath']}/{$img['urlImage']}'>
</div>
END;
            }
            $html .= <<<END
<div class="image-upload cell">
  <label for="file-input">
    <img src="{$vars['basepath']}/web/img/addImage.png"/>
  </label>
  <input id="file-input" type="file" multiple="multiple" name="fichier[]"/>
</div>
</div>
END;
        $html .= <<<END
                <br>
                <h3>Description</h3>
                <div id="menuEditor" >
                    <textarea name="desc" id="area" cols="60" rows="10" style="display:none"></textarea>
                    <iframe style="background: #FFFFFF" name="frm" id="frm"></iframe>
                    <input type="hidden" name="descr" value="{$monument->descLongue}"/>
                </div>
               
            <div id="center" class="box">
        <input type="button" onclick="submitForm()" value="Valider">
    </div>
</form>
<script src="{$vars['basepath']}/web/js/textEditor.js"></script>
<script src="{$vars['basepath']}/web/js/modifierMonument.js"></script>
END;

        return $html;

    }

    private function ajoutMonumentHtml($vars): string
    {
        $html = <<<END
<header>
    <div class="menu-btn">
        <div class="menu-btn__burger"></div>
    </div>
    <h1 id="name">Toureasy</h1>
</header>
<div class="container" id="lessLeft">
    <div>
        <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= <<<END
    </div>
    <form method="post" enctype="multipart/form-data" id="add">
                <h3>Nom du monument<span class="required"> *</span> : <input class="longInput" type="text" name="nom" required/></h3>
                <div id="center">
END;
        if ($vars['publique']) {
            $html .= <<<END
<input type="radio" id="private" name="visibilite" value="private">
<label for="huey">Privé</label>
<input type="radio" id="public" name="visibilite" value="public" checked>
<label for="dewey">Publique</label>
END;

        } else {
            $html .= <<<END
<input type="radio" id="private" name="visibilite" value="private" checked>
<label for="huey">Privé</label>
<input type="radio" id="public" name="visibilite" value="public" >
<label for="dewey">Public</label>
END;
        }

        $html .= <<<END
                    
                    
                </div></br>
                <div class="image-upload cell" id="addImages">
  <label for="file-input">
    <img id="smallImageUpload" src="{$vars['basepath']}/web/img/addImage.png"/>
  </label>
  <input id="file-input" type="file" multiple="multiple" name="fichier[]"/>
</div><input type="hidden" name="lat"/><input type="hidden" name="long"/> <br>
                
                <div>
                    <h3 id="descTitre">Description</h3>
                    <div id="menuEditor">
                        <textarea name="desc" id="area" cols="60" rows="10" style="display:none"></textarea>
                        <iframe style="background: #FFFFFF" name="frm" id="frm"></iframe>
                    </div>
                </div>
                
    <div id="center" class="box">
        <input type="button" onclick="submitForm()" value="Valider">
    </div>
               
    </form>
</div>

<script src="{$vars['basepath']}/web/js/textEditor.js"></script>
END;
        return $html;
    }

    public function ajoutListeHtml($vars): string
    {
        $html = <<<END
        <header>
            <div class="menu-btn">
                <div class="menu-btn__burger"></div>
            </div>
            <h1 id="name">Toureasy</h1>
        </header>
        <div class="container">
            <div>
                <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= <<<END
            </div>
            <form method="post" enctype="multipart/form-data">
                <h3 id="champ">Nom<span class="required">*</span></h3><input type="text" name="nom" class="longInput" required/>
                <h3 id="champ">Description<span class="required">*</span></h3><input type="text" class="longInput" name="desc" required/>
                <p id="center"><input type="submit" value="OK"></p>
            </form>
        </div>
END;
        return $html;
    }

    public function pageDemandeAmi($vars): string
    {
        return <<<END
<h1>Demande D'ami !</h1>
END;

        //TODO
    }
    public function pageTest($vars): string
    {
        return <<<END
        <h1>TEST</h1>
        <p>Amis::getAllAmisByIdMembre(2) : {$vars['r1']}</p>
        <p>DemandeAmi::getDemandeurByIdDemande(1) : {$vars['r2']} </p>
END;
    }

    private function insererMenu($vars): string {
        $html = "";
        if (isset($vars['menu']['profil'])) {
            $html .= <<<END
    <li><a href='{$vars['menu']['map']}'">Map</a></li>
    <li><a href='{$vars['menu']['espace']}'">Mon Espace</a></li>
    <li><a href='{$vars['menu']['profil']}'">Mon Profil</a></li>
    <li><a href='{$vars['menu']['ajout']}'">Ajouter un monument</a></li>
    </br>
    <li><a href='{$vars['menu']['contact']}'">Nous Contacter</a></li>
    <li><a href='{$vars['menu']['about-us']}'">A propos</a></li>
END;

        } else {
            $html .= <<<END
    <li><a href='{$vars['menu']['connexion']}'">Connexion</a></li>
    </br>
    <li><a href='{$vars['menu']['contact']}'">Nous Contacter</a></li>
    <li><a href='{$vars['menu']['about-us']}'">A propos</a></li>
END;
        }
        $html .= <<<END
</ul>
END;

        return $html;
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
                $content = $this->connexionHtml($vars);
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
                $content = $this->ajoutListeHtml($vars);
                break;
            case Vue::PROFIL:
                $content = $this->pageProfil($this->data[0], $vars);
                break;
            case Vue::MODIFIER_MONUMENT:
                $content = $this->modifierUnMonument($this->data[0], $this->data[1], $vars);
                break;
            case Vue::MODIFIER_LISTE:
                $content = $this->modifierUneListe($this->data[0], $vars);
                break;
            case Vue::TEST:
                $content = $this->pageTest($vars);
                break;
            case Vue::DEMANDE_AMI:
                $content = $this->pageDemandeAmi($vars);
                break;
        }


        $html = <<<END
<!DOCTYPE html>
<html>
    <head>
        <title>Toureasy</title>
        <link rel="stylesheet" href="{$vars['basepath']}/web/css/index.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    </head>
    <body>
        $content
    </body>
    <script src="{$vars['basepath']}/web/js/menu.js"></script>
</html>
END;

        return $html;
    }

}