<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Image associée à la table 'image' de la base de donneés
 **/
class ListeMonument extends Model
{
    protected $table = 'listemonument';
    protected $primaryKey = 'idListe';
    public $incrementing = false;

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getListesByIdCreator($id)
    {
        return ListeMonument::query()->where('idCreateur', '=', $id)->get();
    }

    public static function getListeByToken($token)
    {
        return ListeMonument::query()->where('token', '=', $token)->firstOrFail();
    }
}