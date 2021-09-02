<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Contribution associée à la table 'contribution' de la base de donneés
 **/
class AuteurMonumentPrive extends Model
{
    protected $table = 'auteurmonumentprive';
    protected $primaryKey = 'idMonument';

    public $incrementing = false;

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getMonumentByCreator($id)
    {
        return AuteurMonumentPrive::query()->where('idMembre', '=', $id)->get();
    }
}