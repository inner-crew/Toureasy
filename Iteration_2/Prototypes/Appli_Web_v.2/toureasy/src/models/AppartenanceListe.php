<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Contribution associée à la table 'contribution' de la base de donneés
 **/
class AppartenanceListe extends Model
{
    protected $table = 'appartenanceliste';
    protected $primaryKey = ['idListe', 'idMonument'];

    public $incrementing = false;

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getMonumentByIdListe($id)
    {
        return AppartenanceListe::query()->where('idListe', '=', $id)->get();
    }
}