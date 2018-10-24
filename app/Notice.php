<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notice extends Model
{
    protected $fillable=[
        "to_id",
        "from_id",
        "type_id",
        "arguments",
        "delivered",
        "link"

    ];

    /**
     * @param integer $to_id
     * @param string $type
     * @param array $variables
     * @param string $link
     * @param integer $from_id
     * @return int
     */
    public static function sendNotice($to_id, $type, $variables, $link=null, $from_id=0){
        if(!Notice::create([
            'to_id'=>$to_id,
            'from_id'=>$from_id,
            'type_id'=>NoticeType::all(['id', 'type'])->where('type', $type)->first()->id,
            'arguments'=>json_encode($variables),
            'link'=>$link
        ])) return -1;
        else return 0;
    }
    public function deliver(){
        $this->update([
            'delivered'=>1
        ]);
    }
    public static function deliverAll(){
        return self::whereRaw('delivered=0 and to_id='.Auth::id())->update([
            'delivered'=>1
        ]);
    }
    /**
     * @param $user_id
     * @return integer
     */
    public static function getNumberOfNewMessages($user_id){
        return self::whereRaw('to_id=? and delivered=?', [$user_id, 0])->get()->count();
    }
    public static function sendPush($id, $title, $body, $icon, $link){
        $Body = array(
            'to' => '/topics/'.$id,
            'notification' => array(
                'title' =>$title,
                'body' =>$body,
                'icon' =>$icon,
                'click_action' =>$link
            ),
        );


        $headers = array
        (
            'Authorization: key=AAAAK6W8aF8:APA91bFqVkvJ6aKWehI8mm0ONCNGdarpSEhmipxOhdrm8noepCArm2qcSMgglho6a8IA6g5JJwv9ZJVXb_p8A0KcoDPAQBcGPviIPrsgI-TVEdJL8yEv_0vYNP-llzw_8PD92hcx-sa1sPzI8kMyNJrGcGQ4Arj4QA',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $Body ) );
        curl_exec($ch );
        curl_close( $ch );
        return true;
    }
}
