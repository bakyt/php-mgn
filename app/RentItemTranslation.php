<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentItemTranslation extends Model
{
    protected $fillable=[
        'category_id',
        'locale',
        'key',
        'value',
        'attributes'
    ];
    public static function forCategory($cat_id){
        $result = json_decode("{}");
        foreach(self::all()->where('category_id', $cat_id) as $trans){
            $locale = $trans->locale;
            $result->$locale = $trans->value;
        }
        return $result;
    }
    public static function updater($cat_id, $value, $var){
        foreach(self::all()->where('category_id', $cat_id) as $trans){
            $lang = $trans->locale;
            $trans->update(["value"=>$value[$lang], "attributes"=>$var]);
        }
    }
    public static function creator($cat_id, $value, $var){
        if(is_array($value)) foreach($value as $lang=>$val){
            self::create([
                'category_id'=>$cat_id,
                'locale'=>$lang,
                'key'=>'title',
                'value'=>$val,
                'attributes'=>$var
            ]);
        }
    }
    public static function deletor($cat_id){
        foreach(self::all()->where('category_id', $cat_id) as $val){
            $val->delete();
        }
    }
    public static function has($cat_id){
        if(self::all()->where('category_id', $cat_id)->first()) return true;
        else return false;
    }
}
