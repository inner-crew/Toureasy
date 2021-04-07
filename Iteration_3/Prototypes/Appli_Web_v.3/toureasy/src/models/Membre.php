<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Contribution associée à la table 'contribution' de la base de donneés
 **/
class Membre extends Model
{
    protected $table = 'membre';
    protected $primaryKey = 'idMembre';

    public static function getMembreByToken($token)
    {
        return Membre::query()->where('token', '=', $token)->firstOrFail();
    }

    public static function getMembreById($id) : Membre
    {
        return Membre::query()->where('idMembre', '=', $id)->firstOrFail();
    }

    public static function getTousLesMonumentsUtilisateurByToken($token)
    {
        $res = array();

        $membre = self::getMembreByToken($token);

        $listeMonumentsPrives = AuteurMonumentPrive::getMonumentByCreator($membre->idMembre);
        foreach ($listeMonumentsPrives as $monument) {
            $monument = Monument::getMonumentById($monument->idMonument);
            array_push($res, $monument);
        }

        $listeMonumentsPublics = Contribution::getMonumentByIdCreator($membre->idMembre);
        foreach ($listeMonumentsPublics as $monument) {
            $monument = Monument::getMonumentById($monument->monumentTemporaire);
            if ($monument->estPrive == '0') {
                array_push($res, $monument);
            }
        }

        return $res;
    }

    public function usesTimestamps() : bool
    {
        return false;
    }

    public function monumentsFavoris() {
        return $this->belongsToMany(Monument::class,
            'favoris',
            'idMembre',
            'idMonument'
        );
    }
}