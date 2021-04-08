<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Contribution associée à la table 'contribution' de la base de donneés
 **/
class Contribution extends Model
{
    protected $table = 'contribution';
    protected $primaryKey = 'idContribution';

    public function usesTimestamps() : bool
    {
        return false;
    }
}