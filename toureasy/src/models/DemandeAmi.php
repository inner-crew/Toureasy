<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe DemandeAmi associée à la table 'DemandeAami de la base de donneés
 **/
class DemandeAmi extends Model
{
    protected $table = 'demandeAmi';
    protected $primaryKey = 'idDemandeAmi';

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getDemandeByToken($token) : DemandeAmi
    {
        return DemandeAmi::query()->where('token','=',$token)->firstOrFail();
    }

    public static function getDemandeurByTokenDemande($token) : Membre
    {
        $idDem = DemandeAmi::select('idDemandeur')->where('token',"=",$token)->firstOrFail();
        return Membre::getMembreById($idDem['idDemandeur']);
    }


}