<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTemp extends Model
{
    protected $fillable=[
        "parent_id",
        "order",
        "name",
        "image",
        "original_id",
        "extends",
        "author_id",
        "description",
        "features",
        "type"
    ];
    public static function hasId($id){
        $self = self::all(['id', 'original_id'])->where('id',"=", $id)->first();
        if($self) return $self->orignial_id;
        return false;
    }
    public static function hasOriginalId($original_id){
        $self = self::all(['id', 'original_id'])->where('original_id',"", $original_id)->first();
        if($self) return $self->id;
        return false;
    }

    /**
     * @return int
     * @internal param $user_id
     */
    public static function getNumberOfNewModeration(){
        return self::all()->count();
    }
}
