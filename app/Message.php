<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'to_id',
        'from_id',
        'from_deleted',
        'to_deleted',
        'delivered',
        'body',
        'guest'
    ];
    public static function send($message, $from_id, $to_id, $guest=false){
        $create = [
            'body'=>$message,
            'from_id'=>$from_id,
            'to_id'=>$to_id
        ];
        if($guest) $create+=['guest'=>1];
        return self::create($create);
    }
//    public static function deliver($id){
//        return self::all('id', 'deliver')->where('id', $id)->first()->update(["delivered"=>1]);
//    }
    public static function deleteMessages($id, $user_id){
        $messageFrom = self::whereRaw('to_id=? and from_id=?', [$id,$user_id])->get();
        $messageMy = self::whereRaw('to_id=? and from_id=?', [$user_id,$id])->get();
        foreach($messageFrom as $message){
            if($message->from_deleted) $message->delete();
            else $message->update(['to_deleted'=>1]);
        }
        foreach($messageMy as $message){
            if($message->to_deleted) $message->delete();
            else $message->update(['from_deleted'=>1]);
        }
        return false;
    }
    public static function deleteMessage($id, $user_id){
        $message = self::all()->where('id', $id)->first();
        if($message->to_id==$user_id) {
            if($message->from_deleted) $message->delete();
            else $message->update(['to_deleted'=>1]);
        }
        else $message->delete();
        return false;
    }

    /**
     * @param $id
     * @return Message
     */
    public static function getMessagesByUserId($id){
        return self::whereRaw('to_id=? and to_deleted!=1 or from_id=? and from_deleted!=1', [$id, $id])->orderByDesc('id')->get();
    }
    /**
     * @param $id
     * @return Message
     */
    public static function getMessagesByUserIdWith($id, $that){
        return self::whereRaw('to_id=? and from_id=? and to_deleted!=1 or from_id=? and to_id=? and from_deleted!=1', [$id, $that, $id, $that])->get();
    }
    public static function deliverByUserIdWith($me, $that){
        return self::whereRaw('to_id=? and from_id=?', [$me, $that])->update(['delivered'=>1]);
    }

    /**
     * @param $user_id
     * @return integer
     */
    public static function getNumberOfNewMessages($user_id){
        return self::whereRaw('to_id=? and delivered=?', [$user_id, 0])->get()->count();
    }
}
