<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Monument associée à la table 'monument' de la base de donneés
 **/
class Monument extends Model
{
    protected $table = 'monument';
    protected $primaryKey = 'idMonument';

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getMonumentById($id)
    {
        return Monument::query()->where('idMonument', '=', $id)->firstOrFail();
    }

    public static function getMonumentByToken($id)
    {
        return Monument::query()->where('token', '=', $id)->firstOrFail();
    }

    public static function getMonumentPublic()
    {
        return Monument::query()->where('estPrive', '=', 0)->get();
    }

}