<?php

namespace App\Http\Controllers;

use App\CategoryTemp;
use App\CountryCode;
use App\GlobalCategory;
use App\Guest;
use App\Location;
use App\Market;
use App\Message;
use App\Notice;
use App\NoticeType;
use App\Order;
use App\RentItemTranslation;
use App\User;
use App\RentItem;
use function Faker\Provider\pt_BR\check_digit;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile($id){
        $id=intval($id);
        $user = User::all()->where('id', $id)->first();
        if(!$user) abort(404);
        if(\request()->has('my_profile') and !auth()->check()) return redirect("/login?redirect=" . url()->current());
        $locale=app()->getLocale();
        $logUser = User::all(["id", "role_id"])->where('id', Auth::id())->first();
        $user->me = Auth::check()?Auth::user()->role_id == 1 or Auth::user()->role_id == 3 or Auth::id() == $user->id:false;
        $rent=RentItem::all()->where('author', '=', $id)->sortByDesc("updated");
        $notice=Notice::all()->where('to_id', '=', $id)->sortByDesc("created_at");
        $for_moderation=[];
        $pagination_mdr=[];
        $moderateSize = null;
        $Orders = [];
        $Markets = Market::all();
        $Market = $Markets->where('administrator', $id)->first();
        if($logUser->role_id!=2){
            $quant=10;
            $quantity = count($notice);
            $origSize = $quantity;
            $fl = intval($origSize/$quant);
            $size = $fl<$origSize/$quant?$fl+1:$fl;
            $currentPage = \request('page_mdr')?\request('page_mdr'):1;
            $page = ($currentPage-1)*$quant;
            $currentLink = $_SERVER['REQUEST_URI'];
            if(\request()->has('page_mdr')) {
                if(strpos($currentLink, '?')) $currentLink = str_replace("page_mdr=".$currentPage, "page_mdr=", $currentLink);
            }
            else {
                if(strpos($currentLink, '?')) $currentLink = $currentLink."&page_mdr=";
                else $currentLink = $currentLink."?page_mdr=";
            }
            if($currentPage>$size) {$page=1;$currentPage=1;}
            $pagination_mdr = [["value"=>"", "icon"=>"fa fa-chevron-left", "class"=>"btn-primary", "link"=>$currentPage==1?"":$currentLink.($currentPage-1)]];
            array_push($pagination_mdr, ["value"=>"1", "icon"=>"", "class"=>"", "link"=>$currentPage==1?"":$currentLink."1"]);
            if($currentPage>3) array_push($pagination_mdr, ["value"=>"-", "icon"=>"", "class"=>"", "link"=>$currentLink.($currentPage-3)]);
            if($size) {
                if ($size>3 and $size - $currentPage < 2) array_push($pagination_mdr, ["value" => $size - 2, "icon" => "", "class" => "", "link" => $currentPage == $size - 2 ? "" : $currentLink . ($size - 2)], ["value" => $size - 1, "icon" => "", "class" => "", "link" => $currentPage == $size - 1 ? "" : $currentLink . ($size - 1)]);
                else if ($currentPage < 3 and $size>3) array_push($pagination_mdr, ["value" => 2, "icon" => "", "class" => "", "link" => $currentPage == 2 ? "" : $currentLink . "2"], ["value" => 3, "icon" => "", "class" => "", "link" => $currentPage == 3 ? "" : $currentLink . "3"]);
                else if ($size == 3) array_push($pagination_mdr, ["value" => 2, "icon" => "", "class" => "", "link" => $currentPage == 2 ? "" : $currentLink."2"]);
                else if ($size > 3) array_push($pagination_mdr, ["value" => $currentPage - 1, "icon" => "", "class" => "", "link" => $currentLink . ($currentPage - 1)], ["value" => $currentPage, "icon" => "", "class" => "", "link" => ""], ["value" => $currentPage + 1, "icon" => "", "class" => "", "link" => $currentLink . ($currentPage + 1)]);
            }
            if($size-$currentPage>3) array_push($pagination_mdr, ["value"=>"-", "icon"=>"", "class"=>"", "link"=>$currentLink.($currentPage+3)]);
            if($size>1) array_push($pagination_mdr, ["value"=>$size, "icon"=>"", "class"=>"", "link"=>$currentPage==$size?"":$currentLink.$size]);
            array_push($pagination_mdr, ["value"=>"", "icon"=>"fa fa-chevron-right", "class"=>"btn-primary", "link"=>($size and $currentPage!=$size)?$currentLink.($currentPage+1):""]);
            $moder = CategoryTemp::all()->forPage($page, $quant);
            $moderateSize = count($moder);
            foreach($moder as $moderate){
                if($moderate->image == null) $moderate->image = "default-images/no-image.jpeg";
                $name = json_decode($moderate->name);
                $moderate->name = $name->$locale?$name->$locale:$name->ru;
                $description = json_decode($moderate->description);
                $moderate->description = $description?$description->$locale?$description->$locale:$description->ru:"";
                array_push($for_moderation, $moderate);
            }
        }
        $k = 0;
        $notices=[];
        $quant=5;
        $newNotices = Notice::getNumberOfNewMessages($id);
        $quantity = count($notice);
        $hasNotice = $quantity;
        $origSize = $quantity;
        $fl = intval($origSize/$quant);
        $size = $fl<$origSize/$quant?$fl+1:$fl;
        $currentPage = \request('page_msg')?\request('page_msg'):1;
        $page = ($currentPage-1)*$quant;
        $currentLink = $_SERVER['REQUEST_URI'];
        if(\request()->has('page_msg')) {
            if(strpos($currentLink, '?')) $currentLink = str_replace("page_msg=".$currentPage, "page_msg=", $currentLink);
        }
        else {
            if(strpos($currentLink, '?')) $currentLink = $currentLink."&page_msg=";
            else $currentLink = $currentLink."?page_msg=";
        }
        if($currentPage>$size) {$page=1;$currentPage=1;}
        $pagination_msg = [["value"=>"", "icon"=>"fa fa-chevron-left", "class"=>"btn-primary", "link"=>$currentPage==1?"":$currentLink.($currentPage-1)]];
        array_push($pagination_msg, ["value"=>"1", "icon"=>"", "class"=>"", "link"=>$currentPage==1?"":$currentLink."1"]);
        if($currentPage>3) array_push($pagination_msg, ["value"=>"-", "icon"=>"", "class"=>"", "link"=>$currentLink.($currentPage-3)]);
        if($size) {
            if ($size>3 and $size - $currentPage < 2) array_push($pagination_msg, ["value" => $size - 2, "icon" => "", "class" => "", "link" => $currentPage == $size - 2 ? "" : $currentLink . ($size - 2)], ["value" => $size - 1, "icon" => "", "class" => "", "link" => $currentPage == $size - 1 ? "" : $currentLink . ($size - 1)]);
            else if ($currentPage < 3 and $size>3) array_push($pagination_msg, ["value" => 2, "icon" => "", "class" => "", "link" => $currentPage == 2 ? "" : $currentLink . "2"], ["value" => 3, "icon" => "", "class" => "", "link" => $currentPage == 3 ? "" : $currentLink . "3"]);
            else if ($size == 3) array_push($pagination_msg, ["value" => 2, "icon" => "", "class" => "", "link" => $currentPage == 2 ? "" : $currentLink."2"]);
            else if ($size > 3) array_push($pagination_msg, ["value" => $currentPage - 1, "icon" => "", "class" => "", "link" => $currentLink . ($currentPage - 1)], ["value" => $currentPage, "icon" => "", "class" => "", "link" => ""], ["value" => $currentPage + 1, "icon" => "", "class" => "", "link" => $currentLink . ($currentPage + 1)]);
        }
        if($size-$currentPage>3) array_push($pagination_msg, ["value"=>"-", "icon"=>"", "class"=>"", "link"=>$currentLink.($currentPage+3)]);
        if($size>1) array_push($pagination_msg, ["value"=>$size, "icon"=>"", "class"=>"", "link"=>$currentPage==$size?"":$currentLink.$size]);
        array_push($pagination_msg, ["value"=>"", "icon"=>"fa fa-chevron-right", "class"=>"btn-primary", "link"=>($size and $currentPage!=$size)?$currentLink.($currentPage+1):""]);
        foreach($notice->forPage(\request()->has('page_msg')?\request('page_msg'):1, $quant) as $value){
            $delivered = $value->delivered;
            $type = NoticeType::all()->where('id', $value->type_id)->first();
            $value->arguments=json_decode($value->arguments);
            $body=json_decode($type->message)->$locale;
            foreach ($value->arguments as $key=>$argument){
                $body->title = str_replace("{" . $key . "}", $argument, $body->title);
                $body->body = str_replace("{" . $key . "}", $argument, $body->body);
            }
            $value->name = isset($type->arguments->name)?$type->arguments->name:null;
            $value->icon = $type->icon;
            $value->delivered = $delivered;
            $value->message = $body;
            $value->created_at = $value->created_at->addHours(session()->has('timezone')?session()->get('timezone'):0);
            $k++;
            array_push($notices, $value);
        }
        $quant=10;
        $quantity = count($rent);
        $origSize = $quantity;
        $fl = intval($origSize/$quant);
        $size = $fl<$origSize/$quant?$fl+1:$fl;
        $currentPage = \request('page')?\request('page'):1;
        $page = ($currentPage-1)*$quant;
        $currentLink = $_SERVER['REQUEST_URI'];
        if(\request()->has('page')) {
            if(strpos($currentLink, '?')) $currentLink = str_replace("page=".$currentPage, "page=", $currentLink);
        }
        else {
            if(strpos($currentLink, '?')) $currentLink = $currentLink."&page=";
            else $currentLink = $currentLink."?page=";
        }
        if($currentPage>$size) {$page=1;$currentPage=1;}
        $pagination = [["value"=>"", "icon"=>"fa fa-chevron-left", "class"=>"btn-primary", "link"=>$currentPage==1?"":$currentLink.($currentPage-1)]];
        array_push($pagination, ["value"=>"1", "icon"=>"", "class"=>"", "link"=>$currentPage==1?"":$currentLink."1"]);
        if($currentPage>3) array_push($pagination, ["value"=>"-", "icon"=>"", "class"=>"", "link"=>$currentLink.($currentPage-3)]);
        if($size) {
            if ($size>3 and $size - $currentPage < 2) array_push($pagination, ["value" => $size - 2, "icon" => "", "class" => "", "link" => $currentPage == $size - 2 ? "" : $currentLink . ($size - 2)], ["value" => $size - 1, "icon" => "", "class" => "", "link" => $currentPage == $size - 1 ? "" : $currentLink . ($size - 1)]);
            else if ($currentPage < 3 and $size>3) array_push($pagination, ["value" => 2, "icon" => "", "class" => "", "link" => $currentPage == 2 ? "" : $currentLink . "2"], ["value" => 3, "icon" => "", "class" => "", "link" => $currentPage == 3 ? "" : $currentLink . "3"]);
            else if ($size == 3) array_push($pagination, ["value" => 2, "icon" => "", "class" => "", "link" => $currentPage == 2 ? "" : $currentLink."2"]);
            else if ($size > 3) array_push($pagination, ["value" => $currentPage - 1, "icon" => "", "class" => "", "link" => $currentLink . ($currentPage - 1)], ["value" => $currentPage, "icon" => "", "class" => "", "link" => ""], ["value" => $currentPage + 1, "icon" => "", "class" => "", "link" => $currentLink . ($currentPage + 1)]);
        }
        if($size-$currentPage>3) array_push($pagination, ["value"=>"-", "icon"=>"", "class"=>"", "link"=>$currentLink.($currentPage+3)]);
        if($size>1) array_push($pagination, ["value"=>$size, "icon"=>"", "class"=>"", "link"=>$currentPage==$size?"":$currentLink.$size]);
        array_push($pagination, ["value"=>"", "icon"=>"fa fa-chevron-right", "class"=>"btn-primary", "link"=>($size and $currentPage!=$size)?$currentLink.($currentPage+1):""]);
        $items = [];
        $Orders_history = [];
        $order_quantity=0;
        $Categories = GlobalCategory::all(['id', 'name', 'name_single', 'features', 'state']);
        if($user->me){
            foreach (Order::all()->where('user_id', $user->id)->sortByDesc('id') as $order){
                $temp = [];
                foreach (json_decode($order->items) as $item){
                    $value = $rent->where('id', $item->id)->first();
                    $category = $Categories->where('id', '=', $value->category)->first();
                    $features = json_decode($category->features, true);
                    $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='" . $locale . "'")->first();
                    if (!$translate) $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='ru'")->first();
                    $title = $translate['value'];
                    $feat = json_decode($value->features, true);
                    if($translate->attributes) foreach (json_decode($translate->attributes) as $string) {
                        $title = str_replace("{" . $string . "}", isset($feat[$string])?isset($features[$locale][$string]['options'][1])?$features[$locale][$string]['options'][$feat[$string]]." ":$feat[$string]:"", $title);
                    }
                    $item->category = json_decode($category->name_single)->$locale;
                    $item->title = $title;
                    array_push($temp, $item);
                }
                $order->items = $temp;
                if($order->status) array_push($Orders_history, $order);
                else {
                    $order_quantity++;
                    array_push($Orders, $order);
                }
            }
        }
        $locations=array();
        foreach (Location::all() as $item) $locations+=[$item->id=>$item->name];
        foreach ($rent->forPage(\request()->has('page')?\request('page'):1, $quant) as $key => $value) {
            $category = $Categories->where('id', '=', $value->category)->first();
            $features = json_decode($category->features, true);
            $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='" . $locale . "'")->first();
            if (!$translate) $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='ru'")->first();
            $title = $translate['value'];
            $feat = json_decode($value->features, true);
            if($translate->attributes) foreach (json_decode($translate->attributes) as $string) {
                $title = str_replace("{" . $string . "}", isset($feat[$string])?isset($features[$locale][$string]['options'][1])?$features[$locale][$string]['options'][$feat[$string]]." ":$feat[$string]:"", $title);
            }
            $address = explode("&", $value->address);
            if(count($address)==1) $address[1]="";
            $info = json_decode($value->additional_info);
            $value->additional_info = $info->$locale?$info->$locale:$info->ru;
            if (!$value->images) $value->images = ["default-images/no-image.jpeg"];
            else $value->images = json_decode($value->images);
            $value->category = $category;
            if($value->price == 0) $value->price = trans('rent.private_negotiation');
            $value->title = $title;
            if($value->market) $value->market = $Markets->where('slug', $value->market)->first();
            $address = explode("~", $value->address);
            $address_last = count($address)-1;
            $address_text = $address[$address_last];
            unset($address[$address_last]);
            $address = implode(", ", $address);
            $value->address = trim(strtr($address, $locations).($address_text!=-1?", ".$address_text:""),"/");
            array_push($items,$value);
        }

        return view('users.profile', [
            'locale'=>$locale,
            'Market'=>$Market,
            'title'=>trans('app.profile'),
            'user'=>$user,'rent'=>$items,
            'quantity'=>$quantity,
            'pagination'=>$pagination,
            'pagination_msg'=>$pagination_msg,
            'notices'=>$notices,
            'now'=>Carbon::now(),
            'newNotices'=>$newNotices,
            'hasNotice'=>$hasNotice,
            'moderations'=>$for_moderation,
            'pagination_mdr'=>$pagination_mdr,
            'moderateSize'=>$moderateSize,
            'Orders'=>$Orders,
            'Orders_history'=>$Orders_history,
            'order_quantity'=>$order_quantity
        ]);
    }
    public function update($id){
        $user = User::all()->where('id', $id)->first();
        if(!$user) abort(404);
        if(Auth::id()!=$id and $user->role_id !=1 and $user->role_id !=3) abort(404);
        if(!\request("current_password") or !Hash::check(\request('current_password'), $user->password)) return redirect()->back()->withInput()->withErrors(trans("auth.current_password_wrong"));
        $request = [
            'name' => 'required|string|max:255'
        ];
        $info = [
            'name'=>\request('name'),
            'gender'=>\request('gender'),
            'birth_date'=>\request('birth_date'),
        ];
        if(\request()->has('phone_number')) {
            $request+=['phone_number' => 'required|string|max:20|unique:users'];
            $info+=['phone_number'=>\request("phone_code").\request('phone_number')];
            $info+=['phone_code'=>\request('phone_code')];
        }
        if(\request('password') and \request('password') != \request('retype_password')) return redirect()->back()->withInput()->withErrors(["password"=>trans('auth.passwords_not_match')]);
        if(\request('password')) $info+=['password'=>bcrypt(\request('password'))];
        $validator =  Validator::make(\request()->all(), $request);
        if($validator->fails()) return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        $user->update($info);
        return redirect()->back()->with('success', trans('rent.updating_success'));
    }
    public function actionNotice(){
        if(Auth::check() and request()->has('id')) {
            $notice = Notice::all()->where('id', \request('id'))->first();
            if(!$notice) return abort(404);
            if (request()->has('delete')) $notice->delete();
            if (request()->has('deliver')) $notice->deliver();
            return redirect()->back();
        }
        elseif (request()->has('deliver_all')) {
            Notice::deliverAll();
            return redirect()->back();
        }
        else return abort(404);
    }
    public function checkExisting(){
        if(\request()->has("phone_number")) {
            if(User::all('phone_number')->where('phone_number', \request('phone_number'))->first()) return response()->json(['has' => true]);
            else return response()->json(['has' => false]);

        }
        else return response()->json(['has' => false]);
    }
    public function getNewMessages(){
        if(\request()->has('user_id')) {
            $message = Message::getNumberOfNewMessages(\request('user_id'));
            if($message) return response()->json($message);
        }
        return response()->json(false);
    }
    public function getContacts(){
        $Users = User::all();
        $Guest = Guest::all();
        if(\request()->has('user_id')) {
            $messages = Message::getMessagesByUserId(\request('user_id'));
            $users = [];
            $contacts = [];
            $newMessages = [];
            foreach ($messages as $message) {
                if ($message->to_id == \request('user_id')) {
                    $temp = $message->from_id;
                    if(!$message->delivered) {
                        if(!isset($newMessages[$temp])) $newMessages[$temp] = 1;
                        else $newMessages[$temp]++;
                    }
                }
                else $temp = $message->to_id;

                if (!isset($users[$temp])) $users[$temp] = true;
                else continue;
                $user = $Guest->where('phone_number', $temp)->first();
                if($user) {
                    $user->avatar = "users/default.png";
                    $user->id = -2;
                }
                else $user = $Users->where('id', $temp=="-1"?"0":$temp)->first();
                //if($temp=="-1") $user->id="0";
                array_push($contacts, ['user'=>$user, 'message'=>$message->body, 'date'=>$message->created_at->diffForHumans()]);
            }
            return response()->json(['contacts'=>$contacts, 'newMessages'=>$newMessages]);
        }
        return response()->json(false);
    }
    public function getMessagesWith(){
        if(\request()->has('user_id')) {
            $messageswith = [];
            $messages = Message::getMessagesByUserIdWith(\request("user_id"), \request("with_id"));
            $now = Carbon::now();
            $date="";
            $count=0;
            $timezone=session()->has('timezone')?session()->get('timezone'):0;
            $that = Guest::all(['id', 'phone_number', 'name', 'visited_at'])->where('phone_number', \request("with_id"))->first();
            if($that) {
                $that->avatar = "users/default.png";
                $that->id = -2;
                $that->visited = $that->isOnline();
            }
            else {
                $that = User::all('id', 'name', 'avatar', 'visited_at')->where('id', \request("with_id"))->first();
                $that->visited = $that->isOnline();
            }
            if(!$messages) return response()->json(false);
            foreach ($messages as $message) {
                $created_at = $message->created_at->addHours($timezone);
                $message->created = $created_at->format('H:i');
                if($message->to_id != \request("with_id") and !$message->delivered) $count++;
                $difference = ($message->created_at->diff($now)->days < 1)
                    ? trans("app.today")
                    : $message->created_at->diffForHumans($now);
                if($date != $difference) {
                    array_push($messageswith, $difference);
                    $date = $difference;
                }
                array_push($messageswith, $message);
            }
            Message::deliverByUserIdWith(\request('user_id'), \request('with_id'));
            return response()->json(['messages'=>$messageswith, 'user'=>$that,'new_messages'=>trans('auth.new_messages').($count?" (".$count.")":"")]);
        }
        return response()->json(false);
    }
    public function getNewNotices(){
        if(\request()->has('user_id')) {
            $message = Notice::getNumberOfNewMessages(\request('user_id'));
            if($message) return response()->json($message);
        }
        return response()->json(false);
    }
    public function getNewModerates(){
        if(\request()->has('user_id')) {
            $message = CategoryTemp::getNumberOfNewModeration();
            if($message) return response()->json($message);
        }
        return response()->json(false);
    }
    public function deliverNotices(){
        if(\request()->has('user_id')) {
            $message = Notice::deliverAll();
            if($message) return response()->json(true);
        }
        return response()->json(false);
    }
    public function sendMessage(){
        if(\request()->has('user_id') and \request()->has('to')) {
            $send = Message::send(\request('message'), \request('user_id'), \request('to'));
            if(!$send) return response()->json("Can't send message");
            else {
                Notice::sendPush(\request('to'), trans('auth.new_message'), (\auth()->check()?\auth()->user()->name:Guest::all(['phone_number', 'name'])->where('phone_number', \request('user_id'))->first()->name).": ".strip_tags(\request('message')), '/android-icon-144x144.png', '/');
            }
        }
        return response()->json(false);
    }
    public function guestCheck(){
        if(\request()->has('phone') && User::all()->where('phone_number', \request('phone'))->first()) {
            return response()->json(trans('auth.you_have_already_registered_for_guest'));
        }
        return response()->json(false);
    }
    public function createGuest(){
        if(\request()->has('phone') && \request()->has('name')){
            $guest = Guest::all(['id', 'phone_number', 'name'])->where('phone_number', "g-".\request('phone'))->first();
            if(!$guest) $guest = Guest::create([
                'name'=>\request('name'),
                'phone_number'=>"g-".\request('phone')
            ]);
            else $guest->update(['name'=>\request("name")]);
            session()->put('guest',"g-".\request('phone'));
            return response()->json(['has'=>false, 'guest'=>$guest]);
        }
        return response()->json(false);
    }
    public function guestLogout(){
        if(session()->has('guest')) session()->forget('guest');
        return redirect()->back();
    }
    public function userVisitUpdate(){
        if(\request()->has('auth')) {
            $user = User::all(['id', 'visited_at'])->where('id', \request('auth'))->first();
            if(!$user) $user = Guest::all('id', 'phone_number', 'visited_at')->where('phone_number', \request('auth'))->first();
            $user->update(['visited_at'=>Carbon::now()]);
        }
    }
    public function uploadAvatar(){
        if(Auth::check()){
            if($_FILES['image']['error']) return response()->json(false);
            $user = User::all(['id', 'avatar'])->where('id', Auth::id())->first();
            $image = GlobalCategory::uploadImage($_FILES['image'], 'users/'. date('FY', strtotime(date('d.m.Y'))) . "/", [400,400], false, 200);
            if($image[0] == -1) return response()->json(false);
            if(file_exists("storage/".$user->avatar) and $user->avatar != "users/default.png") unlink("storage/".$user->avatar);
            $user->update(['avatar'=>$image[1]]);
            return response()->json($image[1]);
        }
        return response()->json(false);
    }
    public function deleteAvatar(){
        if(Auth::check()){
            $user = User::all(['id', 'avatar'])->where('id', Auth::id())->first();
            if(file_exists("storage/".$user->avatar) and $user->avatar != "users/default.png") unlink("storage/".$user->avatar);
            $user->update(['avatar'=>"users/default.png"]);
            return response()->json(true);
        }
        return response()->json(false);
    }
    public function deleteMessagesWith(){
        if(\request()->has("id") && \request()->has('user_id')){
            if(\request("id") !=-1) Message::deleteMessages(\request('id'), \request('user_id'));
        }
    }
    public function deleteMessage(){
        if(\request()->has('id') && \request()->has('user_id')){
            if(\request("user_id") != -1) Message::deleteMessage(\request("id"), \request("user_id"));
        }
    }
    public function getPhoneProperties(){
        return response()->json(CountryCode::all());
    }
    public function getPhoneProperty(){
        if(\request()->has('code')) return response()->json(CountryCode::all()->where('code', \request('code'))->first());
        return response()->json(false);
    }
    public function deliveryUpdate($id){
        if(Auth::check() and auth()->user()->role_id == 1) {
            User::all(['id', 'delivery'])->where('id', $id)->first()->update(['delivery'=>\request('delivery-edit')?\request('delivery-edit'):null]);
            return response()->json(\request('delivery-edit')?true:false);
        }
        else return false;
    }
    public function setFirebaseToken(Request $request){
        if(!Auth::check()) return response()->json(false);
        User::all()->where('id', auth()->id())->first()->update(['firebase_token'=>$request->get('key')]);
        return response()->json(true);
    }
    public function getSubscribeInterface(Request $request){
        return view('inc.subscribe');
    }
}

