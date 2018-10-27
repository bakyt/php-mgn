<?php

namespace App\Http\Controllers;

use App\Category;
use App\GlobalCategory;
use App\Location;
use App\Market;
use App\RentItem;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if(request()->decodedPath() == 'home') return redirect()->route('home');
        $locale=app()->getLocale();
        $rent_cat = [];
        $rent = [];
        $users = User::all();
        $ads = [];
        $cats = [];
        $feats = [];
        foreach (Category::all()->where('active', 1)->sortBy('order') as $cat) {
            if($cat->parent_id) !isset($rent_cat[$cat->parent_id])? $rent_cat[$cat->parent_id]=" or parent_id=".$cat->id:$rent_cat[$cat->parent_id] .= " or parent_id=".$cat->id;
            else array_push($rent, $cat);
        }
        foreach($rent as $rent_key=>$rent_value){
            $child = [];
            $shart = [];
            if($rent_value->icon == null) $rent_value->icon="default-images/no-image.jpeg";
            $name = json_decode($rent_value->name);
            $rent_value->name = $name->$locale?$name->$locale:$name->ru;
            $description = json_decode($rent_value->description);
            $rent_value->description = $description?$description->$locale?$description->$locale:$description->ru:"";
            foreach(GlobalCategory::whereRaw('parent_id = '.$rent_value->id.(isset($rent_cat[$rent_value->id])?$rent_cat[$rent_value->id]:"").' and status=2')->get() as $key=>$value) {
                if($value->image == null) $value->image = "default-images/no-image.jpeg";
                $name = json_decode($value->name_single);
                $value->name = $name->$locale?$name->$locale:$name->ru;
                $description = json_decode($value->description);
                $value->description = $description?$description->$locale?$description->$locale:$description->ru:"";
                $child[$key] = $value;
                array_push($shart, 'category='.$value->id);
                $cats[$value->id] = [$value->name, $value->state];
                $feats[$value->id] = json_decode($value->features, true);
            }
            $locations=array();
            foreach (Location::all() as $item) $locations+=[$item->id=>$item->name];
            $advers = [];
            $Markets = Market::all();
            if($shart) foreach (RentItem::whereRaw(implode(' or ', $shart))->orderByDesc('updated')->take(8)->get() as $key => $value) {
                $value->content = json_decode($value->content);
                $value->title = $value->content->title->$locale;
                $value->features = $value->content->body->$locale;
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
                if(!$value->market) $value->author = $users->where('id', $value->author)->first();
                else $value->market = $Markets->where('slug', $value->market)->first();
                $advers[$key] = $value;
            }
            if($advers) $ads[$rent_value->id] = $advers;
            $rent_value->children = $child;
            $rent[$rent_key] = $rent_value;
        }
        return view('category.list', ['cat_tits'=>$cats,'ads'=>$ads,'categories'=>$rent, 'Description'=>setting('site.description'), 'Keywords'=>setting('site.keywords'), 'title'=>setting('site.title_'.app()->getLocale())]);
    }
    public function setTimezone(){
        if(\request()->has('timezone')) session()->put('timezone', \request('timezone'));
        return response()->json(false);
    }
    public function checkTimezone(){
        if(session()->has('timezone')) return response()->json(true);
        else return response()->json(false);
    }
    public function getLocation(){
        return response()->json(Location::all()->where('parent_id', \request('id')));
    }
    public function search(Request $request){
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
            if(mb_strlen(trim($request->get('query')))<3) return redirect(route('search')."?category=".$request->get('category'))->withErrors(__('app.must_be_slightly_longer'));
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
                                     ->whereRaw("`rent_items`.`category` IN (?) AND match (content,additional_info) against (? in boolean mode)", [$cats, $query])
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
                                     ->whereRaw("match (content,additional_info) against (? in boolean mode)", [$query])
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
        return view('search', [
            'title'=>$request->get('query')?__('rent.search_results')."({$count})":__('rent.search'),
            'header'=>$request->get('query'),
            'is_searching'=>$request->has('query'),
            'items'=>$result,
            'locale'=>$locale,
            'pagination'=>$pagination,
            'pathway'=>$pathway
        ]);
    }

}
