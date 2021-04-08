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
}