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

    /**
     * @param $id
     * @return Membre[]
     */
    public static function getAllAmisByIdMembre($id)
    {
       $tmp1 = Amis::select('amis2 as amis')->where('amis1', '=', $id)->get();

        $tabAmis = [];
        foreach ($tmp1 as $tamis) {
            array_push($tabAmis, Membre::getMembreById($tamis["amis"]));
        }

        $tmp2 = Amis::select('amis1 as amis')->where('amis2', '=', $id)->get();
        foreach ($tmp2 as $tamis) {
            array_push($tabAmis, Membre::getMembreById($tamis["amis"]));
        }


        return $tabAmis;
        //return $tmp1->merge($tmp2);
    }

    /**
     * @param $id1
     * @param $id2
     * @return Amis
     */
    public static function getAmisByIds($id1, $id2): Amis
    {
        if($id1 < $id2){
            $amis = Amis::query()->where([['amis1', '=', $id1],['amis2',"=",$id2]])->first();
        } else {
            $amis = Amis::query()->where([['amis2', '=', $id1],['amis1',"=",$id2]])->first();
        }
        return $amis;
    }

    public function getDureeAmitie(){

        return $this->amis1;
    }


}