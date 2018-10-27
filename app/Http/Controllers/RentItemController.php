<?php

namespace App\Http\Controllers;

use App\Category;
use App\GlobalCategory;
use App\Location;
use App\Market;
use App\Notice;
use App\Order;
use App\RentDeletedItem;
use App\RentItem;
use App\RentItemTranslation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentItemController extends Controller
{
    public function listItems($id)
    {
        $id = intval($id);
        $locale = app()->getLocale();
        $category = GlobalCategory::all()->where("id", $id)->first();
        $Markets = Market::all();
        if(!$category) return abort(404);
        $locations=array();
        $cities ="";
        foreach (Location::all() as $item) {
            $locations+=[$item->id=>$item->name];
            if($item->parent_id == 1) $cities.=$item->name.", ";
        }
        $parent = json_decode(Category::all(['id', 'name'])->where("id", $category->parent_id)->first()['name'], true);
        $parent_id = $category->parent_id;
        if ($category['parent_id'] == null) {
            return abort(404);
        }
        $name = json_decode($category->name, true);
        $category->features = json_decode( $category->features,true);
        $category->keywords = json_decode( $category->keywords,true);
        $name[$locale] ? $category->name = $name[$locale] : $category->name = $name['ru'];
        $quant = 12;
        $currentPage = \request('page')?\request('page'):1;
        $page = ($currentPage-1)*$quant;
        $items = RentItem::all()->where('category', $id)->sortByDesc('updated');
        if(!\request()->has('find')) {
            $rent=[];
            $count=0;

            foreach ($items as $value) {
                if($count<$page) {
                    $count++;
                    continue;
                }
                $value->author = User::all()->where('id', $value->author)->first();
                if(!$value->author) {$items->where('id', $value->id)->first()->delete(); continue;}
                array_push($rent, $value);
                $count++;
                if($count-$page == $quant) break;
            }
        }
        else {
            $req = "";
            $param=array();
            if(\request('address')) {
                $add="";
                foreach (\request('address') as $item) {if($item) $add.="~".$item;else break;}
                $req.=" and address LIKE ?";
                array_push($param, trim($add, "~").'%');
            }
            if(\request('payment_time')) {
                $req.=" and payment_time = ?";
                array_push($param, \request('payment_time'));
            }
            if(\request('state')) {
                $req.=" and state = ?";
                array_push($param, \request('state'));
            }
            if(\request('price_from')){
                $req.=" and price >= ?";
                array_push($param, \request('price_from'));
            }
            if(\request('price_to')){
                $req.=" and price <= ?";
                array_push($param, \request('price_to'));
            }
            if(\request()->has('type')){
                $req.=" and type = ?";
                array_push($param, \request('type'));
            }
            $rents = RentItem::whereRaw('category='.$id.$req, $param)->orderByDesc('updated')->get();
            $rent=[];
            $count=0;
            foreach ($rents as $item){
                if($count<$page) {
                    $count++;
                    continue;
                }
                $check = true;
                $sfeatures = json_decode($item->features);
                if(\request()->has('features')) foreach (\request('features') as $key=>$value) {
                    if(!isset($sfeatures->$key)) continue;
                    if(is_array($sfeatures->$key)) {foreach($sfeatures->$key as $val) if(@strpos($val, $value) !== false or $value == '-1') $value="";
                    }
                    else if(@strpos($sfeatures->$key, $value) !== false or $value == '-1') $value="";
                    if($value == "") continue;
                    else {
                        $check=false;
                        break;
                    }
                }
                if(!$check) continue;
                $item->author = User::all()->where('id', $item->author)->first();
                if(!$item->author) {$items->where('id', $item->id)->first()->delete(); continue;}
                array_push($rent, $item);
                $count++;
                if($count-$page == $quant) break;
            }
        }
        $origSize = count($items);
        $fl = intval($origSize/$quant);
        $size = $fl<$origSize/$quant?$fl+1:$fl;
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


        $translate = RentItemTranslation::whereRaw("category_id='" . $id . "' and locale='" . $locale . "'")->first();
        if (!$translate) $translate = RentItemTranslation::whereRaw("category_id='" . $id . "' and locale='ru'")->first();
        foreach ($rent as $key => $value) {
            $title = $translate['value'];
            $feat = json_decode($value->features, true);
            $features = [];
            if($feat) foreach ($feat as $item => $val) {
                if(isset($category->features[$locale][$item])){
                if(@$category->features[$locale][$item][$val]) $features += [$category->features[$locale][$item]['name'] => $category->features[$locale][$item][$val] . " " . $category->features[$locale][$item]['addon']];
                    else {
                        if(is_array($val) or @$category->features[$locale][$item]['options'][0]){
                            $temp=[];
                            for($i=0; $i<count($val); $i++) {
                                array_push($temp, @$category->features[$locale][$item]['options'][$val[$i]]);
                            }
                            $val = implode(",", $temp);
                        }
                        $features += [$item=>[$category->features[$locale][$item]['name'], $val . " " . $category->features[$locale][$item]['addon']]];
                    }
                }
            }
            if($translate->attributes) foreach (json_decode($translate->attributes) as $string) {
                $title = str_replace("{" . $string . "}", isset($features[$string][1])?$features[$string][1]:"", $title);
            }
            $value->features = $features;
            if($value->price == 0) $value->price = trans('rent.private_negotiation');
            $value->phone_number=json_decode($value->phone_number);
            $address = explode("~", $value->address);
            $address_last = count($address)-1;
            $address_text = $address[$address_last];
            unset($address[$address_last]);
            $address = implode(", ", $address);
            $value->address = trim(strtr($address, $locations).($address_text!=-1?", ".$address_text:""),"/");
            $info = json_decode($value->additional_info);
            $value->additional_info = $info->$locale?$info->$locale:$info->ru;
            if (!$value->images) $value->images = json_encode(["default-images/no-image.jpeg"]);
            $value->images = json_decode($value->images);
            $value->title = json_decode($category->name_single)->$locale." ".$title;
            $value->market = $Markets->where('slug', $value->market)->first();
            $rent[$key] = $value;
        }
        $parent = @$parent[$locale] ? $parent[$locale] : $parent['ru'];
        return view('item.list', ['pathway' => [["title" => $parent, "link" => "/category/" . $parent_id], ["title" => $category->name]], 'title_add'=>$category->type?$category->type==1?trans('rent.rent'):trans('rent.sale'):trans('rent.sale_and_rent'), 'title' => $category->name, 'title_right'=>' ('.trim($cities, ', ').")", 'items' => $rent, 'id'=>false, 'locale'=>$locale, 'category'=>$category, 'pagination'=>$pagination, 'Image'=>$category->image]);
    }

    public function item($id)
    {
        $id = intval($id);
        $locale = app()->getLocale();
        $locations=array();
        foreach (Location::all() as $item) $locations+=[$item->id=>$item->name];
        $rent = RentItem::all()->where('id', $id)->first();
        if(!$rent) return abort(404);
        $category = GlobalCategory::all()->where("id", $rent->category)->first();
        $category->features = json_decode($category->features, true);
        $parent = json_decode(Category::all(['id', 'name'])->where("id", $category->parent_id)->first()['name'], true);
        $cat_id = $category['id'];
        $parent_id = $category->parent_id;
        $category->name = json_decode($category['name'], true);
        $translate = RentItemTranslation::whereRaw("category_id='" . $rent->category . "' and locale='" . $locale . "'")->first();
        if (!$translate) $translate = RentItemTranslation::whereRaw("category_id='" . $rent->category . "' and locale='ru'")->first();
        $title = $translate['value'];
        $feat = json_decode($rent->features, true);
        $features = [];
        if($feat) foreach ($feat as $key => $value) {
            if(@$category->features[$locale][$key][$value]) $features += [$category->features[$locale][$key]['name'] => $category->features[$locale][$key][$value]." ".$category->features[$locale][$key]['addon']];
            else {
                if(is_array($value) or @$category->features[$locale][$key]['options'][0]){
                    $temp=[];
                    for($i=0; $i<count($value); $i++) {
                        array_push($temp, @$category->features[$locale][$key]['options'][$value[$i]]);
                    }
                    $value = implode(",", $temp);
                }
                if(isset($category->features[$locale][$key]['name'])) $features += [$key=>[$category->features[$locale][$key]['name'], $value . " " . $category->features[$locale][$key]['addon']]];
            }
        }
        foreach (json_decode($translate->attributes) as $string) {
            $title = str_replace("{" . $string . "}", isset($features[$string][1])?$features[$string][1]:"", $title);
        }

        $category->name = @$category->name[$locale] ? $category->name[$locale] : $category->name['ru'];
        $rent->title = $title;
        $rent->payment_time=trans('rent.'.$category->payment_time);
        $rent->phone_number=json_decode($rent->phone_number);
        $rent->features = $features;
        if($rent->price == 0) $rent->price = trans('rent.private_negotiation');
        $info = json_decode($rent->additional_info);
        $rent->additional_info = $info->$locale?$info->$locale:$info->ru;
        $address = explode("~", $rent->address);
        $address_last = count($address)-1;
        $address_text = $address[$address_last];
        unset($address[$address_last]);
        $address = implode(", ", $address);
        $rent->address = trim(strtr($address, $locations).($address_text!=-1?", ".$address_text:""),"/");
        if (!$rent->images) $rent->images = json_encode(["default-images/no-image.jpeg"]);
        $rent->images = json_decode($rent->images);
        $title.= isset($rent->features['payment_time'])?", ".$rent->features['payment_time'][1]:"";
        $rent->author = User::all()->where('id', $rent->author)->first();
        if(!$rent->author) {$rent->delete(); return abort(404);}
        $parent = @$parent[$locale] ? $parent[$locale] : $parent['ru'];
        $rent->market = Market::all()->where('slug', $rent->market)->first();
        return view('item.view', [
            'pathway' => [
                ["title" => $parent, "link" => "/category/" . $parent_id],
                ["title" => $category->name, "link" => "/list/" . $cat_id]
            ],
            'title_add'=>$rent->type?trans('rent.sale'):trans('rent.rent'),
            'title' => $title,
            'item' => $rent,
            'id'=>$id,
            'Image'=>$rent->images[0],
            'category'=>$category
        ]);
    }

    public function createCategorySelector()
    {
        if(cache()->has('select_categories')) $categories = cache('select_categories');
        else {
            $cats = Category::all()->where('active', 1);
            $cates = GlobalCategory::all();
            $categories = [];
            foreach ($cats as $key => $value) {
                $chal = $cates->where('parent_id', $value->id);
                if (!$chal->first()) continue;
                $child = [];
                $value->name = json_decode($value->name);
                foreach ($chal as $kay => $vale) {
                    $vale->name = json_decode($vale->name);
                    $vale->name_single = json_decode($vale->name_single);
                    $child[$kay] = $vale;
                }
                $value->child = $child;
                $categories[$key] = $value;
            }
            cache()->forever('select_categories', $categories);
        }
//        cache()->flush();
        return view('item.createCategorySelector', [
            'title' => trans("rent.new_item"),
            "categories" => $categories,
            'locale'=>app()->getLocale()
        ]);
    }

    public function createCategorySelect()
    {
        if(\request()->has("category")) return redirect()->route("item.create", [\request("category"),\request("type")]);
        return abort(404);
    }
    public function create($catId, $type){
        $catId = intval($catId);
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        $locale = app()->getLocale();
        $category = GlobalCategory::all()->where("id", "=", $catId)->first();
        if($category->type and $category->type != $type+1) return redirect()->back()->withErrors(trans('rent.not_available_category'));
        if(setting('site.rent_limit') == RentItem::all()->where('author', Auth::id())->count()) return redirect()->back()->withErrors(trans('rent.item_limit'));
        $category->name = json_decode($category->name_single)->$locale;
            $category->features = json_decode($category->features, true);
            $category->keywords = json_decode( $category->keywords,true);
            $category->description = json_decode($category->description);
        return view('item.create', ['type'=>$type, 'pathway'=>[['title'=>$category->name, 'link'=>'/list/'.$catId],['title'=>trans("rent.new_item")]],'title' => trans("rent.new_item"), "locales" => config("app.locales"), "category" => $category, 'locale' => $locale]);
    }
    public function store($catId, $type){
        $catId = intval($catId);
        if (Auth::check()) {
            if (!request()->has("save")) return abort(404);
            $locale = app()->getLocale();
            $address = \request("address");
            if(!$address[0]) return redirect()->back()->withInput()->withErrors(trans('rent.address_empty'));
            array_push($address, \request("address_text")?\request("address_text"):-1);
            $features = \request('features');
            $error = false;
            $categ = GlobalCategory::all(['id', 'parent_id', 'features', 'keywords', 'name', 'name_single'])->where('id', '=', $catId)->first();
            if($categ->type and $categ->type != $type+1) return redirect()->back()->withErrors(trans('rent.not_available_category'));
            $keywords = json_decode($categ->keywords, true);
            if(\request()->has('features'))
                foreach (json_decode($categ->features)->$locale as $key=>$value){
                if(!$value->options[0]) {
                    if(isset($keywords[$key])) {
                        if(!in_array($features[$key], $keywords[$key])) array_push($keywords[$key], $features[$key]);
                    }
                    else $keywords[$key][] = $features[$key];
                    $categ->update([
                        'keywords'=>json_encode($keywords)
                    ]);
                }
                if($value->required) {
                    if(array_has($features, $key)) {if($features[$key]=="") $error=true;}
                    else $error=true;
                }
                if($error) return redirect()->back()->withInput()->withErrors(trans('rent.required_field', ['field'=>$value->$locale->name]));
            }
            $locations=array();
            $cities ="";
            $addressForDb = str_replace("0~", "", implode("~", $address));
            foreach (Location::all() as $value) {
                $locations+=[$value->id=>$value->name];
                if($value->parent_id == 1) $cities.=$value->name.", ";
            }
            $address = explode("~", $addressForDb);
            $address_last = count($address)-1;
            $address_text = $address[$address_last];
            unset($address[$address_last]);
            $address = implode(", ", $address);
            $content=[
                'title'=>[
                    'ru'=>'',
                    'en'=>'',
                    'kg'=>''
                ],
                'body'=>[
                    'ru'=>'',
                    'en'=>'',
                    'kg'=>''
                ],
                'category'=>json_decode($categ->name, true),
                'category_single'=>json_decode($categ->name_single, true),
                'keywords'=>$type?setting('keywords.sale_keywords'):setting('keywords.rent_keywords'),
                'address'=>trim(strtr($address, $locations).($address_text!=-1?", ".$address_text:""),"/")
            ];
            $categ->features = json_decode($categ->features, true);
            foreach (config('app.locales') as $loc){
                $featur="";
                $feat=[];
                if($features) foreach ($features as $key => $val) {
                    if(isset($categ->features[$loc][$key])){
                        if(@$categ->features[$loc][$key][$val]) $featur .= @$categ->features[$loc][$key]['name'] .': '. @$categ->features[$loc][$key][$val] . " " . @$categ->features[$loc][$key]['addon']. ' | ';
                        else {
                            if(is_array($val) or @$categ->features[$loc][$key]['options'][0]){
                                $temp=[];
                                for($i=0; $i<count($val); $i++) {
                                    array_push($temp, @$categ->features[$loc][$key]['options'][$val[$i]]);
                                }
                                $val = implode(",", $temp);
                            }
                            $feat[$key]=$val . " " . @$categ->features[$key]['addon'];
                            $featur .= @$categ->features[$loc][$key]['name'].": ". $val . " " . @$categ->features[$loc][$key]['addon']." | ";
                        }
                    }
                }
                $translate = RentItemTranslation::whereRaw("category_id=? and locale=?", [$categ->id, $loc])->first();
                $title = $translate['value'];
                foreach (json_decode($translate->attributes) as $string) {
                    $title = str_replace("{" . $string . "}", isset($feat[$string])?$feat[$string]:"", $title);
                }
                $content['title'][$loc] = $title;
                $content['body'][$loc] = $featur;
            }
            $images = array();
            if($_FILES['images']['size'][0]){
                $image = GlobalCategory::uploadImage($_FILES['images'], "rent-items/" . date('FY', strtotime(date('d.m.Y'))) . "/", [768,768]);
                if ($image[0] == -1) return redirect()->back()->withInput()->withErrors($image[1]);
                $images = $image[1];
            }
            $item = RentItem::create([
                'images'=>$images?json_encode($images):null,
                'category'=>$catId,
                'priority'=>1,
                'author'=> Auth::id(),
                'updated'=>Carbon::now(),
                'phone_number'=>json_encode(\request("phone_number")),
                'messengers'=>\request("messengers"),
                'additional_info'=>json_encode(\request("additional_info")),
                'features'=>json_encode(\request("features")),
                'content'=>json_encode($content, JSON_UNESCAPED_UNICODE),
                'price'=>\request("price"),
                'address'=>$addressForDb,
                'type'=>$type,
                'state'=>\request()->has('state')?\request('state'):0
            ]);
            if (!$item) return redirect()->back()->withInput()->withErrors(trans('rent.something_wrong')."!");
        } else return redirect("/login")->withErrors(trans('rent.must_login')."!");
        if(\request()->wantsJson()) return response()->json('/view/'.$item->id);
        return redirect()->route("item.view", $item->id)->with("success", trans('rent.adding_success')."!");
    }
    public function update($id,$type){
        $id=intval($id);
        if (Auth::check()) {
            $item = RentItem::all()->where('id', $id)->first();
            $role = User::all(['id', 'role_id'])->where('id', Auth::id())->first()['role_id'];
            if(Auth::id() != $item->author and $role != 3 and $role != 1) return abort(404);
            if (!$item or !request()->has("save") and !request()->has("delete") and !request()->has("update")) return abort(404);
            if (\request()->has('delete')) {
                    RentDeletedItem::create([
                        'category'=>$item->category,
                        'author'=>$item->author,
                        'phone_number'=>$item->phone_number,
                        'messengers'=>$item->messengers,
                        'additional_info'=>$item->additional_info,
                        'features'=>$item->features,
                        'price'=>$item->price,
                        'address'=>$item->address,
                    ]);
                    if($item->images) foreach (json_decode($item->images) as $key=>$image){
                    if(file_exists("storage/".$image)) unlink("storage/".$image);
                    }
                    $item->delete();
                    return redirect('/users/'.Auth::id())->with("success", trans('rent.deleting_success'));
            }
            else if(\request('update')){
                    $item->update(['updated'=>Carbon::now()]);
                    return redirect()->back()->with("success", trans('rent.updating_success'));
            }
            $locale=app()->getLocale();
            $address = \request("address");
            if(!$address[0]) return redirect()->back()->withInput()->withErrors(trans('rent.address_empty'));
            array_push($address, \request("address_text")?\request("address_text"):-1);
            $features = \request('features');
            $error = false;
            $categ = GlobalCategory::all(['id', 'parent_id', 'features', 'keywords', 'name', 'name_single'])->where('id', '=', $item->category)->first();
            if($categ->type and $categ->type != $type+1) return redirect()->back()->withErrors(trans('rent.not_available_category'));
            $keywords = json_decode($categ->keywords, true);
            if(\request()->has("features")) foreach (json_decode($categ->features)->$locale as $key=>$value){
                if ($type and $value->rent and !$value->sale or !$type and $value->sale and !$value->rent) continue;
                if(!$value->options[0]) {
                    if(isset($keywords[$key])) {
                        if(isset($features[$key]) and !in_array(@$features[$key], $keywords[$key])) array_push($keywords[$key], $features[$key]);
                    }
                    else $keywords[$key][] = $features[$key];
                    $categ->update([
                        'keywords'=>json_encode($keywords)
                    ]);
                }
                if($value->required and $value->sale and $type or $value->required and $value->rent and !$type) {
                    if(array_has($features, $key)) {if($features[$key]=="") $error=true;}
                    else $error=true;
                }
                if($error) return redirect()->back()->withInput()->withErrors(trans('rent.required_field', ['field'=>$value->name]));
            }
            $locations=[];
            $cities ="";
            $addressForDb = str_replace("0~", "", implode("~", $address));
            foreach (Location::all() as $value) {
                $locations+=[$value->id=>$value->name];
                if($value->parent_id == 1) $cities.=$value->name.", ";
            }
            $address = explode("~", $addressForDb);
            $address_last = count($address)-1;
            $address_text = $address[$address_last];
            unset($address[$address_last]);
            $address = implode(", ", $address);
            $content=[
                'title'=>[
                    'ru'=>'',
                    'en'=>'',
                    'kg'=>''
                ],
                'body'=>[
                    'ru'=>'',
                    'en'=>'',
                    'kg'=>''
                ],
                'category'=>json_decode($categ->name, true),
                'category_single'=>json_decode($categ->name_single, true),
                'keywords'=>$type?setting('keywords.sale_keywords'):setting('keywords.rent_keywords'),
                'address'=>trim(strtr($address, $locations).($address_text!=-1?", ".$address_text:""),"/")
            ];
            $categ->features = json_decode($categ->features, true);
            foreach (config('app.locales') as $loc){
                $featur="";
                $feat=[];
                if($features) foreach ($features as $key => $val) {
                    if(isset($categ->features[$loc][$key])){
                        if(@$categ->features[$loc][$key][$val]) $featur .= @$categ->features[$loc][$key]['name'] .': '. @$categ->features[$loc][$key][$val] . " " . @$categ->features[$loc][$key]['addon']. ' | ';
                        else {
                            if(is_array($val) or @$categ->features[$loc][$key]['options'][0]){
                                $temp=[];
                                for($i=0; $i<count($val); $i++) {
                                    array_push($temp, @$categ->features[$loc][$key]['options'][$val[$i]]);
                                }
                                $val = implode(",", $temp);
                            }
                            $feat[$key]=$val . " " . @$categ->features[$key]['addon'];
                            $featur .= @$categ->features[$loc][$key]['name'].": ". $val . " " . @$categ->features[$loc][$key]['addon']." | ";
                        }
                    }
                }
                $translate = RentItemTranslation::whereRaw("category_id=? and locale=?", [$categ->id, $loc])->first();
                $title = $translate['value'];
                foreach (json_decode($translate->attributes) as $string) {
                    $title = str_replace("{" . $string . "}", isset($feat[$string])?$feat[$string]:"", $title);
                }
                $content['title'][$loc] = $title;
                $content['body'][$loc] = $featur;
            }
//            echo '<pre>';
//            print_r($content);
//            die();
            $images = array();
            $old_images = \request('old_images');
            if($item->images) foreach (json_decode($item->images) as $key=>$image){
                if($old_images and array_key_exists($key, $old_images)) array_push($images, $image);
                else if(file_exists("storage/".$image)) unlink("storage/".$image);
            }
            if($_FILES['images']['size'][0]){
                $image = GlobalCategory::uploadImage($_FILES['images'], "rent-items/" . date('FY', strtotime(date('d.m.Y'))) . "/", [768,768]);
                if ($image[0] == -1) return redirect()->back()->withInput()->withErrors($image[1]);
                $images = array_merge($images , $image[1]);
            }
            $item->update([
                'images'=>$images?json_encode($images):null,
                'phone_number'=>json_encode(\request("phone_number")),
                'messengers'=>\request("messengers"),
                'additional_info'=>json_encode(\request("additional_info")),
                'features'=>json_encode(\request()->get("features")),
                'content'=>json_encode($content, JSON_UNESCAPED_UNICODE),
                'price'=>\request("price"),
                'address'=>$addressForDb,
                'updated'=>Carbon::now(),
                'type'=>$type,
                'state'=>\request()->has('state')?\request('state'):0
            ]);
            if (!$item) return redirect()->back()->withInput()->withErrors(trans('rent.something_wrong')."!");
        } else return redirect("/login")->withErrors(trans('rent.must_login')."!");
        if(\request()->wantsJson()) return response()->json('/view/'.$item->id);
        return redirect()->route("item.view", $item->id)->with("success", trans('rent.updating_success')."!");
    }
    public function edit($id, $type){
        $id=intval($id);
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        $locale = app()->getLocale();
        $item = RentItem::all()->where("id", "=", $id)->first();
        if(!$item) abort(404);
        $role = User::all(['id', 'role_id'])->where('id', Auth::id())->first()['role_id'];
        if(Auth::id() != $item->author and $role != 3 and $role != 1) return abort(404);
        $item->features = json_decode($item->features);
        $item->additional_info = json_decode($item->additional_info, true);
        $address_text = explode("~", $item->address);
        $item->address_text = $address_text[count($address_text)-1]==-1?"":$address_text[count($address_text)-1];
        $item->images = json_decode($item->images);
        $category = GlobalCategory::all()->where("id", "=", $item->category)->first();
        if($category->type and $category->type != $type+1) return redirect()->back()->withErrors(trans('rent.not_available_category'));
        $category->name = json_decode($category->name_single)->$locale;
        $category->features = json_decode($category->features, true);
        $category->keywords = json_decode( $category->keywords,true);
        $category->description = json_decode($category->description);
        $item->phone_number = json_decode($item->phone_number);
        return view('item.edit', ['type'=>$type, 'pathway'=>[['title'=>$category->name, 'link'=>'/list/'.$item->category],['title'=>trans("rent.edit_item")]],'title' => trans("rent.edit_item"), "locales" => config("app.locales"), "category" => $category, 'locale' => $locale, 'item'=>$item]);
    }
    public function viewPlus(){
        if(\request()->has('id')) {
            $item = RentItem::all()->where("id", "=", \request('id'))->first();
            $item->update(['views'=>$item->views+1]);
        }
    }
    public function order(Request $request){
        foreach (json_decode($request->get("items")) as $item){
            if($item->type == "market") {
                Order::create([
                    "name"=>$request->get('name'),
                    "items"=>json_encode($item->orders, JSON_UNESCAPED_UNICODE),
                    "phone"=>"+".$request->get('phone_code').$request->get('phone'),
                    "address"=>$request->get('address'),
                    "market_slug"=>$item->slug,
                    "total_price"=>$item->total
                ]);
                $admin = Market::all(['slug', 'administrator'])->where('slug', $item->slug)->first()->administrator;
                Notice::sendNotice($admin, "new-order-market", ["name"=>$request->get('name')],"/".$item->slug."/orders");
                Notice::sendPush($admin, trans('rent.new_order'), trans('rent.from_somebody', ['name'=>$request->get('name')]), '/android-icon-144x144.png', "/".$item->slug."/orders");
            }
            else {
                Order::create([
                    "name"=>$request->get('name'),
                    "items"=>json_encode($item->orders, JSON_UNESCAPED_UNICODE),
                    "phone"=>"+".$request->get('phone_code').$request->get('phone'),
                    "address"=>$request->get('address'),
                    "user_id"=>$item->id,
                    "total_price"=>$item->total
                ]);
                Notice::sendNotice($item->id, "new-order-user", ["name"=>$request->get('name')],"/users/".$item->id);
                Notice::sendPush($item->id, trans('rent.new_order'), trans('rent.from_somebody', ['name'=>$request->get('name')]), '/android-icon-144x144.png', "/users/".$item->id."/?my_profile=true");
            }
        }
        if($request->wantsJson()) return response()->json(trans('rent.order_accepted'));
        return redirect('/#truncate')->with("success", trans('rent.order_accepted'));
    }
    public function toHistory(Request $request){
        if(Auth::check() and $request->get('id')){
            if($request->get('type') == 'market') $item = Order::all(['id', 'status'])->where('id', $request->get('id'))->first();
            elseif ($request->get('type') == 'user') $item = Order::all(['id', 'status'])->where('id', $request->get('id'))->first();
            else return abort(404);
            if(!$item) return abort(404);
            $item->update(['status'=>1]);
            return redirect()->back()->with('success', trans('rent.order_was_moved'));
        }
        else return abort(404);
    }
}