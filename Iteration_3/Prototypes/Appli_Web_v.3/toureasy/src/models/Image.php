<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Image associée à la table 'image' de la base de donneés
 **/
class Image extends Model
{
    protected $table = 'image';
    protected $primaryKey = 'idMonument';
    public $incrementing = false;

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getImageUrlByIdMonument($id) {
        return Image::query()->where('idMonument', '=', $id)->get()->toArray();
    }

    public static function copierImageMonumentPublicANouveau($idMonumentNouveau, $idMonumentOriginel) {
        $images = Image::query()->where('idMonument','=',$idMonumentOriginel)->get();
        $dernierId = Image::query()->where('idMonument','=', $idMonumentNouveau)->orderBy('numeroImage','DESC')->select('numeroImage')->first();
        $dernierId++;
        foreach ($images as $i) {
            $image = new Image();
            $image->numeroImage = $dernierId;;
            $image->idMonument = $idMonumentNouveau;
            $image->urlImage = $i->urlImage;
            $image->save();
            $dernierId++;
        }
    }

    public static function getImagesIdByIdMonument($id) {
        return Image::query()->where('idMonument', '=', $id)->select('numeroImage')->get();
    }

    public static function getImagesByIdMonument($id) {
        return Image::query()->where('idMonument', '=', $id)->get();
    }

    public static function supprimerImageById($idImage, $idMonument) {
        Image::query()->where([['numeroImage', '=', $idImage], ['idMonument', '=', $idMonument]])->delete();
    }
}