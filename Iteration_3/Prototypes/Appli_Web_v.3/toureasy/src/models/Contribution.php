<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public static function getMonumentByIdCreator($id)
    {
        return Contribution::query()->where([['contributeur', '=', $id],['estNouveauMonument', '=', 1]])->get();
    }

    public static function getContributionByIdMonument($id)
    {
        return Contribution::query()->where('monumentTemporaire', '=', $id)->first();
    }

    public static function getMonumentsTemporaires() {
        $res = [];
        $contributions = Contribution::query()->where('statutContribution','=','enAttenteDeTraitement')->get()->toArray();
        foreach ($contributions as $c) {
            $token = Monument::getMonumentById($c['monumentTemporaire'])->token;
            try {
                $tokenO = Monument::getMonumentById($c['monumentAModifier'])->firstOrFail()->token;
            } catch (ModelNotFoundException $e) {
                $tokenO = $token;
            }

            array_push($res, ["Contribution" => $c, "url" => ($token), "originel" => $tokenO]);
        }
        return $res;
    }

}