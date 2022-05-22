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

    const ERREUR_SERVEUR = 14;

    const ABOUT = 15;

    const AMIS = 16;

    const AMISNOUVEAULIEN = 17;

    const MAP_MONUMENT = 18;

    const MAP_LISTE = 19;


    public function __construct($data)
    {
        $this->data = $data;
    }

    public function unMessage($vars): string
    {
        $html = $this->insererEnteteSite($vars);

        $html .= <<<END
        <div>
            <div class="w-full absolute flex items-center justify-center bg-modal">
                <div class="bg-white rounded shadow p-8 m-4 max-w-xs  text-center">
                    <div class="mb-8">
                        <p>{$vars['message']}</p>
                    </div>
                    <div class="flex justify-center">
                        <button onclick="window.location.href='{$vars['url']}'" class="flex-no-shrink text-white py-2 px-4 bg-green-700 rounded bg-teal hover:bg-green-500">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

END;
        return $html;
    }

    private function homeHtml(array $vars): string
    {
        $html = $this->insererEnteteSite($vars);

        $html .= <<<END
            <div id="home">
                <img id="logo-small" src='{$vars['basepath']}/web/img/logo2.png'>
                <div class="box">
                    <button id="homeBouton" onclick="location.href='{$vars['map']}'">Accéder à Toureasy</button>
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
    <input type="hidden" id="link" value="{$v['map']}">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js"></script>                                       <!--Le js principal de la l'api mapBox-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/suncalc/1.8.0/suncalc.min.js"></script>            <!--Le script pour calculer la pos du soleil pour faire le ciel-->
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-compare/v0.4.0/mapbox-gl-compare.js"></script>                          <!-- le script qui fait fonctionner le swap entre les 2 maps-->
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>              <!--le script de la recherche de lieu (geocoder)-->

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.css" rel="stylesheet"/>                                        <!--Le css principal de la l'api mapBox-->
    <link href="{$v['basepath']}/web/carteSetting/css/index.css" rel="stylesheet"/>                                                 <!--Le css principal de la page web-->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-compare/v0.4.0/mapbox-gl-compare.css" type="text/css"/>           <!--le css qui gère la transition des 2 maps-->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css" type="text/css"/>   <!--css de la recherche de lieu-->
END;

        $html .= $this->insererEnteteSite($v);

        $html .= <<<END

<div id="comparison-container">
    
</div>
<label class="switch">
  <input id="3dSwitch" type="checkbox">
  <span class="slider"></span>
</label>
<!--<input type= "image" id="btn" src="{$v['basepath']}/web/carteSetting/data/images/mark.png" width="100px" height="200px"/>-->
<select id="monumentAfficher">
  
</select>
</div>
<script type="module" src="{$v['basepath']}/web/carteSetting/js/index.js"></script>   <!--le script de toureasy (il est lourd)-->

END;
        return $html;
    }

    public function connexionHtml($vars): string
    {
        $html = <<<END
    <div class="h-screen w-screen flex items-center bg-gray-50">
        <div class="absolute bottom-0 w-screen">
         <img src="{$vars['basepath']}/web/img/background.png" class="w-screen hidden lg:flex">
</div>
        
        <div class="container flex flex-col md:flex-row items-center justify-between px-5 text-gray-700">
            <div class="w-full lg:w-1/2 mx-8 relative">
                <div class="text-7xl text-green-500 font-dark font-extrabold mb-8">Toureasy</div>
                <p class="text-2xl md:text-3xl font-light leading-normal mb-8 absolute">
                    Découvrez les monuments près de chez vous !
                </p>
            </div>

            <div class="w-full lg:flex lg:justify-end lg:w-1/2 mx-5 my-12">
                <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-md w-full space-y-8">
                        <div>
                            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">J'ai un code d'identification</h2>
                        </div>
                        <form class="mt-8 space-y-6" action="#" method="POST">
                            <input type="hidden" name="remember" value="true">
                            <div class="rounded-md shadow-sm -space-y-px">
                                <div>
                                    <label for="code" class="sr-only">Code d'identification</label>
                                    <input id="code" name="token" type="text" class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" placeholder="Code d'identification">
                                </div>
                            </div>

                            <div>
                                <button type="submit" name="action" value="OK" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <!-- Heroicon name: solid/lock-closed -->
            <svg class="h-5 w-5 text-green-500 group-hover:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </span>
                                    Connexion
                                </button>
                                <button type="submit" name="action" value="Obtenir un code" class="mt-3 group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
          </span>
                                    Inscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute top-0">
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
<style>
@import url(https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css);
@-webkit-keyframes fadeIn {
  from { opacity:0 }
  to { opacity:1 }
}
@keyframes fadeIn {
  from { opacity:0 }
  to { opacity:1 }
}
@-webkit-keyframes fadeInUp {
  from { opacity:0; transform:translate3d(0,10%,0) }
  to { opacity:1; transform:translate3d(0,0,0) }
}
@keyframes fadeInUp {
  from { opacity:0; transform:translate3d(0,10%,0) }
  to { opacity:1; transform:translate3d(0,0,0) }
}

dialog[open] { -webkit-animation-duration:0.3s; animation-duration:0.3s; -webkit-animation-fill-mode:both; animation-fill-mode:both; -webkit-animation-name:fadeInUp; animation-name:fadeInUp }
dialog::backdrop { background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(3px); -webkit-animation-duration:0.3s; animation-duration:0.3s; -webkit-animation-fill-mode:both; animation-fill-mode:both; -webkit-animation-name:fadeIn; animation-name:fadeIn  }
</style>

<div class="w-screen  bg-gray-100 flex items-center justify-center px-5 py-5 relative" x-data="{showCookieBanner:true}">
    <section class="w-full p-5 lg:px-24 absolute top-0 bg-gray-600" x-show="showCookieBanner">
        <div class="md:flex items-center -mx-3">
            <div class="md:flex-1 px-3 mb-5 md:mb-0">
                <p class="text-center md:text-left text-white text-xs leading-tight md:pr-12">Afin de permettre le bon fonctionnement de Toureasy, merci d'accepter les cookies.</p>
            </div>
            <div class="px-3 text-center">
                <button id="btn" class="py-2 px-8 bg-gray-800 hover:bg-gray-900 text-white rounded font-bold text-sm shadow-xl mr-3" @click.prevent="document.getElementById('cookiesModal').showModal()">Cookies ?</button>
                <button id="btn" class="py-2 px-8 bg-green-400 hover:bg-green-500 text-white rounded font-bold text-sm shadow-xl" @click.prevent="showCookieBanner=!showCookieBanner">Accepter les cookies</button>
            </div>
        </div>
    </section>
        <dialog id="cookiesModal" class="h-1/8 w-11/12 md:w-1/2 bg-white overflow-hidden rounded-md p-0">
        <div class="flex flex-col w-full h-auto">
            <div class="flex w-full h-auto items-center px-5 py-3">
                <div class="w-10/12 h-auto text-lg font-bold">
                    Informations sur les cookies
                </div>
                <div class="flex w-2/12 h-auto justify-end">
                    <button @click.prevent="document.getElementById('cookiesModal').close();" class="cursor-pointer focus:outline-none text-gray-400 hover:text-gray-800">
                        <i class="mdi mdi-close-circle-outline text-2xl"></i>
                    </button>
                </div>
            </div>
            <div class="flex w-full items-center bg-gray-100 border-b border-gray-200 px-5 py-3 text-sm">
                <div class="flex-1">
                    <p>Nous stockons uniquement votre code d'identification afin de faciliter votre prochaine connexion</p>
                </div>
                <div class="w-10 text-right">
                    <i class="mdi mdi-check-circle text-2xl text-green-400 leading-none"></i>
                </div>
            </div>
            <div class="flex w-full items-center bg-gray-100 border-b border-gray-200 px-5 py-3 text-sm">
                <div class="flex-1">
                    <p>Aucunes autres informations ne sont stockées</p>
                </div>
                <div class="w-10 text-right">
                    <i class="mdi mdi-check-circle text-2xl text-green-400 leading-none"></i>
                </div>
            </div>
        </div>
    </dialog>
</div>
    </div>
    <div class="absolute bottom-10 right-10">
    <!-- component -->
<a href='{$vars["about"]}' class="flex flex-col max-w-xs text-white bg-gray-800 p-6 h-32 rounded-lg relative">
  <div class="">
    <h3 class="text-xl font-bold pb-2">Nous contacter</h3>
    <p class="text-sm">Vous voulez en savoir plus sur Toureasy ? Vous pouvez nous contacter !</p>
  </div>
</a>
</div>
    </div>
    

END;
        return $html;
    }

    private function pageProfil(Membre $m, array $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
<div class="h-full">

  <div class="border-b-2 py-5 block md:flex">

    <div class="w-full md:w-2/5 p-4 sm:p-6 lg:p-8 bg-white shadow-md">
      <div class="flex justify-between">
        <span class="text-xl font-semibold block">Mon Profil</span>
        <button class="-mt-2 text-md font-bold text-white bg-gray-700 rounded-full px-5 py-2 hover:bg-gray-800" id="bt-modif">Modifier</button>
      </div>

      <span class="text-gray-600">Vos informations</span>
    </div>
    
    <div class="w-full md:w-3/5 p-8 bg-white lg:ml-4 shadow-md">
     <form method="post">
      <div class="rounded  shadow p-6">
        <div class="pb-6">
          <label for="name" class="font-semibold text-gray-700 block pb-1">Prénom</label>
          <div class="flex">
          <input type="text" name="prenom" class="border-1 rounded-r px-4 py-2 w-full input-profil longInputAvecMargin"  readonly="readonly" value="{$m->prenom}" placeholder="Prénom">
          </div>
        </div>
        <div class="pb-6">
          <label for="name" class="font-semibold text-gray-700 block pb-1">Nom</label>
          <div class="flex">
          <input type="text" name="nom" class="border-1 rounded-r px-4 py-2 w-full input-profil longInputAvecMargin" readonly="readonly" value="{$m->nom}" placeholder="Nom">
          </div>
        </div>
        <div class="pb-6">
          <label for="name" class="font-semibold text-gray-700 block pb-1">Sexe</label>
          <div class="flex">
          <select name="sexe" disabled="true" id="select" class="border-1  rounded-r px-4 py-2 w-full" class="input-profil longSelectAvecMargin">
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
          </div>
        </div>
        <div class="pb-6">
          <label for="name" class="font-semibold text-gray-700 block pb-1">Date de naissance</label>
          <div class="flex">
          <input type="date" name="naissance" class="input-profil longInputAvecMargin border-1  rounded-r px-4 py-2 w-full" readonly="readonly" value="{$m->dateNaissance}">
          </div>
        </div>
        <div class="pb-6">
          <label for="name" class="font-semibold text-gray-700 block pb-1">Token</label>
          <div class="flex">
          <input type="text" readonly="readonly" class="input-token longInputAvecMargin border-1  rounded-r px-4 py-2 w-full" value="{$m->token}" >
          </div>
        </div>
        <div class="pb-4">
          <label for="about" class="font-semibold text-gray-700 block pb-1">Email</label>
          <input type="text" name="mail" class="border-1 input-profil rounded-r px-4 py-2 w-full" readonly="readonly" value="{$m->email}" placeholder="email">
        </div>
        
        <div class="pb-4 mt-5 valider">
          
        </div>
      </div>
    </div>
 </form>
  </div>

</div>
END;


        $html .= <<<END
<script>
    function modifie() {
        let input = document.getElementsByClassName("input-profil")

        for (let i = 0; i < input.length; i++) {
            input[i].readOnly = false;
        }
        
        document.getElementById('select').disabled = false;

        let container = document.querySelector('.valider');
        container.innerHTML = ''

        let valider = document.createElement('input')
        valider.type = 'submit'
        valider.value = 'Valider'
        valider.className = 'box -mt-2 text-md font-bold text-white bg-gray-700 rounded-full px-5 py-2 hover:bg-gray-800'
        container.appendChild(valider)
    }

    let bt = document.getElementById("bt-modif")

    bt.addEventListener('click', modifie);

</script>

END;
        return $html;
    }

    public function monEspace($arrayListeUtilisateur, $arrayMonumentsPrives, $arrayMonumentsPublics, $vars)
    {
        $basepath = $vars['basepath'];
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END

<div class="lg:flex lg:flex-row">
<div>
<link
	href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
	rel="stylesheet">
	<div class="flex">
	<h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">Vos listes</h1>
	<button class="h-full bg-green-400 text-gray-50 rounded-md px-2 mx-5 my-7" onclick="location.href='{$vars['createListe']}'">Ajouter</button>
</div>
END;

        if (sizeOf($arrayListeUtilisateur) > 0) {
            $html .= <<<END
                
        <div class="flex items-center">
	<div class="col-span-12">
		<div class="overflow-auto lg:overflow-visible ">
			<table class="ml-10 table text-black-400 border-separate space-y-6 text-sm">
				<thead class="bg-white text-gray-500">
					<tr>
						<th class="p-3 text-left">Nom</th>
						<th class="p-3 text-left">Date</th>
						<th class="p-3  rounded text-left">Lien de partage</th>
					</tr>
				</thead>
				<tbody>
END;
            for ($i = 0; $i < sizeOf($arrayListeUtilisateur); $i++) {
                $html .= $this->uneLigneListeMonEspace($arrayListeUtilisateur[$i][0], $basepath, $arrayListeUtilisateur[$i][1], $arrayListeUtilisateur[$i][2]);
            }
            $html .= <<<END
                
              </tbody>
			</table>
		</div>
	</div>
</div>
</div>
<style>
	.table {
		border-spacing: 0 15px;
	}

	i {
		font-size: 1rem !important;
	}

	.table tr {
		border-radius: 20px;
	}

	tr td:nth-child(n+5),
	tr th:nth-child(n+5) {
		border-radius: 0 .625rem .625rem 0;
	}

	tr td:nth-child(1),
	tr th:nth-child(1) {
		border-radius: .625rem 0 0 .625rem;
	}
</style>
END;


        } else {
            $html .= <<<END
<p class='mt-5 ml-10' >Vous n'avez pas encore créé de liste </p></section>
END;

        }

        $html .= <<<END
<div>
<div class="flex">
	<h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">Vos monuments privé</h1>
	<button class="h-full bg-green-400 text-gray-50 rounded-md px-2 mx-5 my-7" onclick="location.href='{$vars['createMonument']}'">Ajouter</button>
</div>
END;


        if (sizeOf($arrayMonumentsPrives) > 0) {
            $html .= <<<END
                
        <div class="flex items-center">
	<div class="col-span-12">
		<div class="overflow-auto lg:overflow-visible ">
			<table class="ml-10 table text-black-400 border-separate space-y-6 text-sm">
				<thead class="bg-white text-gray-500">
					<tr>
						<th class="p-3 text-left">Nom</th>
						<th class="p-3  rounded text-left">Lien de partage</th>
					</tr>
				</thead>
				<tbody>
END;
            for ($i = 0; $i < sizeOf($arrayMonumentsPrives); $i++) {
                $html .= $this->uneLigneMonumentMonEspace($arrayMonumentsPrives[$i][0], $basepath, $arrayMonumentsPrives[$i][1], $arrayMonumentsPrives[$i][2]);
            }
            $html .= <<<END
              </tbody>
			</table>
		</div>
	</div>
</div>
</div>
END;


        } else {
            $html .= "<p class='ml-10 mt-5'>Vous n'avez pas encore créé de monument privé</p></div>";
        }

        $html .= <<<END
<div>
<div class="flex">
	<h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">Vos monuments publique</h1>
	<button class="h-full bg-green-400 text-gray-50 rounded-md px-2 mx-5 my-7" onclick="location.href='{$vars['createMonument']}'">Ajouter</button>
</div>

END;


        if (sizeOf($arrayMonumentsPublics) > 0) {
            $html .= <<<END
        <div class="flex items-center">
	<div class="col-span-12">
		<div class="overflow-auto lg:overflow-visible ">
			<table class="ml-10 table text-black-400 border-separate space-y-6 text-sm">
				<thead class="bg-white text-gray-500">
					<tr>
						<th class="p-3 text-left">Nom</th>
						<th class="p-3  rounded text-left">Lien de partage</th>
					</tr>
				</thead>
				<tbody>
END;
            for ($i = 0; $i < sizeOf($arrayMonumentsPublics); $i++) {
                $html .= $this->uneLigneMonumentMonEspace($arrayMonumentsPublics[$i][0], $basepath, $arrayMonumentsPublics[$i][1], $arrayMonumentsPublics[$i][2]);
            }
            $html .= <<<END
              </tbody>
			</table>
		</div>
	</div>
</div>
</div>
</div>
END;


        } else {
            $html .= "<p class='ml-10 mt-5'>Vous n'avez pas encore créé de monument publics</p></div></section>";
        }

        $html .= <<<END
<script src="{$vars['basepath']}/web/js/lib/clipboard.js-master/dist/clipboard.min.js"></script>
    <script> 
        new ClipboardJS('.lienAmis');
    </script>
END;

        return $html;

    }

    private function uneLigneListeMonEspace(ListeMonument $liste, $basepath, $url, $urlPartage): string
    {
        $html = <<<END
<tr class="bg-white">
						<td class="p-3 font-bold">
							<a href="$url">$liste->nom</a>
						</td>
						<td class="p-3 font-bold">
							<p class="nom">$liste->dateCreation</p>
						</td>
						<td class="p-8 rounded">
							<div class="lienAmis" data-clipboard-text="{$urlPartage}" data-tooltip="Cliquer pour copier dans le presse-papier">
                            <p id="url"><svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M17.391,2.406H7.266c-0.232,0-0.422,0.19-0.422,0.422v3.797H3.047c-0.232,0-0.422,0.19-0.422,0.422v10.125c0,0.232,0.19,0.422,0.422,0.422h10.125c0.231,0,0.422-0.189,0.422-0.422v-3.797h3.797c0.232,0,0.422-0.19,0.422-0.422V2.828C17.812,2.596,17.623,2.406,17.391,2.406 M12.749,16.75h-9.28V7.469h3.375v5.484c0,0.231,0.19,0.422,0.422,0.422h5.483V16.75zM16.969,12.531H7.688V3.25h9.281V12.531z"></path>
						</svg></p>
                        </div>
						</td>
					</tr>
END;
        return $html;
    }

    private function uneLigneMonumentMonEspace(Monument $monument, $basepath, $url, $urlPartage): string
    {
        $html = <<<END
<tr class="bg-white">
						<td class="p-3 font-bold">
							<a href="$url">$monument->nomMonum</a>
						</td>
						<td class="p-3 rounded">
							<div class="lienAmis" data-clipboard-text="{$urlPartage}" data-tooltip="Cliquer pour copier dans le presse-papier">
                            <p id="url">Lien de partage</p>
                        </div>
						</td>
					</tr>
END;
        return $html;
    }

    public function uneListeHtml(ListeMonument $liste, $monumentsDeCetteListe, $monumentsDeUtilisateur, $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
<div class="flex">
	 <h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">Liste : {$liste->nom}</h1>
	 <button id="modifier" class="h-full bg-green-400 text-gray-50 rounded-md px-2 mx-5 my-7" onclick="window.location.href='{$vars['modifierListe']}'">Modifier</button>
</div>
<p class='ml-10'>{$liste->description}</p>
END;
        if (sizeOf($monumentsDeCetteListe) > 0) {
            $html .= <<<END
        <div class="flex items-center">
	<div class="col-span-12">
		<div class="overflow-auto lg:overflow-visible ">
			<table class="ml-10 table text-black-400 border-separate space-y-6 text-sm">
				<thead class="bg-white text-gray-500">
					<tr>
						<th class="rounded p-3 text-left">Nom</th>
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
		</div>
	</div>
</div>
END;

        } else {
            $html .= "<p class='mt-5 ml-10'>Aucuns monuments dans cette liste</p></br>";
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
</div>
</div>
<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;
    }

    private function uneLigneMonumentListe(Monument $monument, $url): string
    {
        $html = <<<END
            <tr class="bg-white">
						<td class="p-3 font-bold rounded">
							<a href="$url">$monument->nomMonum</a>
						</td>
					</tr>
END;
        return $html;
    }

    private function modifierUneListe(ListeMonument $liste, $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
<div class="w-full absolute flex items-center justify-center bg-modal">
                <div class="min-h-full w-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-md w-full space-y-8">
                        <div>
                            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Modifier une liste</h2>
                        </div>
                        <form method="post" class="mt-8 space-y-6" enctype="multipart/form-data">
                            <input type="hidden" name="remember" value="true">
                            <div class="rounded-md shadow-sm -space-y-px">
                                <div>
                                    <label for="champ" class="sr-only">Nom de la liste</label>
                                    <input id="champ" name="nom" value="{$liste->nom}" required type="text" class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" placeholder="Nom de la liste">
                                </div>
                                <br></br>
                                <div>
                                    <label for="desc" class="sr-only">Description</label>
                                    <input id="desc" name="desc" value="{$liste->description}" required type="text" class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" placeholder="Description">
                                </div>
                            </div>

                            <div>
                                <button type="submit" name="action" value="Valider" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Valider
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute top-0">
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
<style>
@import url(https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css);
@-webkit-keyframes fadeIn {
  from { opacity:0 }
  to { opacity:1 }
}
@keyframes fadeIn {
  from { opacity:0 }
  to { opacity:1 }
}
@-webkit-keyframes fadeInUp {
  from { opacity:0; transform:translate3d(0,10%,0) }
  to { opacity:1; transform:translate3d(0,0,0) }
}
@keyframes fadeInUp {
  from { opacity:0; transform:translate3d(0,10%,0) }
  to { opacity:1; transform:translate3d(0,0,0) }
}

dialog[open] { -webkit-animation-duration:0.3s; animation-duration:0.3s; -webkit-animation-fill-mode:both; animation-fill-mode:both; -webkit-animation-name:fadeInUp; animation-name:fadeInUp }
dialog::backdrop { background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(3px); -webkit-animation-duration:0.3s; animation-duration:0.3s; -webkit-animation-fill-mode:both; animation-fill-mode:both; -webkit-animation-name:fadeIn; animation-name:fadeIn  }
</style>
END;
        return $html;
    }

    public function unMonumentHtml(Monument $monument, array $images, $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
        <section class="infos">
        <h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">{$monument->nomMonum}</h1>
            <p class="desc ml-10 mt-5" id="descMonu">{$monument->descLongue}</p>
END;
        if (sizeof($images) > 0) {
            $html .= "<div class='wrapper ml-10 mr-10' id='galerie'>";
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
        <div class="box pb-10">
        <button onclick="window.location.href='{$vars['modifierMonument']}'" class=" ml-10 mt-5 group relative  flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
Modifier
</button>
        </div></div>
<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;

        return $html;
    }

    public function detailMonumentMap(Monument $monument, array $images, $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
<p id="msg"></p>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{$vars['basepath']}/web/js/favoris.js"></script>
        <section class="infos mr-10">
        <div id="test">
<div id="main-content">
  <div>
  <form method="post" id="Form">
  <div class="ml-10 mt-5 my-auto flex">
<h3 class="text-3xl font-extrabold text-gray-900 my-auto" id="nom">{$monument->nomMonum}</h3>
END;

        if ($vars['estFavori']) {
            $html .= <<<END
<input type="checkbox" name="fav" onChange="chk('{$vars['basepath']}','{$monument->token}')" id="checkbox" checked/>
END;
        } else {
            $html .= <<<END
<input type="checkbox" name="fav" onChange="chk('{$vars['basepath']}','{$monument->token}')" id="checkbox"/>
END;
        }

        $html .= <<<END
    <label for="checkbox">
      <svg id="heart-svg" viewBox="467 392 58 57" xmlns="http://www.w3.org/2000/svg">
        <g id="Group" fill="none" fill-rule="evenodd" transform="translate(467 392)">
          <path d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z" id="heart" fill="#AAB8C2"/>
          <circle id="main-circ" fill="#E2264D" opacity="0" cx="29.5" cy="29.5" r="1.5"/>

          <g id="grp7" opacity="0" transform="translate(7 6)">
            <circle id="oval1" fill="#9CD8C3" cx="2" cy="6" r="2"/>
            <circle id="oval2" fill="#8CE8C3" cx="5" cy="2" r="2"/>
          </g>

          <g id="grp6" opacity="0" transform="translate(0 28)">
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="7" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="3" cy="2" r="2"/>
          </g>

          <g id="grp3" opacity="0" transform="translate(52 28)">
            <circle id="oval2" fill="#9CD8C3" cx="2" cy="7" r="2"/>
            <circle id="oval1" fill="#8CE8C3" cx="4" cy="2" r="2"/>
          </g>

          <g id="grp2" opacity="0" transform="translate(44 6)">
            <circle id="oval2" fill="#CC8EF5" cx="5" cy="6" r="2"/>
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp5" opacity="0" transform="translate(14 50)">
            <circle id="oval1" fill="#91D2FA" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp4" opacity="0" transform="translate(35 50)">
            <circle id="oval1" fill="#F48EA7" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#F48EA7" cx="2" cy="2" r="2"/>
          </g>

          <g id="grp1" opacity="0" transform="translate(24)">
            <circle id="oval1" fill="#9FC7FA" cx="2.5" cy="3" r="2"/>
            <circle id="oval2" fill="#9FC7FA" cx="7.5" cy="2" r="2"/>
          </g>
        </g>
      </svg>
    </label>
    </form>
  </div>
</div>
</div>
</div>            
            <p class="desc ml-10" id="descMonu">{$monument->descLongue}</p>
END;
        if (sizeof($images) > 0) {
            $html .= "<div class='wrapper ml-10' id='galerie'>";
            foreach ($images as $img) {
                $html .= <<<END
<div class='cell'>
    <img class="mr-10" src='{$vars['basepath']}/{$img['urlImage']}'>
</div>
END;
            }
            $html .= "</div>";
        }

        if ($monument->estPrive == 0) {
            $html .= <<<END
</section>
<div class="flex pb-10 mr-10">
<button onclick="window.location.href='{$vars['modifierMonument']}'" name="action" value="OK" class="ml-10 mt-5 group relative  flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
Proposer une modification
</button>

<button onclick="window.location.href='{$vars['seeOnMap']}'" name="action" value="OK" class="ml-5 mt-5 group relative  flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
Voir le monument sur la carte
</button>

</div>

<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;
        } else {
            $html .= <<<END
</section>
<div class="box"><button onclick="window.location.href='{$vars['seeOnMap']}'">Voir le monument sur la carte</button></div>
<div id="back"><img onclick="back('{$vars['back']}')" src="{$vars['basepath']}/web/img/back.png"/></div></div>
<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;
        }
        return $html;
    }

    public function detailListeMap(ListeMonument $liste, $monumentsDeCetteListe, $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
        <section class="titreListe">
            <h3 class="nomSection">{$liste->nom}</h3>
            <i class="desc">{$liste->description}</i>
        </section></br>
END;
        if (sizeOf($monumentsDeCetteListe) > 0) {
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

        return $html .= <<<END
<div class="box"><button onclick="window.location.href='{$vars['seeOnMap']}'">Voir la liste sur la carte</button></div>
<div id="back"><img onclick="back('{$vars['back']}')" src="{$vars['basepath']}/web/img/back.png"/></div></div>
</div>
<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;
    }

    public function modifierUnMonument(Monument $monument, array $arrayImg, $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
        <form method="post" enctype="multipart/form-data" id="add">
        
        <div class="  py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
          <div class=" mx-auto">
            <div class="flex my-5 items-center space-x-5">
              <div class="block  font-semibold text-xl self-start text-gray-700">
                <h1 class="leading-relaxed">Modifier un monument</h1>
              </div>
            </div>
            <div class="divide-y divide-gray-200">
              <div class="my-5 py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                <div class="flex flex-col">
                  <label class="leading-loose">Nom du monument</label>
                  <input type="text" name="nom" value="{$monument->nomMonum}" required class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600" placeholder="Nom du monument">
                </div>
                <div class="my-5 flex flex-col">
                  <label class="leading-loose">Description</label>
                  
                  <div>
                        <div id="menuEditor">
                            <textarea name="desc" id="area" cols="60" rows="10" style="display:none"></textarea>
                            <iframe class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600" style="background: #FFFFFF" name="frm" id="frm"></iframe>
                            <input type="hidden" name="descr" value="{$monument->descLongue}"/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="lat"/><input type="hidden" name="long"/>
                
              

        <input type="hidden" id="delete" name="delete"/>
        END;
        $html .= "<div class='wrapper' id='galerie'>";
        foreach ($arrayImg as $img) {
            $html .= <<<END
        <div class='cell'>
            <div id="content">
                <img src="{$vars['basepath']}/web/img/cross.png" class="cross absolute"/>
            </div>
            <img id="{$img['numeroImage']}" src='{$vars['basepath']}/{$img['urlImage']}'>
        </div>
        END;
        }
        $html .= <<<END

                </div>
                <div class="flex items-center space-x-4">
                  <div class="flex flex-col">
                    <div id='galerie'>
                    <div class="image-upload cell" id="addImages">
                        <input id="file-input" type="file" multiple="multiple" name="fichier[]"/>
                    </div>
                  </div>
                </div>
                
              </div>
                <div class="pt-4 flex items-center space-x-4">
                  <input type="button" name="action" onclick="submitForm()" value="Valider" class="mt-3 group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    </input>
              </div>
          </div>
        </div>
        </div>
        </div>
</form>
<script src="{$vars['basepath']}/web/js/textEditor.js"></script>
<script src="{$vars['basepath']}/web/js/modifierMonument.js"></script>
END;

        return $html;

    }

    private function ajoutMonumentHtml($vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
<form method="post" enctype="multipart/form-data" id="add">
<div class="  py-6 flex flex-col justify-center sm:py-12">
  <div class="relative py-3 sm:max-w-xl sm:mx-auto">
    <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
      <div class=" mx-auto">
        <div class="flex my-5 items-center space-x-5">
          <div class="block  font-semibold text-xl self-start text-gray-700">
            <h1 class="leading-relaxed">Créer un monument</h1>
          </div>
        </div>
        <div class="divide-y divide-gray-200">
          <div class="my-5 py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
            <div class="flex flex-col">
              <label class="leading-loose">Nom du monument</label>
              <input type="text" name="nom" required class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600" placeholder="Nom du monument">
            </div>
            <div class="my-5 flex flex-col">
              <label class="leading-loose">Description</label>
              
              <div>
                    <div id="menuEditor">
                        <textarea name="desc" id="area" cols="60" rows="10" style="display:none"></textarea>
                        <iframe class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600" style="background: #FFFFFF" name="frm" id="frm"></iframe>
                    </div>
                </div>
            </div>
            <div class="flex justify-center space-x-4">
              <div class="">
                <input type="radio" id="private" name="visibilite" value="private">
<label for="huey">Privé</label>

              </div>
              <div class="">
                <input type="radio" id="public" name="visibilite" value="public" checked>
<label for="dewey">Public</label>
              </div>
            </div>
            <input type="hidden" name="lat"/><input type="hidden" name="long"/>
            <div class="flex items-center space-x-4">
              <div class="flex flex-col">
                <div id='galerie'>
                <div class="image-upload cell" id="addImages">
  <label for="file-input">
    
  </label>
  <input id="file-input" type="file" multiple="multiple" name="fichier[]"/>
</div>
  
              </div>
            </div>
            
          </div>
          <div class="pt-4 flex items-center space-x-4">
              <input type="button" name="action" onclick="submitForm()" value="Valider" class="mt-3 group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                </input>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>


END;

        $html .= <<<END
<script src="{$vars['basepath']}/web/js/textEditor.js"></script>
<script src="{$vars['basepath']}/web/js/ajouterMonument.js"></script>
END;
        return $html;
    }

    public function ajoutListeHtml($vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
<div class="w-full absolute flex items-center justify-center bg-modal">
                <div class="min-h-full w-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-md w-full space-y-8">
                        <div>
                            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Créer une liste</h2>
                        </div>
                        <form method="post" class="mt-8 space-y-6" enctype="multipart/form-data">
                            <input type="hidden" name="remember" value="true">
                            <div class="rounded-md shadow-sm -space-y-px">
                                <div>
                                    <label for="champ" class="sr-only">Nom de la liste</label>
                                    <input id="champ" name="nom" required type="text" class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" placeholder="Nom de la liste">
                                </div>
                                <br></br>
                                <div>
                                    <label for="desc" class="sr-only">Description</label>
                                    <input id="desc" name="nom" required type="text" class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" placeholder="Description">
                                </div>
                            </div>

                            <div>
                                <button type="submit" name="action" value="OK" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Valider
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute top-0">
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
<style>
@import url(https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css);
@-webkit-keyframes fadeIn {
  from { opacity:0 }
  to { opacity:1 }
}
@keyframes fadeIn {
  from { opacity:0 }
  to { opacity:1 }
}
@-webkit-keyframes fadeInUp {
  from { opacity:0; transform:translate3d(0,10%,0) }
  to { opacity:1; transform:translate3d(0,0,0) }
}
@keyframes fadeInUp {
  from { opacity:0; transform:translate3d(0,10%,0) }
  to { opacity:1; transform:translate3d(0,0,0) }
}

dialog[open] { -webkit-animation-duration:0.3s; animation-duration:0.3s; -webkit-animation-fill-mode:both; animation-fill-mode:both; -webkit-animation-name:fadeInUp; animation-name:fadeInUp }
dialog::backdrop { background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(3px); -webkit-animation-duration:0.3s; animation-duration:0.3s; -webkit-animation-fill-mode:both; animation-fill-mode:both; -webkit-animation-name:fadeIn; animation-name:fadeIn  }
</style>
END;
        return $html;
    }

    public function pageDemandeAmi($vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END

<div>
            <div class="w-full absolute flex items-center justify-center bg-modal">
                <div class="bg-white rounded shadow p-8 m-4 max-w-xs  text-center">
                    <div class="mb-8">
                        <h1 id="center">{$vars['prenom']} vous a envoyé une demande d'ami </h1>
                    </div>
                    <div class="flex justify-center">
                    <form method="post" enctype="multipart/form-data">
                        <input name="rep" type="submit" value="Accepter" class="flex-no-shrink text-white py-2 px-4 bg-green-700 rounded bg-teal hover:bg-green-500"></input>
                         <input name="rep" type="submit" value="Refuser" class="flex-no-shrink text-white py-2 px-4 bg-green-700 rounded bg-teal hover:bg-green-500"></input>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
END;

        return $html;
    }

    public function pageAmis($vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
       <h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">Amis</h1>
       <div class="box">
END;
        if (!isset($vars["lienAmis"])) {
            $html .= <<<END
            <form method="post" enctype="multipart/form-data">
                <input type="submit" value="Générer un lien pour inviter un amis" class="ml-10 mt-5 shadow bg-green-700 hover:bg-green-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">            </form>
END;
        } else {
            $html .= '<p  class="asinput"> ' . $vars["lienAmis"] . '</p>';
        }

        $html .= <<<END
       </div>
       

       <div>
            <h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">Vos amis</h1>
       </div>
END;
        $liste = $vars["listeAmis"];
        if (sizeOf($liste) > 0) {
            $html .= <<<END
        <div class="flex mt-5 items-center">
	<div class="col-span-12">
		<div class="overflow-auto lg:overflow-visible ">
			<table class="ml-10 table text-black-400 border-separate space-y-6 text-sm">
				<thead class="bg-white text-gray-500">
					<tr>
						<th class="p-3 text-left">Prénom</th>
						<th class="p-3  rounded text-left">Date d'inscription</th>
					</tr>
				</thead>
				<tbody>                                
END;
            foreach ($liste as $item) {
                $html .= $this->uneLigneListeAmis($item);
            }
            $html .= <<<END
              </tbody>
			</table>
		</div>
	</div>
</div>
</div>
END;

        } else {
            $html .= "<p class='ml-10 mt-5 message'>Vous n'avez pour l'instant aucun amis</p></br>";
            $html .= "</div>";
        }
        return $html;
    }

    private function uneLigneListeAmis($item): string
    {
        $html = <<<END
<tr class="bg-white">
						<td class="p-3 font-bold">
							<p class="username">{$item["prenom"]}</p>
						</td>
						<td class="p-3 rounded">
							<p class="inscription">{$item["dateInscription"]}</p>
						</td>
					</tr>
END;
        return $html;
    }

    private function pageAmisNouveauLien($vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
        <div>
            <h1 class="mt-6 ml-10  text-3xl font-extrabold text-gray-900 ">Lien d'invitation</h1>
    
            <div class="lienAmis flex" data-clipboard-text="{$vars["urlDemande"]}" data-tooltip="Cliquer pour copier dans le presse-papier">
                <p class="ml-10 mt-5">Envoyez le lien ci-dessous à la personne que vous souhaitez ajouter à vos amis : </p>
                <button class="ml-2 shadow bg-green-700 hover:bg-green-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
            type="submit">
            Copier le lien
            </button>
            </div>
            <div class="boxsmall">
            <button onclick="window.location.href='{$vars['url']}'" class="ml-10 mt-10 shadow bg-green-700 hover:bg-green-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
            Retour à la page précédente
</button></button>
            </div>          
        </div>      
    </div>
    <script src="{$vars['basepath']}/web/js/lib/clipboard.js-master/dist/clipboard.min.js"></script>
    <script> 
        new ClipboardJS('.lienAmis');
    </script>
END;
        return $html;
    }

    public function pageTest($vars): string
    {
        return <<<END
        <h1>TEST</h1>
END;
    }

    /**
     * Retourne un string avec l'entête, ne pas oublier de fermer la div container à la fin du contenu
     * @param $vars
     * @return string
     */
    private function insererEnteteSite($vars): string
    {
        $html = <<<END
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
<style>
@import url(https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css);
/*
module.exports = {
    plugins: [
        require('tailwindcss-inset')({
            insets: {
                full: '100%'
            }
        })
    ]
};
*/
.inset-l-full {
    left: 100%;
}
</style>
<div class="min-w-screen min-h-screen bg-gray-200">
    <div class="py-3 px-5 bg-white rounded shadow-xl">
        <div class="-mx-1">
            <ul class="flex w-full flex-wrap items-center h-10">
            <li class="block relative">
                    <p class="flex items-center h-10 leading-10 px-2 lg:px-4 rounded no-underline hover:no-underline transition-colors duration-100 mx-1">
                        <span class="text-2xl text-green-500 font-dark font-extrabold ">Toureasy</span>
                    </p>
                </li>
                <li class="block relative">
                    <a href='{$vars['menu']['map']}' class="flex items-center h-10 leading-10 px-2 lg:px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 bg-green-500 text-white" @click.prevent="showChildren=!showChildren">
                        <span class="lg:mr-3 text-xl"> <i class="mdi mdi-map"></i> </span>
                        <span class="hidden lg:flex">Map</span>
                    </a>
                </li>
                <li class="block relative">
                    <a href='{$vars['menu']['espace']}' class="flex items-center h-10 leading-10 px-2 lg:px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100">
                        <span class="lg:mr-3 text-xl"> <i class="mdi mdi-widgets-outline"></i> </span>
                        <span class="hidden lg:flex">Mon espace</span>
                    </a>
                </li>
                <li class="block relative">
                    <a href='{$vars['menu']['ajout']}' class="flex items-center h-10 leading-10 px-2 lg:px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100" @click.prevent="showChildren=!showChildren">
                        <span class="lg:mr-3 text-xl"> <i class="mdi mdi-view-grid-plus"></i> </span>
                        <span class="hidden lg:flex">Ajouter un monument</span>
                    </a>
                </li>
                <li class="block relative">
                    <a href='{$vars['menu']['about-us']}' class="flex items-center h-10 leading-10 px-2 lg:px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100" @click.prevent="showChildren=!showChildren">
                        <span class="lg:mr-3 text-xl"> <i class="mdi mdi-information"></i> </span>
                        <span class="hidden lg:flex">À propos</span>
                    </a>
                </li>
                <li class="block absolute right-0" x-data="{showChildren:false}" @click.away="showChildren=false">
                    <a href="#" class="flex items-center h-10 leading-10 px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100" @click.prevent="showChildren=!showChildren">
                        <span class="mr-3 text-xl"> <i class="mdi mdi-account-cog"></i> </span>
                        <span class="hidden lg:flex">Mon profil</span>
                        <span class="ml-2"> <i class="mdi mdi-chevron-down"></i> </span>
                    </a>
                    <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute top-auto left-0 min-w-full w-56 z-30 mt-1" x-show="showChildren" x-transition:enter="transition ease duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease duration-300 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                        <span class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -mt-1 ml-6"></span>
                        <div class="bg-white rounded w-full relative z-10 py-1">
                            <ul class="list-reset">
                                <li class="relative" x-data="{showChildren:false}" @mouseleave="showChildren=false" @mouseenter="showChildren=true">
                                    <a href='{$vars['menu']['amis']}' class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer"> <span class="flex-1">Amis</span> </a>
                                </li>
                                <li class="relative" x-data="{showChildren:false}" @mouseleave="showChildren=false" @mouseenter="showChildren=true">
                                    <a href='{$vars['menu']['profil']}' class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer"> <span class="flex-1">Réglages</span> </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
END;
        return $html;
    }

    private function pageAbout($vars): string
    {
        $html = <<<END
<div>
<div class="mb-16"> 

    <dh-component>
        <div class="container flex justify-center mx-auto pt-16">
            <div>
                <p class="text-gray-500 text-lg text-center font-normal pb-3">CRÉATEURS</p>
                <h1 class="xl:text-4xl text-3xl text-center text-gray-800 font-extrabold pb-6 sm:w-4/6 w-5/6 mx-auto">Projet Universitaire</h1>
            </div>
        </div>
        <div class="w-full bg-gray-100 px-10 pt-10">
            <div class="container mx-auto">
                <div role="list" aria-label="Behind the scenes People " class="lg:flex md:flex sm:flex items-center xl:justify-between flex-wrap md:justify-around sm:justify-around lg:justify-around">
                    <div role="listitem" class="xl:w-1/3 sm:w-3/4 md:w-2/5 relative mt-16 mb-32 sm:mb-24 xl:max-w-sm lg:w-2/5">
                        <div class="rounded overflow-hidden shadow-md bg-white">
                            <div class="absolute -mt-20 w-full flex justify-center">
                                <div class="h-32 w-32">
                                    <img src="{$vars['basepath']}/web/img/silvio.png" alt="Photo de Silvio Brancati" role="img" class="rounded-full object-cover h-full w-full shadow-md" />
                                </div>
                            </div>
                            <div class="px-6 mt-16">
                                <h1 class="font-bold text-3xl text-center mb-1">Silvio Brancati</h1>
                                <p class="text-gray-800 text-sm text-center">Étudiant en L3 Informatique</p>
                                <div class="w-full flex justify-center pt-5 pb-5">
                                    <a href="https://github.com/Silvio-Br" class="mx-5">
                                        <div aria-label="Github" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github">
                                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                            </svg>
                                        </div>
                                    </a>
                                    <a href="https://www.linkedin.com/in/silvio-brancati-82970219b" class="mx-5">
                                        <div aria-label="Linkedin" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 64 64" aria-labelledby="title"
                                                 aria-describedby="desc" role="img" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>Linkedin</title>
                                                <desc>A color styled icon from Orion Icon Library.</desc>
                                                <path data-name="layer1"
                                                      fill="#0077b7" d="M1.15 21.7h13V61h-13zm46.55-1.3c-5.7 0-9.1 2.1-12.7 6.7v-5.4H22V61h13.1V39.7c0-4.5 2.3-8.9 7.5-8.9s8.3 4.4 8.3 8.8V61H64V38.7c0-15.5-10.5-18.3-16.3-18.3zM7.7 2.6C3.4 2.6 0 5.7 0 9.5s3.4 6.9 7.7 6.9 7.7-3.1 7.7-6.9S12 2.6 7.7 2.6z"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="listitem" class="xl:w-1/3 sm:w-3/4 md:w-2/5 relative mt-16 mb-32 sm:mb-24 xl:max-w-sm lg:w-2/5">
                        <div class="rounded overflow-hidden shadow-md bg-white">
                            <div class="absolute -mt-20 w-full flex justify-center">
                                <div class="h-32 w-32">
                                    <img src="{$vars['basepath']}/web/img/nicolas.png" alt="Photo de Nicolas Frache" role="img" class="rounded-full object-cover h-full w-full shadow-md" />
                                </div>
                            </div>
                            <div class="px-6 mt-16">
                                <h1 class="font-bold text-3xl text-center mb-1">Nicolas Frache</h1>
                                <p class="text-gray-800 text-sm text-center">Étudiant à Telecom Nancy</p>
                                <div class="w-full flex justify-center pt-5 pb-5">
                                    <a href="https://github.com/Nicolas-Frache" class="mx-5">
                                        <div aria-label="Github" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github">
                                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                            </svg>
                                        </div>
                                    </a>
                                    <a href="https://www.linkedin.com/in/nicolas-frache-8bb8531b4/" class="mx-5">
                                        <div aria-label="Linkedin" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 64 64" aria-labelledby="title"
                                                 aria-describedby="desc" role="img" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>Linkedin</title>
                                                <desc>A color styled icon from Orion Icon Library.</desc>
                                                <path data-name="layer1"
                                                      fill="#0077b7" d="M1.15 21.7h13V61h-13zm46.55-1.3c-5.7 0-9.1 2.1-12.7 6.7v-5.4H22V61h13.1V39.7c0-4.5 2.3-8.9 7.5-8.9s8.3 4.4 8.3 8.8V61H64V38.7c0-15.5-10.5-18.3-16.3-18.3zM7.7 2.6C3.4 2.6 0 5.7 0 9.5s3.4 6.9 7.7 6.9 7.7-3.1 7.7-6.9S12 2.6 7.7 2.6z"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="listitem" class="xl:w-1/3 sm:w-3/4 md:w-2/5 relative mt-16 mb-32 sm:mb-24 xl:max-w-sm lg:w-2/5">
                        <div class="rounded overflow-hidden shadow-md bg-white">
                            <div class="absolute -mt-20 w-full flex justify-center">
                                <div class="h-32 w-32">
                                    <img src="{$vars['basepath']}/web/img/arthur.png" alt="Photo d'Arthur Moitrier" role="img" class="rounded-full object-cover h-full w-full shadow-md" />
                                </div>
                            </div>
                            <div class="px-6 mt-16">
                                <h1 class="font-bold text-3xl text-center mb-1">Arthur Moitrier</h1>
                                <p class="text-gray-800 text-sm text-center">Étudiant en L3 Informatique</p>
                                <div class="w-full flex justify-center pt-5 pb-5">
                                    <a href="https://github.com/JoCk3rZ" class="mx-5">
                                        <div aria-label="Github" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github">
                                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                            </svg>
                                        </div>
                                    </a>
                                    <a href="https://www.linkedin.com/in/arthur-moitrier/" class="mx-5">
                                        <div aria-label="Linkedin" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 64 64" aria-labelledby="title"
                                                 aria-describedby="desc" role="img" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>Linkedin</title>
                                                <desc>A color styled icon from Orion Icon Library.</desc>
                                                <path data-name="layer1"
                                                      fill="#0077b7" d="M1.15 21.7h13V61h-13zm46.55-1.3c-5.7 0-9.1 2.1-12.7 6.7v-5.4H22V61h13.1V39.7c0-4.5 2.3-8.9 7.5-8.9s8.3 4.4 8.3 8.8V61H64V38.7c0-15.5-10.5-18.3-16.3-18.3zM7.7 2.6C3.4 2.6 0 5.7 0 9.5s3.4 6.9 7.7 6.9 7.7-3.1 7.7-6.9S12 2.6 7.7 2.6z"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="listitem" class="xl:w-1/3 sm:w-3/4 md:w-2/5 relative mt-16 mb-32 sm:mb-24 xl:max-w-sm lg:w-2/5">
                        <div class="rounded overflow-hidden shadow-md bg-white">
                            <div class="absolute -mt-20 w-full flex justify-center">
                                <div class="h-32 w-32">
                                    <img src="{$vars['basepath']}/web/img/remi.png" alt="Photo de Rémi Zapp" role="img" class="rounded-full object-cover h-full w-full shadow-md" />
                                </div>
                            </div>
                            <div class="px-6 mt-16">
                                <h1 class="font-bold text-3xl text-center mb-1">Rémi Zapp</h1>
                                <p class="text-gray-800 text-sm text-center">Étudiant en L3 Informatique</p>
                                <div class="w-full flex justify-center pt-5 pb-5">
                                    <a href="https://github.com/RemRem57" class="mx-5">
                                        <div aria-label="Github" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github">
                                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                            </svg>
                                        </div>
                                    </a>
                                    <a href="https://www.linkedin.com/in/rémi-zapp-502a93225/" class="mx-5">
                                        <div aria-label="Linkedin" role="img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 64 64" aria-labelledby="title"
                                                 aria-describedby="desc" role="img" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>Linkedin</title>
                                                <desc>A color styled icon from Orion Icon Library.</desc>
                                                <path data-name="layer1"
                                                      fill="#0077b7" d="M1.15 21.7h13V61h-13zm46.55-1.3c-5.7 0-9.1 2.1-12.7 6.7v-5.4H22V61h13.1V39.7c0-4.5 2.3-8.9 7.5-8.9s8.3 4.4 8.3 8.8V61H64V38.7c0-15.5-10.5-18.3-16.3-18.3zM7.7 2.6C3.4 2.6 0 5.7 0 9.5s3.4 6.9 7.7 6.9 7.7-3.1 7.7-6.9S12 2.6 7.7 2.6z"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </dh-component> 
    
    <div class="mt-10">
        <style>
  /* RED BORDER ON INVALID INPUT */
  .check input:invalid {
      border-color: red;
  }

  /* FLOATING LABEL */
  .label-floating input:not(:placeholder-shown),
  .label-floating textarea:not(:placeholder-shown) {
      padding-top: 1.4rem;
  }
  .label-floating input:not(:placeholder-shown) ~ label,
  .label-floating textarea:not(:placeholder-shown) ~ label {
      font-size: 0.8rem;
      transition: all 0.2s ease-in-out;
      color: #1f9d55;
      opacity: 1;
  }

</style>
<form id="contact-me" action="mailto:Toureasy.contact@gmail.com" class="w-full mx-auto max-w-3xl bg-white shadow p-8 text-gray-700 ">


    <h2 class="w-full hidden flex my-2 text-3xl font-bold leading-tight">Nous contacter</h2>
    <div class="">
        <button class="w-full shadow bg-green-700 hover:bg-green-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
            type="submit">
            Envoyer un mail
        </button>

    </div>
</form>


<script>


//RED BORDER ON INVALID INPUT
document.getElementById('contact-me').addEventListener("invalid", function (event) {
	this.classList.add('check');
}, true);




	// TEXT AREA AUTO EXPAND
var textarea = document.querySelector('textarea.autoexpand');

textarea.addEventListener('keydown', autosize);

function autosize(){
  var el = this;
  setTimeout(function(){
    el.style.cssText = 'height:auto; padding: 1.4rem .2rem .5rem';
    
    el.style.cssText = 'height:' + el.scrollHeight + 'px';
  },0);
}



</script>
    </div>
    
</div> 
</div>
END;
        return $html;
    }

    private function displayErreurServeur($vars): string {
        return <<<END

  <div class="message">
    <h2 id="center">Le serveur est actuellement indisponible</h2>
    <p id="center">Merci de vous reconnecter plus tard</p>        
  </div>      
</div>
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
            case Vue::ABOUT:
                $content = $this->pageAbout($vars);
                break;
            case Vue::AMIS:
                $content = $this->pageAmis($vars);
                break;
            case Vue::AMISNOUVEAULIEN:
                $content = $this->pageAmisNouveauLien($vars);
                break;
            case Vue::MAP_MONUMENT:
                $content = $this->detailMonumentMap($this->data[0], $this->data[1], $vars);
                break;
            case Vue::MAP_LISTE:
                $content = $this->detailListeMap($this->data[0], $this->data[1], $vars);
                break;
            case Vue::ERREUR_SERVEUR:
                $content = $this->displayErreurServeur($vars);
                break;
        }


        $html = <<<END
<!DOCTYPE html>
<html>
    <head>
        <title>Toureasy</title>
        <link rel="stylesheet" href="{$vars['basepath']}/web/css/tailwind.css">
        <link rel="icon" type="image/png" href="{$vars['basepath']}/web/img/ico.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    </head>
    <body class="overflow-x-hidden">
        $content
    </body>
    <script src="{$vars['basepath']}/web/js/menu.js"></script>
</html>
END;

        return $html;
    }

}