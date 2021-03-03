<?php

namespace toureasy\models;
use Illuminate\Database\Eloquent\Model;
/**
 * Classe Image associée à la table 'image' de la base de donneés
 **/
class Image extends Model
{
    protected $table = 'image';
    protected $primaryKey = ['numeroImage', 'idMonument'];
    public $incrementing = false;

    public function usesTimestamps() : bool
    {
        return false;
    }

    public static function getImageUrlByIdMonument($id) {
        return Image::query()->where('idMonument', '=', $id)->get()->toArray();
    }
}