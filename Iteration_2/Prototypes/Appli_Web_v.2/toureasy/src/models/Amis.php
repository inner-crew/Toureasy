<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Contribution associée à la table 'amis' de la base de donneés
 **/
class Amis extends Model
{
    protected $table = 'amis';
    //protected $primaryKey = ['amis1','amis2'];
    protected $primaryKey = 'amis1';

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getAllAmisByIdMembre($id)
    {
        $tmp1 = Amis::select('amis2')->where('amis1', '=', $id)->get();
        $tmp2 = Amis::select('amis1')->where('amis2', '=', $id)->get();

        return $tmp1->merge($tmp2);
    }


}