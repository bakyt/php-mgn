<?php

namespace App\Http\Controllers;

use App\Category;
use App\GlobalCategory;
use App\Location;
use App\Market;
use App\MarketType;
use App\Order;
use App\RentDeletedItem;
use App\RentItem;
use App\RentItemTranslation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketController extends Controller
{
    public function orders($slug){
        $locale = app()->getLocale();
        $Market = Market::all()->where('slug', $slug)->first();
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        if(auth()->check() and $Market->administrator != auth()->id() and auth()->user()->role_id != 1) return abort(404);
        $Orders_history = [];
        $Orders = [];
        $rent = RentItem::all();
        $Categories = GlobalCategory::all(['id', 'name', 'name_single', 'features', 'state']);
        $i = 0;
        foreach (Order::all()->where('market_slug', $slug)->sortByDesc('id') as $order){
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
                array_push($Orders, $order);
                $i++;
            }
        }
        return view("market.orders", [
            "Market"=>$Market,
            'title'=>trans('rent.orders'),
            'locale'=>$locale,
            'Orders'=>$Orders,
            'Orders_history'=>$Orders_history,
            'quantity'=>$i
            ]);
    }
    public function editCategory($slug){
        if(!Auth::check()) return abort(404);
        $Market = Market::all()->where('slug', $slug)->first();
        if(!$Market or $Market->administrator != auth()->id() and auth()->user()->role_id != 1 and auth()->user()->role_id != 3) return abort(404);
        $categories = GlobalCategory::all();
        $cats = Category::all()->where('active', 1);
        $categoriesType = [];
        $locale = app()->getLocale();
        foreach ($cats as $key => $value) {
            $chal = $categories->where('parent_id', $value->id);
            if(!$chal->first()) continue;
            $child = [];
            $value->name = json_decode($value->name)->$locale;
            foreach ($chal as $kay=>$vale) {
                $child[$kay] = $vale;
            }
            $value->child = $child;
            $categoriesType[$key] = $value;
        }
        $result = [];
        foreach (json_decode($Market->categories) as $category){
            $temp = [];
            if(isset($category->cats)) foreach ($category->cats as $cat){
                $cat = $categories->where('id', $cat)->first();
                array_push($temp, $cat);
            }
            $category->cats = $temp;
            array_push($result, $category);
        }
        return view('market.category', ['categoriesSelect'=>$categoriesType,'categories'=>$result, 'Market'=>$Market, 'title'=>trans('rent.edit_categories'), 'locale'=>app()->getLocale()]);
    }
    public function updateCategory(Request $request, $slug){
        if(!Auth::check()) return abort(404);
        $Market = Market::all()->where('slug', $slug)->first();
        if(!$Market or $Market->administrator != auth()->id() and auth()->user()->role_id != 1 and auth()->user()->role_id != 3) return abort(404);
        $oldCats = [];
        $newCats = [];
        foreach (json_decode($Market->categories) as $sub) {
            if (isset($sub->cats)) foreach ($sub->cats as $cat) array_push($oldCats, $cat);
        }
        $Market->update([
            'categories'=>$request->get("section")?json_encode($request->get("section"), JSON_UNESCAPED_UNICODE):json_encode([])
        ]);
        foreach (json_decode($Market->categories) as $sub) {
            if (isset($sub->cats)) foreach ($sub->cats as $cat) array_push($newCats, $cat);
        }
        $deletedCats = array_diff($oldCats, $newCats);
        $itemsMarket = RentItem::all()->where('market', $Market->slug);
        foreach ($deletedCats as $sub){
            $items = $itemsMarket->where('category', $sub);
            if($items->first()) foreach ($items as $item) {
                if($item->images) foreach (json_decode($item->images) as $key=>$image){
                    if(file_exists("storage/".$image)) unlink("storage/".$image);
                }
                $item->delete();
            }
        }
        if($request->wantsJson()) return response()->json(trans('rent.updating_success'));
        else return redirect()->back()->with('success', trans('rent.updating_success'));
    }
    public function editMarket($id)
    {
        $market = Market::all()->where('id', $id)->first();
        if(!auth()->check() or !$market or $market->administrator != auth()->id() and auth()->user()->role_id !=1 and auth()->user()->role_id !=3) return abort(404);
        if (!$market) return abort(404);
        $type = [];
        $locations = array();
        $locale = app()->getLocale();
        foreach (Location::all() as $item) {
            $locations += [$item->id => $item->name];
        }
        $market->description = json_decode($market->description);
        $market->contacts = json_decode($market->contacts);
        $address = explode("~", $market->address);
        $market->address_text = $address[count($address) - 1];
        if($market->delivery) $market->delivery = implode(",", json_decode($market->delivery));
        foreach (MarketType::all() as $value) {
            $value->name = json_decode($value->name)->$locale;
            $type[$value->id] = $value->name;
        }
        return view('market.edit', ['title' => trans('rent.edit_market'), 'locale' => $locale, 'type' => $type, 'market' => $market, 'pathway' => [["title" => trans('rent.edit_market'), "icon" => "fa fa-edit"]]]);
    }
    public function updateMarket(Request $request, $id){
        if(!Auth::check()) return abort(404);
        if(!\request('slug')) return redirect()->back()->withInput()->withErrors(trans('rent.required_field', ['field'=>trans('rent.link_to_market')]));
        $edit = Market::all()->where('id', $id)->first();
        if(!$edit or $edit->administrator != auth()->id() and auth()->user()->role_id !=1 and auth()->user()->role_id !=3) return abort(404);
        if($request->has('delete')){
            if($edit->icon) if(file_exists("storage/".$edit->icon) and explode("/", $edit->icon)[1] != 'market') unlink("storage/".$edit->icon);
            if($edit->background) if(file_exists("storage/".$edit->background) and explode("/", $edit->background)[1] != 'market') unlink("storage/".$edit->background);
            $items = RentItem::all()->where('market', $edit->slug);
            if($items->first()) foreach ($items as $item) {
                if($item->images) foreach (json_decode($item->images) as $key=>$image){
                    if(file_exists("storage/".$image)) unlink("storage/".$image);
                }
                $item->delete();
            }
            User::all()->where('id', $edit->id)->first()->update(['market'=>null]);
            $edit->delete();
            return redirect()->route('home')->with('success', trans('rent.deleting_success'));
        }
        $contacts = [];
        if(!$request->get("phone_number")) return redirect()->back()->withInput()->withErrors(trans('rent.required_field', ['field'=>trans('rent.phone_number')]));
        $contacts['phone'] = explode(",", $request->get('phone_number'));
        if($request->get("whatsapp")) $contacts["whatsapp"]=$request->get("whatsapp");
        if($request->get("facebook")) $contacts["facebook"]=$request->get("facebook");
        if($request->get("instagram")) $contacts["instagram"]=$request->get("instagram");
        $image=[false, false];
        $icon=[false, false];
        if ($_FILES['background']['tmp_name']) {
            $image = GlobalCategory::uploadImage($_FILES['background'], "markets/" . date('FY', strtotime(date('d.m.Y'))) . "/", [1366,1366]);
            if ($image[0] == -1) return redirect()->back()->withInput()->withErrors($image[1]);
            if(explode("/", $edit->background)[0] !='default-images' and file_exists('storage/' . $edit->background) and !is_dir('storage/'.$edit->background)) unlink('storage/' . $edit->background);
        }
        if ($_FILES['icon']['tmp_name']) {
            $icon = GlobalCategory::uploadImage($_FILES['icon'], "markets/" . date('FY', strtotime(date('d.m.Y'))) . "-i/", [400,400], false, 200);
            if ($icon[0] == -1) return redirect()->back()->withInput()->withErrors($image[1]);
            if(explode("/", $edit->icon)[0] !='default-images' and file_exists('storage/'.$edit->icon) and !is_dir('storage/'.$edit->icon)) unlink('storage/'.$edit->icon);
        }
        $address = $request->get('address');
        array_push($address, $request->get("address_text"));
        $edit->update([
            'name'=>$request->get('name'),
            'description'=>json_encode($request->get('description'), JSON_UNESCAPED_UNICODE),
            'slug'=>$request->get('slug'),
            'icon'=>$icon[1]?$icon[1]:$edit->icon,
            'background'=>$image[1]?$image[1]:$edit->background,
            'type'=>":".implode(":", $request->get('type')).":",
            'address'=>implode("~",$address),
            'contacts'=>json_encode($contacts, JSON_UNESCAPED_UNICODE),
            'delivery'=>$request->get("delivery")?json_encode(explode(",", $request->get("delivery")), JSON_UNESCAPED_UNICODE):null,
        ]);
        User::all(['id', 'market'])->where('id', \auth()->id())->first()->update(['market'=>$request->get('slug')]);
        if($request->wantsJson()) return response()->json(trans('/'.$request->get('slug')));
        return redirect()->route('market.index', $request->get('slug'))->with(['success'=>trans('rent.updating_success')]);
    }
    public function createMarket($type){
        if(!\old("name")) return redirect()->route('market.selector');
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        $types = MarketType::all()->where('id', explode(":", $type)[0])->first();
        if(!$types) return redirect()->route('market.selector');
        $locale = app()->getLocale();
        $types->name = json_decode($types->name);
        return view('market.create', ['title'=>trans('rent.new_market'),'locale'=>$locale, 'type'=>$types, 'types'=>$type, 'pathway'=>[["title"=>trans('rent.new_market'), "icon"=>"fa fa-plus"]]]);
    }
    public function storeMarket(Request $request, $types){
        if(!Auth::check()) return abort(404);
        if(!\request('slug')) return redirect()->back()->withInput()->withErrors(trans('rent.required_field', ['field'=>trans('rent.link_to_market')]));
        if(Market::all(['id', 'slug'])->where('slug', \request('slug'))->first()) return redirect()->back()->withInput()->withErrors(trans("rent.not_free_link"));
        $typess = MarketType::all()->whereIn('id', explode(":", $types));
        $type = $typess->first();
        $contacts = [];
        if(!$request->get("phone_number")) return redirect()->back()->withInput()->withErrors(trans('rent.required_field', ['field'=>trans('rent.phone_number')]));
        $contacts['phone'] = explode(",", $request->get('phone_number'));
        if($request->get("whatsapp")) $contacts["whatsapp"]=$request->get("whatsapp");
        if($request->get("facebook")) $contacts["facebook"]=$request->get("facebook");
        if($request->get("instagram")) $contacts["instagram"]=$request->get("instagram");
        $background=[false, false];
        $icon=[false, false];
        if ($_FILES['background']['tmp_name']) {
            $background = GlobalCategory::uploadImage($_FILES['background'], "markets/" . date('FY', strtotime(date('d.m.Y'))) . "/", [1366,1366]);
            if ($background[0] == -1) return redirect()->back()->withInput()->withErrors($background[1]);
        }
        if ($_FILES['icon']['tmp_name']) {
            $icon = GlobalCategory::uploadImage($_FILES['icon'], "markets/" . date('FY', strtotime(date('d.m.Y'))) . "-i/", [400,400], false, 200);
            if ($icon[0] == -1) return redirect()->back()->withInput()->withErrors($icon[1]);
        }
        $address = $request->get('address');
        array_push($address, $request->get("address_text"));
        $categories = Category::all('id', 'parent_id', 'name');
        $gCategories = GlobalCategory::all(['id','parent_id']);
        $category = [];
        foreach ($typess as $typ)
            foreach (explode(",", $typ->categories) as $cat)
                foreach ($categories->where('parent_id', $cat) as $sec){
                    $subId = [];
                    foreach ($gCategories->where('parent_id', $sec->id) as $sub){
                        array_push($subId, $sub->id);
                    }
                    array_push($category, ["section"=>json_decode($sec->name), "cats"=>$subId]);
                }
        $market = Market::create([
            'name'=>$request->get('name'),
            'description'=>json_encode($request->get('description'), JSON_UNESCAPED_UNICODE),
            'slug'=>$request->get('slug'),
            'icon'=>$icon[1]?$icon[1]:$type->icon,
            'background'=>$background[1]?$background[1]:$type->background,
            'administrator'=>\auth()->id(),
            'type'=>":".$types.":",
            'type_products'=>($request->get('type_products')!=1)?1:2,
            'categories'=>json_encode($category, JSON_UNESCAPED_UNICODE),
            'address'=>implode("~",$address),
            'contacts'=>json_encode($contacts, JSON_UNESCAPED_UNICODE),
            'delivery'=>$request->get("delivery")?json_encode(explode(",", $request->get("delivery")), JSON_UNESCAPED_UNICODE):null,
        ]);
        User::all(['id', 'market'])->where('id', \auth()->id())->first()->update(['market'=>$request->get('slug')]);
        if(!$market) return redirect()->back()->withInput()->withErrors(trans('rent.something_wrong'));
        if($request->wantsJson()) return response()->json(trans('/'.$request->get('slug')));
        return redirect()->route('market.category', $request->get('slug'))->with(['success'=>trans('rent.adding_success')]);
    }
    public function createMarketTypeSelector(){
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        $types = [];
        $locale = app()->getLocale();
        foreach (MarketType::all() as $type){
            $type->name = json_decode($type->name)->$locale;
            $types[$type->id] = $type->name;
        }
        return view('market.createSelect', ["types"=>$types, "title"=>trans('rent.new_market')]);
    }
    public function createMarketTypeSelect(){
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        if(!\request("name") or !\request('type')) return redirect()->route('market.selector')->withErrors(trans('rent.name_is_required'));
        return redirect()->route('market.create', implode(":", \request("type")))->withInput(["name"=>\request('name'), 'type_products'=>\request('type_products')]);
    }
    public function marketList(){
        $Markets = [];
        $Type = MarketType::all();
        $locations=array();
        $cities ="";
        foreach (Location::all() as $item) {
            $locations+=[$item->id=>$item->name];
            if($item->parent_id == 1) $cities.=$item->name.", ";
        }
        if(\request()->has('find')){
            $req = "";
            $param=array();
            if(\request('address')) {
                $add="";
                foreach (\request('address') as $item) {if($item) $add.="~".$item;else break;}
                $req.="address LIKE ?";
                array_push($param, trim($add, "~").'%');
            }
            if(\request('type')){
                $req.=" and type LIKE ?";
                array_push($param, "%:".\request('type').":%");
            }
            $Marks = Market::whereRaw($req, $param)->orderByDesc('updated_at')->get()->forPage(1, 12);
        }
        else $Marks = Market::all()->forPage(1, 12);
        foreach ($Marks as $Market){
            $address = explode("~", $Market->address);
            $address_last = count($address)-1;

            $address_text = $address[$address_last];
            $address[$address_last] = "";

            $address = implode(", ", $address);
            $Market->address = trim(strtr($address, $locations).$address_text,"/");
            $Market->type = json_decode($Market->description)->{app()->getLocale()};
            array_push($Markets, $Market);
        }
        return view('market.markets', ['Type'=>$Type,'Markets'=>$Markets, 'title'=>trans('rent.markets')]);
    }
    public function index($slug){
        $Market = Market::all()->where('slug', $slug)->first();
        if(!$Market) return abort(404);
        $Market->contacts = json_decode($Market->contacts);
        $locale = app()->getLocale();
        $locations=array();
        $cities ="";
        foreach (Location::all() as $item) {
            $locations+=[$item->id=>$item->name];
            if($item->parent_id == 1) $cities.=$item->name.", ";
        }
        $address = explode("~", $Market->address);
        $address_last = count($address)-1;

        $address_text = $address[$address_last];
        $address[$address_last] = "";
        $advers =[];
        $address = implode(", ", $address);
        $Market->address = trim(strtr($address, $locations).$address_text,"/");
        $Market->delivery = json_decode($Market->delivery);
        $Market->description = json_decode($Market->description)->$locale;
        $categories = GlobalCategory::all(['id', 'features', 'name', 'name_single']);
        foreach (RentItem::all()->where('market', $Market->slug)->sortByDesc('updated')->take(12) as $key => $value) {
            $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='" . $locale . "'")->first();
            if (!$translate) $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='ru'")->first();
            $feat = json_decode($value->features, true);
            $features = "";
            $categry = $categories->where('id', $value->category)->first();
            if(!$categry) continue;
            $feats = json_decode($categry->features, true);
            if($feat) foreach ($feat as $item => $val) {
                if(isset($feats[$locale][$item])){
                    if(@$feats[$locale][$item][$val]) $features .= [@$feats[$locale][$item]['name'] => @$feats[$locale][$item][$val] . " " . @$feats[$locale][$item]['addon']];
                    else {
                        if(is_array($val) or @$feats[$locale][$item]['options'][0]){
                            $temp=[];
                            for($i=0; $i<count($val); $i++) {
                                array_push($temp, @$feats[$locale][$item]['options'][$val[$i]]);
                            }
                            $val = implode(",", $temp);
                        }
                        $feat[$item]=$val . " " . @$feats[$locale][$item]['addon'];
                        $features .= @$feats[$locale][$item]['name'].": ". $val . " " . @$feats[$locale][$item]['addon']."| ";
                    }
                }
            }
            $title = $translate['value'];
            if($translate->attributes) {
                foreach (json_decode($translate->attributes) as $string) {
                    $title  = str_replace("{" . $string . "}", isset($feat[$string])?$feat[$string]:"", $title);
                }
                $value->title=$title;
            }
            else $value->title="";
            $value->features = $features;
            if($value->price == 0) $value->price = trans('rent.private_negotiation');
            $value->phone_number=json_decode($value->phone_number);
            $info = json_decode($value->additional_info);
            $value->additional_info = $info->$locale?$info->$locale:$info->ru;
            if (!$value->images) $value->images = json_encode(["default-images/no-image.jpeg"]);
            $value->images = json_decode($value->images);
            $value->category_name = json_decode($categry->name_single)->$locale;
            $advers[$key] = $value;
        }
        $order_quantity = Order::all(['market_slug', 'status'])->where('market_slug', $slug)->where('status', 0)->count();
        return view('market.index', [
            'ads'=>$advers,
            'Market'=>$Market,
            'title'=>trans('rent.market'),
            'Image'=>$Market->icon,
            'Description'=>$Market->description,
            'order_quantity'=>$order_quantity
        ]);
    }
    public function itemList($slug, $id){
        $id = intval($id);
        $Market = Market::all()->where('slug', $slug)->first();
        if($Market) {
            $check = false;
            foreach (json_decode($Market->categories) as $sub){
                foreach ($sub->cats as $cat) if ($cat == $id) {
                    $check = true;
                    break;
                }
                if($check) break;
            }
            if(!$check) $Market = null;
        }
        if(!$Market) return abort(404);
        $locale = app()->getLocale();
        $category = GlobalCategory::all()->where("id", $id)->first();
        if(!$category) return abort(404);
        $locations=array();
        $cities ="";
        foreach (Location::all() as $item) {
            $locations+=[$item->id=>$item->name];
            if($item->parent_id == 1) $cities.=$item->name.", ";
        }
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
        $items = RentItem::all()->where('category', $id)->where('market', $Market->slug)->sortByDesc('updated');
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
            $rents = RentItem::whereRaw('market="'.$Market->slug.'" and category='.$id.$req, $param)->orderByDesc('updated')->get();
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
            else $value->price.= " ".trans('rent.som');
            $value->phone_number=json_decode($value->phone_number);
            if (!$value->images) $value->images = json_encode(["default-images/no-image.jpeg"]);
            $value->images = json_decode($value->images);
            $value->title = $title;
            $value->category_name = json_decode($category->name_single)->$locale;
            $value->additional_info = json_decode($value->additional_info);
            $value->additional_info = $value->additional_info->$locale?$value->additional_info->$locale:$value->additional_info->ru;
            $rent[$key] = $value;
        }
        return view('market.item.list', ['Market'=>$Market,'pathway' => [["title" => $category->name]], 'title_add'=>$Market->type?trans('rent.sale'):trans('rent.rent'), 'title' => $category->name, 'items' => $rent, 'id'=>false, 'locale'=>$locale, 'category'=>$category, 'pagination'=>$pagination, 'Image'=>$category->image]);
    }
    public function view($slug, $id){
        $id = intval($id);
        $locale = app()->getLocale();
        $locations=array();
        $Market = Market::all()->where('slug', $slug)->first();
        if(!$Market) return abort(404);
        foreach (Location::all() as $item) $locations+=[$item->id=>$item->name];
        $rent = RentItem::all()->where('id', $id)->where('market', $Market->slug)->first();
        if(!$rent) return abort(404);
        $category = GlobalCategory::all()->where("id", $rent->category)->first();
        $category->features = json_decode($category->features, true);
        $cat_id = $category['id'];
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
        $title = $category->name." ".$title;
        $rent->payment_time=trans('rent.'.$category->payment_time);
        $rent->phone_number=json_decode($rent->phone_number);
        $rent->features = $features;
        if($rent->price == 0) $rent->price = trans('rent.private_negotiation');
        $info = json_decode($rent->additional_info);
        $rent->additional_info = $info->$locale?$info->$locale:$info->ru;
        if (!$rent->images) $rent->images = json_encode(["default-images/no-image.jpeg"]);
        $rent->images = json_decode($rent->images);
        $title.= isset($rent->features['payment_time'])?", ".$rent->features['payment_time'][1]:"";
        $rent->author = User::all()->where('id', $rent->author)->first();
        if(!$rent->author) {$rent->delete(); return abort(404);}
        return view('market.item.view', ['Market'=>$Market, 'pathway' => [["title" => $category->name, "link" => '/'.$Market->slug."/list/" . $cat_id]], 'title_add'=>$rent->type?trans('rent.sale'):trans('rent.rent'),'title' => $title, 'item' => $rent, 'category'=>$category, 'id'=>$id, 'Image'=>$rent->images[0]]);

    }
    public function edit($slug, $id){
        $id=intval($id);
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        $locale = app()->getLocale();
        $Market = Market::all()->where('slug', $slug)->first();
        if(!$Market) return abort(404);
        $item = RentItem::all()->where("id", "=", $id)->where('market', $Market->slug)->first();
        if(!$item) abort(404);
        $role = User::all(['id', 'role_id'])->where('id', Auth::id())->first()['role_id'];
        if(Auth::id() != $item->author and $role != 3 and $role != 1) return abort(404);
        $item->features = json_decode($item->features);
        $item->additional_info = json_decode($item->additional_info, true);
        $address_text = explode("~", $item->address);
        $item->address_text = $address_text[count($address_text)-1];
        $item->images = json_decode($item->images);
        $category = GlobalCategory::all()->where("id", "=", $item->category)->first();
        $category->name = json_decode($category->name_single)->$locale;
        $category->features = json_decode($category->features, true);
        $category->keywords = json_decode( $category->keywords,true);
        $category->description = json_decode($category->description);
        $item->phone_number = json_decode($item->phone_number);
        return view('market.item.edit', [
            'Market'=>$Market,
            'type'=>$item->type,
            'pathway'=>[
                ['title'=>$category->name, 'link'=>'/'.$slug.'/list/'.$item->category],
                ['title'=>trans("rent.edit_item")]
            ],
            'title' => trans("rent.edit_item"),
            "locales" => config("app.locales"),
            "category" => $category, 'locale' => $locale,
            'item'=>$item
        ]);
    }
    public function update($slug, $id){
        $id=intval($id);
        if (Auth::check()) {
            $item = RentItem::all()->where('id', $id)->where('market', $slug)->first();
            $role = User::all(['id', 'role_id'])->where('id', Auth::id())->first()['role_id'];
            if(Auth::id() != $item->author and $role != 3 and $role != 1) return abort(404);
            if (!$item or !request()->has("save") and !request()->has("delete") and !request()->has("update")) return abort(404);
            $Market = Market::all()->where('slug', $slug)->first();
            if (\request()->has('delete')) {
                RentDeletedItem::create([
                    'category'=>$item->category,
                    'author'=>$item->author,
                    'phone_number'=>$item->phone_number,
                    'messengers'=>$item->messengers,
                    'additional_info'=>$item->additional_info,
                    'features'=>$item->features,
                    'price'=>$item->price,
                    'address'=>$item->address
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
            $features = \request('features');
            $error = false;
            $categ = GlobalCategory::all(['id', 'features', 'keywords', 'name', 'name_single'])->where('id', '=', $item->category)->first();
            $keywords = json_decode($categ->keywords, true);
            if(\request()->has("features")) foreach (json_decode($categ->features)->$locale as $key=>$value){
                if(!$value->options[0]) {
                    if(isset($keywords[$key])) {
                        if(isset($features[$key]) and !in_array(@$features[$key], $keywords[$key])) array_push($keywords[$key], $features[$key]);
                    }
                    else $keywords[$key][] = $features[$key];
                    $categ->update([
                        'keywords'=>json_encode($keywords)
                    ]);
                }
                if($value->required and $value->sale and $item->type or $value->required and $value->rent and !$item->type) {
                    if(array_has($features, $key)) {if($features[$key]=="") $error=true;}
                    else $error=true;
                }
                if($error) return redirect()->back()->withInput()->withErrors(trans('rent.required_field', ['field'=>$value->name]));
            }
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
            $locations=array();
            $cities ="";
            foreach (Location::all() as $value) {
                $locations+=[$value->id=>$value->name];
                if($value->parent_id == 1) $cities.=$value->name.", ";
            }
            $address = explode("~", $Market->address);
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
                'keywords'=>setting('keywords.sale_keywords'),
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
            $item->update([
                'images'=>$images?json_encode($images):null,
                'additional_info'=>json_encode(\request("additional_info")),
                'features'=>json_encode(\request()->get("features")),
                'content'=>json_encode($content, JSON_UNESCAPED_UNICODE),
                'price'=>\request("price"),
                'updated'=>Carbon::now(),
                "state" => "2"
            ]);
            if (!$item) return redirect()->back()->withInput()->withErrors(trans('rent.something_wrong')."!");
        } else return redirect("/login")->withErrors(trans('rent.must_login')."!");
        if(\request()->wantsJson()) return response()->json('/'.$slug.'/view/'.$item->id);
        return redirect()->route("market.item.view", ['slug'=>$slug, 'id'=>$item->id])->with("success", trans('rent.updating_success')."!");
    }
    public function createCategorySelector($slug){
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        $Market = Market::all()->where('slug', $slug)->first();
        if(!$Market) return abort(404);
        $categories = [];
        $locale = app()->getLocale();
        $cats = json_decode($Market->categories);
        if($cats) {
            $category = GlobalCategory::all();
            foreach ($cats as $section){
                $temp = [];
                if(isset($section->cats)) foreach ($section->cats as $sub){
                    $cat = $category->where('id', $sub)->first();
                    $cat->name = isset(json_decode($cat->name)->$locale)?json_decode($cat->name)->$locale:$cat->name;
                    array_push($temp, $cat);
                }
                $section->name = $section->section->$locale;
                $section->child = $temp;
                array_push($categories, $section);
            }
        }

        return view('market.item.createCategorySelector', ['Market'=>$Market,'title' => trans("rent.new_product"), "categories" => $categories]);

    }
    public function createCategorySelect($slug){
        if(\request()->has("category")) return redirect()->route("market.item.create", [$slug, \request("category")]);
        return abort(404);
    }
    public function create($slug, $id){
        $catId = intval($id);
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login') . "!");
        $Market = Market::all()->where('slug', $slug)->first();
        if(!$Market) return abort(404);
        $locale = app()->getLocale();
        $category = GlobalCategory::all()->where("id", "=", $catId)->first();
        if(setting('site.rent_limit') == RentItem::all()->where('author', Auth::id())->count()) return redirect()->back()->withErrors(trans('rent.item_limit'));
        $category->name = json_decode($category->name_single)->$locale;
        $category->features = json_decode($category->features, true);
        $category->keywords = json_decode( $category->keywords,true);
        $category->description = json_decode($category->description);
        return view('market.item.create', [
            'Market'=>$Market,
            'pathway'=>[
                ['title'=>$category->name, 'link'=>'/'.$slug.'/list/'.$catId],
                ['title'=>trans("rent.new_product")]],
            'title' => trans("rent.new_product"),
            "locales" => config("app.locales"),
            "category" => $category, 'locale' => $locale
        ]);

    }
    public function store($slug, $catId){
        $catId = intval($catId);
        if (Auth::check()) {
            if (!request()->has("save")) return abort(404);
            $Market = Market::all()->where('slug', $slug)->first();
            $locale = app()->getLocale();
            $features = \request('features');
            $error = false;
            $categ = GlobalCategory::all(['id', 'features', 'keywords', 'name', 'name_single'])->where('id', '=', $catId)->first();
            $keywords = json_decode($categ->keywords, true);
            if(\request()->has('features')) foreach (json_decode($categ->features)->$locale as $key=>$value){
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
            $images = array();
            if($_FILES['images']['size'][0]){
                $image = GlobalCategory::uploadImage($_FILES['images'], "rent-items/" . date('FY', strtotime(date('d.m.Y'))) . "/", [768,768]);
                if ($image[0] == -1) return redirect()->back()->withInput()->withErrors($image[1]);
                $images = $image[1];
            }
            $locations=array();
            $cities ="";
            foreach (Location::all() as $value) {
                $locations+=[$value->id=>$value->name];
                if($value->parent_id == 1) $cities.=$value->name.", ";
            }
            $address = explode("~", $Market->address);
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
                'keywords'=>setting('keywords.sale_keywords'),
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
            $item = RentItem::create([
                'images'=>$images?json_encode($images):null,
                'category'=>$catId,
                'priority'=>1,
                'author'=> Auth::id(),
                'updated'=>Carbon::now(),
                'phone_number'=>$Market->contacts,
                'messengers'=>\request("messengers"),
                'additional_info'=>json_encode(\request("additional_info")),
                'features'=>json_encode(\request("features")),
                'content'=>json_encode($content, JSON_UNESCAPED_UNICODE),
                'price'=>\request("price"),
                'address'=>$Market->address,
                'type'=>1,
                'state'=>$Market->type_products,
                'market'=>$slug
            ]);
            if (!$item) return redirect()->back()->withInput()->withErrors(trans('rent.something_wrong')."!");
        } else return redirect("/login")->withErrors(trans('rent.must_login')."!");
        if(\request()->wantsJson()) return response()->json('/'.$slug.'/view/'.$item->id);
        return redirect()->route("market.item.view", ['slug'=>$slug, 'id'=>$item->id])->with("success", trans('rent.adding_success')."!");

    }
    public function checkMarket(){
        $routes = [
            "users"=>true,
            "password"=>true,
            "category"=>true,
            "item"=>true,
            "view"=>true,
            "list"=>true,
            "message"=>true,
            "guest"=>true,
            "phone"=>true,
            "notice"=>true,
            "moderation"=>true,
            "settimezone"=>true,
            "location"=>true,
            "visit"=>true,
            "avatar"=>true,
            "page"=>true,
            "markets"=>true,
            "admin"=>true,
            "auth"=>true,
            "api"=>true,
            "register"=>true,
            "megazon"=>true,
            "search"=>true
        ];
        $check = array_has($routes, \request('slug'));
        if(!$check) $check = Market::all('id', 'slug')->where('slug', \request('slug'))->first()?true:false;
        return response()->json($check);
    }
    public function getProducts(Request $request, $slug){
        $advers = [];
        $locale = app()->getLocale();
        $categories = GlobalCategory::all(['id', 'features', 'name', 'name_single']);
        foreach (RentItem::all()->where('market', $slug)->sortByDesc('updated')->forPage($request->get('page'), 12) as $key => $value) {
            $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='" . $locale . "'")->first();
            if (!$translate) $translate = RentItemTranslation::whereRaw("category_id='" . $value->category . "' and locale='ru'")->first();
            $feat = json_decode($value->features, true);
            $features = "";
            $categry = $categories->where('id', $value->category)->first();
            if(!$categry) continue;
            $feats = json_decode($categry->features, true);
            if($feat) foreach ($feat as $item => $val) {
                if(isset($feats[$locale][$item])){
                    if(@$feats[$locale][$item][$val]) $features .= [@$feats[$locale][$item]['name'] => @$feats[$locale][$item][$val] . " " . @$feats[$locale][$item]['addon']];
                    else {
                        if(is_array($val) or @$feats[$locale][$item]['options'][0]){
                            $temp=[];
                            for($i=0; $i<count($val); $i++) {
                                array_push($temp, @$feats[$locale][$item]['options'][$val[$i]]);
                            }
                            $val = implode(",", $temp);
                        }
                        $feat[$item]=$val . " " . @$feats[$locale][$item]['addon'];
                        $features .= @$feats[$locale][$item]['name'].": ". $val . " " . @$feats[$locale][$item]['addon']."| ";
                    }
                }
            }
            $title = $translate['value'];
            if($translate->attributes) {
                foreach (json_decode($translate->attributes) as $string) {
                    $title  = str_replace("{" . $string . "}", isset($feat[$string])?$feat[$string]:"", $title);
                }
                $value->title=$title;
            }
            else $value->title="";
            $value->features = $features;
            if($value->price == 0) $value->price = trans('rent.private_negotiation');
            $value->phone_number=json_decode($value->phone_number);
            $info = json_decode($value->additional_info);
            $value->additional_info = $info->$locale?$info->$locale:$info->ru;
            if (!$value->images) $value->images = json_encode(["default-images/no-image.jpeg"]);
            $value->images = json_decode($value->images);
            $value->category_name = json_decode($categry->name_single)->$locale;
            array_push($advers, $value);
        }
        return response()->json($advers);
    }
    public function getMarkets(Request $request){
        $Markets = [];
        $locations=array();
        $cities ="";
        foreach (Location::all() as $item) {
            $locations+=[$item->id=>$item->name];
            if($item->parent_id == 1) $cities.=$item->name.", ";
        }
        if(\request()->get('find')){
            $req = "";
            $param=array();
            if(\request('address')) {
                $add="";
                foreach (\request('address') as $item) {if($item) $add.="~".$item;else break;}
                $req.="address LIKE ?";
                array_push($param, trim($add, "~").'%');
            }
            if(\request('type')){
                $req.=" and type LIKE ?";
                array_push($param, "%:".\request('type').":%");
            }
            $Marks = Market::whereRaw($req, $param)->orderByDesc('updated_at')->get()->forPage($request->get('page'), 12);
        }
        else $Marks = Market::all()->forPage($request->get('page'), 12);
        foreach ($Marks as $Market){
            $address = explode("~", $Market->address);
            $address_last = count($address)-1;

            $address_text = $address[$address_last];
            $address[$address_last] = "";

            $address = implode(", ", $address);
            $Market->address = trim(strtr($address, $locations).$address_text,"/");
            $Market->type = json_decode($Market->description)->{app()->getLocale()};
            array_push($Markets, $Market);
        }
        return response()->json($Markets);
    }
    public function search(Request $request, $slug){
        $Market = Market::all()->where('slug', $slug)->first();
        $result = [];
        $pathway = null;
        $pagination = [];
        $count = 0;
        $quant = 10;
        $currentPageOrig = \request('page')?\request('page'):1;
        $currentPage = $currentPageOrig;
        $currentLink = $_SERVER['REQUEST_URI'];
        $parent_category=null;
        if($request->has('query')){
            if(mb_strlen(trim($request->get('query')))<3) return redirect(route('search.market', $slug)."?category=".$request->get('category'))->withErrors(__('app.must_be_slightly_longer'));
            $session_search_results = session()->get('search_results');
            if($session_search_results and $session_search_results['query'] == $request->get('query') and $request->get('category') == @$session_search_results['category']->id and $request->get('order_by')."_".$request->get('order_type') == @$session_search_results['order_by'] and @$session_search_results['type']==$request->get('type') and cache()->has($session_search_results['id'])){
                $count = $session_search_results['quantity'];
                if($currentPageOrig>ceil($count/$quant)) $currentPage=1;
                $result = array_slice(cache($session_search_results['id']), ($currentPage-1)*$quant, $quant);
                $parent_category = $session_search_results['category'];
            }
            else {
                $query = trim(trim(preg_replace("/[+\*.\<.\>.\(.\).\"]/", " ", $request->get('query')), '-')).($request->has('query_1')?" ".$request->get('query_1'):"");
                foreach (explode(" ", $query) as $word)
                    if (preg_match("/[-]/", $word))
                        $query = str_replace($word,'"'.$word.'"', $query);
                if($query and substr($query, -1, 1)!='"') $query = $query."* ";
                if($query){
                    $parent_category = Category::whereRaw('`id`=? and `active`=1', $request->get('category'))->get()->first();
                    if($request->get('category') and $parent_category) {
                        $parent_category->name = json_decode($parent_category->name);
                        $cats = "";
                        foreach (GlobalCategory::whereRaw("`parent_id` = ?", [$request->get('category')])->selectRaw("`id`")->get() as $cat) $cats .= $cat->id.',';
                        $items = RentItem::selectRaw("rent_items.*, MATCH(content,additional_info) AGAINST(?) as `score`,markets.name as market_name, markets.delivery as market_delivery,users.name as author_name, users.delivery as author_delivery", [$query])
                            ->whereRaw("`rent_items`.`market`=? AND `rent_items`.`category` IN (?) AND match (content,additional_info) against (? in boolean mode)", [$slug, $cats, $query])
                            ->leftJoin("markets", "markets.slug", "=", "rent_items.market")
                            ->leftJoin("users", "users.id", "=", "rent_items.author");
                        $items = $items->orderByDesc('score');
                        if($request->has('order_by') and $request->has('order_type')){
                            if($request->get('order_by') == 'price') {
                                if($request->get('order_type')=='descending') $items = $items->orderByDesc('price');
                                elseif ($request->get('order_type')=='ascending') $items = $items->orderBy('price');
                            }
                            elseif ($request->get('order_by') == 'date'){
                                if($request->get('order_type')=='descending') $items = $items->orderByDesc('updated_at');
                                elseif ($request->get('order_type')=='ascending') $items = $items->orderBy('updated_at');
                            }
                        }
                        $items = $items->get();
                        if($request->get('type')){
                            if($request->get('type')=="rent") $items = $items->where('type', 0);
                            elseif($request->get('type')=="sale") $items = $items->where('type', 1);
                            elseif($request->get('type')=="sale_new") $items = $items->where('type', 1)->where('state', 2);
                            elseif($request->get('type')=="sale_secondhand") $items = $items->where('type', 1)->where('state',1);
                        }
                        foreach ($items as $item) {

                            $item->content = json_decode($item->content);
                            if (!$item->images) $item->images = array('default-images/no-image.jpeg');
                            else $item->images = json_decode($item->images);
                            $item->additional_info = json_decode($item->additional_info);
                            array_push($result, $item);
                            $count++;
                        }
                    }
                    else {
                        $items = RentItem::selectRaw("rent_items.*, MATCH(content,additional_info) AGAINST(?) as `score`,markets.name as market_name, markets.delivery as market_delivery,users.name as author_name, users.delivery as author_delivery", [$query])
                            ->whereRaw("`rent_items`.`market`=? AND match (content,additional_info) against (? in boolean mode)", [$slug, $query])
                            ->leftJoin("markets", "markets.slug", "=", "rent_items.market")
                            ->leftJoin("users", "users.id", "=", "rent_items.author");
                        $items = $items->orderByDesc('score');
                        if($request->has('order_by') and $request->has('order_type')){
                            if($request->get('order_by') == 'price') {
                                if($request->get('order_type')=='descending') $items = $items->orderByDesc('price');
                                elseif ($request->get('order_type')=='ascending') $items = $items->orderBy('price');
                            }
                            elseif ($request->get('order_by') == 'date'){
                                if($request->get('order_type')=='descending') $items = $items->orderByDesc('updated_at');
                                elseif ($request->get('order_type')=='ascending') $items = $items->orderBy('updated_at');
                            }
                        }
                        $items = $items->get();
                        if($request->get('type')){
                            if($request->get('type')=="rent") $items = $items->where('type', 0);
                            elseif($request->get('type')=="sale") $items = $items->where('type', 1);
                            elseif($request->get('type')=="sale_new") $items = $items->where('type', 1)->where('state', 2);
                            elseif($request->get('type')=="sale_secondhand") $items = $items->where('type', 1)->where('state',1);
                        }
                        foreach ($items as $item){

                            $item->content = json_decode($item->content);
                            if(!$item->images) $item->images = array('default-images/no-image.jpeg');
                            else $item->images = json_decode($item->images);
                            $item->additional_info = json_decode($item->additional_info);
                            array_push($result, $item);
                            $count++;
                        }
                    }
                    if ($count>$quant) {
                        $id = 'search_'.str_replace(' ','_', microtime());
                        session()->put('search_results', ['id'=>$id, 'query'=>$request->get('query'), 'quantity'=>$count, 'category'=>$parent_category, 'order_by'=>$request->get('order_by')."_".$request->get('order_type'), 'type'=>$request->get('type')]);
                        cache()->put($id, $result, 10);
                    }
                    $result = array_slice($result, ($currentPage-1)*$quant, $quant);
                    if($currentPageOrig>ceil($count/$quant)) $currentPage=1;

                }
            }
            $origSize = $count;
            $fl = intval($origSize/$quant);
            $size = $fl<$origSize/$quant?$fl+1:$fl;
            if(\request()->has('page')) {
                if(strpos($currentLink, '?')) $currentLink = str_replace("page=".$currentPageOrig, "page=", $currentLink);
            }
            else {
                if(strpos($currentLink, '?')) $currentLink = $currentLink."&page=";
                else $currentLink = $currentLink."?page=";
            }
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
        }
        $locale = app()->getLocale();
        if($parent_category) $pathway = [
            [
                'title'=>$parent_category->name->$locale,
                'link'=>route('category.sub', $parent_category->id),
                'icon'=>'fa fa-folder'
            ],
            [
                'title'=>__('rent.search')
            ]
        ];
        return view('market.search', [
            'title'=>$request->get('query')?__('rent.search_results')."({$count})":__('rent.search'),
            'header'=>$request->get('query'),
            'is_searching'=>$request->has('query'),
            'items'=>$result,
            'locale'=>$locale,
            'pagination'=>$pagination,
            'pathway'=>$pathway,
            'Market'=>$Market
        ]);
    }
}
