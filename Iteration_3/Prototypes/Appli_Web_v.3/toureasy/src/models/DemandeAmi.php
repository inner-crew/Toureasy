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

    public static function getDemandeurByIdDemande($id) : Membre
    {
        $idDem = DemandeAmi::select('idDemandeur')->find($id);
        return Membre::getMembreById($idDem['idDemandeur']);
    }


}