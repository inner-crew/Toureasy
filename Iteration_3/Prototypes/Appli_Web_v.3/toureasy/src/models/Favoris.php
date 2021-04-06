<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Favrosi associée à la table 'Favoris de la base de donneés
 **/
class Favoris extends Model
{
    protected $table = 'favoris';
    protected $primaryKey = 'idMonument';

    public function usesTimestamps() : bool
    {
        return false;
    }

}