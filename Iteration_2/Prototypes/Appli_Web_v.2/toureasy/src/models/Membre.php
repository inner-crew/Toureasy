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

    public static function getIdBytoken($token)
    {
        return Membre::query()->where('token', '=', $token)->firstOrFail()->idMembre;
    }

    public function usesTimestamps() : bool
    {
        return false;
    }
}