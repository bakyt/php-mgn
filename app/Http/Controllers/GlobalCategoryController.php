<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryTemp;
use App\GlobalCategory;
use App\Notice;
use App\RentItemTranslation;
use App\User;
use Illuminate\Support\Facades\Auth;

class GlobalCategoryController extends Controller
{
    public function subList($id){
        $id=intval($id);
        $cats1 = Category::all();
        $category = $cats1->where('id', $id)->first();
        if(!$category) abort(404);
        $parent = $cats1->where('id', $category->parent_id)->first();
        $slocale = app()->getLocale();
        $categories = [];
        $cats = GlobalCategory::all(['id', 'image', 'name', 'description', 'parent_id', 'type'])->where('parent_id', $id);
        foreach ($cats1 as $item){
            if($item->parent_id != $id) continue;
            $description = json_decode($item->description);
            $item->description = $description?$description->$slocale?$description->$slocale:$description->ru:"";
            $name = json_decode($item->name);
            $item->name = $name->$slocale?$name->$slocale:$name->ru;
            array_push($categories, $item);
        }
        foreach ($cats as $item){
            $description = json_decode($item->description);
            $item->description = $description?$description->$slocale?$description->$slocale:$description->ru:"";
            $name = json_decode($item->name);
            $item->name = $name->$slocale?$name->$slocale:$name->ru;
            array_push($categories, $item);
        }
        $name = json_decode($category->name);
        $category->name = $name->$slocale?$name->$slocale:$name->ru;
        $pathway = $parent?[["title" => json_decode($parent->name)->$slocale, "link" => "/category/" . $parent->id],["title" => $category->name]]:[];
        return view('category.sublist', [
            'pathway'=>$pathway,
            'title'=>$category->name,
            "category"=>$category,
            "categories" => $categories]);
    }
    public function create()
    {
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login')."!");
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
        return view('category.create', [
            'title'=>trans("rent.new_category"),
            "locales" => config("app.locales"),
            "categories" => $categories,
            'slocale'=>app()->getLocale()
            ]);
    }

    public function store()
    {
        if (Auth::check()) {
            if (!request()->has("save")) return abort(404);
            $image = [null, null];
            $title = request("title");
            $checker=false;
            $role = User::all('id', 'role_id')->where('id', Auth::id())->first()->role_id;
            $has = GlobalCategory::where('name', 'like', '%' . $title['ru'] . '%')->first()['name'];
            $has1 = CategoryTemp::where('name', 'like', '%' . $title['ru'] . '%')->first()['name'];
            if($has) foreach (json_decode($has, true) as $key=>$value) if($title[$key] == $value and $title[$key]) $checker=true;
            if ($checker) return redirect()->back()->withInput()->withErrors(trans('rent.category_already_exists')."!");
            if ($has1) return redirect()->back()->withInput()->with("success",trans('rent.category_after_moderation')."!");
            else if (!$title['ru']) return redirect()->back()->withInput()->withErrors(trans('rent.title_is_required')."!");
            $title = json_encode($title, JSON_UNESCAPED_UNICODE);
            if (strlen($title) > 255) return redirect()->back()->withInput()->withErrors(trans('rent.title_is_too_long')."!");
            $features = [];
            if (request()->get("parent")) {
                $feats = request()->get("features");
                if ($feats) foreach ($feats as $lang => $feat) {
                    $temp = [];
                    foreach ($feat as $key => $value) {
                        $temp += [$key => ["name" => $value["name"], "options" => explode(",", $value['options']),"required" => @$feats['ru'][$key]["required"], "addon" => @$value["addon"], "multiple" => @$feats['ru'][$key]["multiple"]]];
                    }
                    $features += [$lang => $temp];
                }

            }
            if ($_FILES['image']['tmp_name']) {
                $image = GlobalCategory::uploadImage($_FILES['image'], "global-categories/" . date('FY', strtotime(date('d.m.Y'))) . "/", [300,300]);
                if ($image[0] == -1) return redirect()->back()->withInput()->withErrors($image[1]);
            }
            $category = CategoryTemp::create([
                    "original_id" => null,
                    "parent_id" => \request()->parent ? \request()->parent : null,
                    "order" => 1,
                    "name" => $title,
                    "features" => $features?json_encode($features, JSON_UNESCAPED_UNICODE):null,
                    "image" => $image[1] ? $image[1] : 'default-images/no-image.jpeg',
                    "author_id" => Auth::id(),
                    "description" => json_encode(request()->description, JSON_UNESCAPED_UNICODE)
                ]);
        } else return redirect("/login")->withErrors(trans('rent.must_login')."!");
        if($role == 2) return redirect()->route("category.create")->with("success", trans('rent.category_after_moderation'));
        else return redirect()->route("category.moderate", $category->id)->with("success", trans('rent.category_after_moderation'));
    }

    public function update($id)
    {
        $id=intval($id);
        if (Auth::check()) {
            if (!request()->has("save") and !request()->has("delete")) return abort(404);
            $role = User::all('id', 'role_id')->where('id', Auth::id())->first()->role_id;
            if ($role == 2) return abort(404);
            $edit = GlobalCategory::all()->where("id", "=", $id)->first();
            $temp = CategoryTemp::all()->where("id", "=", $id)->first();
            if (!\request()->has('moderate') and !$edit or !$temp and !$edit) return abort(404);
            cache()->forget('select_categories');
            cache()->forget('menu_categories');
            if(request()->has("delete")) {
                if(\request()->has('moderate')) {
                    if ($temp->image and file_exists("storage/" . $temp->image) and explode(",", $temp->image)[0] != 'default-images') unlink("storage/" . $temp->image);
                    if($temp) $temp->delete();
                }
                else if($edit){
                    if ($edit->image and file_exists("storage/" . $edit->image) and explode(",", $edit->image)[0] != 'default-images') unlink("storage/" . $edit->image);
                    $edit->delete();
                }
                if(RentItemTranslation::has($id)) RentItemTranslation::deletor($id);
                return redirect('/users/'.Auth::id())->with("success", trans('rent.deleting_success'));
            }
            $image = [null, null];
            $title = request("title");
            $checker = false;
            $has = GlobalCategory::where('name', 'like', '%' . $title['ru'] . '%')->first();
            if ($has['id'] != $id and $has) foreach (json_decode($has['name'], true) as $key => $value) if ($title[$key] == $value and $title[$key]) $checker = true;
            if ($checker) return redirect()->back()->withInput()->withErrors(trans('rent.category_already_exists') . "!");
            else if (!$title['ru']) return redirect()->back()->withInput()->withErrors(trans('rent.title_is_required') . "!");
            $forNotice = $title;
            $title = json_encode($title, JSON_UNESCAPED_UNICODE);
            if (strlen($title) > 255) return redirect()->back()->withInput()->withErrors(trans('rent.title_is_too_long') . "!");
            if (!\request('title_single')['ru']) return redirect()->back()->withInput()->withErrors(trans('rent.title_is_required') . "!");
            $title_single = json_encode(\request('title_single'), JSON_UNESCAPED_UNICODE);
            $features = [];
            $title_var = [];
            $title_item = \request('item_title');
            if (request()->get("parent")) {
                $feats = request()->get("features");
                if ($feats) foreach ($feats as $lang => $feat) {
                    $temp1 = [];
                    foreach ($feat as $key => $value) {
                        $temp1 += [$key => ["name" => $value["name"], "options" => explode(",", $value['options']), "required" => @$feats['ru'][$key]["required"], "addon" => @$value["addon"], "multiple" => @$feats['ru'][$key]["multiple"], "filter" => @$feats['ru'][$key]["filter"],"rent" => @$feats['ru'][$key]["rent"], "sale" => @$feats['ru'][$key]["sale"]]];
                        if(!$features and strpos($title_item['ru'], "{".$key."}") !== false) {
                            array_push($title_var,$key);
                        }
                    }
                    
                    $features += [$lang => $temp1];
                    
                }
            }
            if ($_FILES['image']['tmp_name']) {
                $image = GlobalCategory::uploadImage($_FILES['image'], "global-categories/" . date('FY', strtotime(date('d.m.Y'))) . "/", [300, 300]);
                if ($image[0] == -1) return redirect()->back()->withInput()->withErrors($image[1]);
                if ($edit and $edit->image and file_exists("storage/" . $edit->image) and explode(",", $edit->image) != 'default-images') unlink("storage/" . $edit->image);
                if ($temp and $temp->image and file_exists("storage/" . $temp->image) and explode(",", $temp->image) != 'default-images') unlink("storage/" . $temp->image);
            }
            if(\request()->has('moderate')) {
                    $category = GlobalCategory::create([
                        "parent_id" => \request()->parent ? \request()->parent : null,
                        "order" => 1,
                        "name" => $title,
                        "name_single" => $title_single,
                        "image" => $image[1] ? $image[1] : $temp->image,
                        "features" => $features ? json_encode($features, JSON_UNESCAPED_UNICODE) : null,
                        "moderator_id" => Auth::id(),
                        "status" => 2,
                        "type" => \request('type'),
                        "state" => \request()->has('state')?\request('state'):0,
                        "payment_time"=>request()->payment_time,
                        "description" => json_encode(request()->description, JSON_UNESCAPED_UNICODE)
                    ]);
                }
                else {
                    $edit->update([
                        "parent_id" => \request()->parent ? \request()->parent : null,
                        "order" => 1,
                        "name" => $title,
                        "name_single" => $title_single,
                        "image" => $image[1] ? $image[1] : $edit->image,
                        "features" => $features ? json_encode($features, JSON_UNESCAPED_UNICODE) : null,
                        "moderator_id" => Auth::id(),
                        "status" => 2,
                        "type" => \request('type'),
                        "state" => \request()->has('state')?\request('state'):0,
                        "payment_time"=>request()->payment_time,
                        "description" => json_encode(request()->description, JSON_UNESCAPED_UNICODE)
                    ]);
                    $category = $edit;
                }
            if($category?$category->author_id:$temp->author_id != Auth::id()) Notice::sendNotice($temp->author_id, "category-moderation-success", ["category"=>$forNotice[/*$authorLang*/'ru']],"/list/".$category->id);
            if($temp) $temp->delete();
            $temp = $category?RentItemTranslation::all()->where("category_id", "=", $category->id)->first():null;
            if($temp) RentItemTranslation::updater($category->id, $title_item, json_encode($title_var));
            else RentItemTranslation::creator($category->id, $title_item, json_encode($title_var));
            if (!$category) return redirect()->back()->withInput()->withErrors(trans('rent.something_wrong')."!");
            if(\request()->has('redirect')) return redirect(str_replace("*", "#", \request('redirect')))->with("success", trans('rent.moderation_succeed'));
        } else return redirect("/login")->withErrors(trans('rent.must_login')."!");
        if(\request()->wantsJson()) return response()->json($category->image);
        return redirect()->route('category.edit', $category->id)->with("success", trans('rent.updating_success')."!");
    }

    public function edit($id)
    {
        $id=intval($id);
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login')."!");
        $role = User::all('id', 'role_id')->where('id', Auth::id())->first()->role_id;
        if ($role == 2) return abort(404);
        $category = GlobalCategory::all()->where('id', '=', $id)->first();
        if (!$category) return abort(404);
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
        $category->name = json_decode($category->name);
        $category->name_single = json_decode($category->name_single);
        $category->description = json_decode($category->description);
        $category->item_title = RentItemTranslation::forCategory($category->id);
        $category->features = json_decode($category->features, true);
        return view('category.edit', ['title'=>trans("rent.edit_category"), 'category' => $category, 'categories' => $categories, "locales" => config("app.locales"), 'slocale' => app()->getLocale(), 'role'=>$role]);
    }
    public function moderate($id)
    {
        $id=intval($id);
        if (!Auth::check()) return redirect("/login?redirect=" . url()->current())->withErrors(trans('rent.must_login')."!");
        $role = User::all('id', 'role_id')->where('id', Auth::id())->first()->role_id;
        if ($role == 2) return abort(404);
        $category = CategoryTemp::all()->where('id', '=', $id)->first();
        if (!$category) return abort(404);
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
        $category->name = json_decode($category->name);
        $category->description = json_decode($category->description);
        $category->item_title = json_decode('{"ru":"{brand}", "en":"{brand}", "kg":"{brand}"}');
        $category->features = json_decode('{"ru":{"brand":{"name":"Марка","options":[""],"required":"on","addon":null,"multiple":null,"filter":"on","rent":"on","sale":"on"}},"en":{"brand":{"name":"Brand","options":[""],"required":"on","addon":null,"multiple":null,"filter":"on","rent":"on","sale":"on"}},"kg":{"brand":{"name":"Марка","options":[""],"required":"on","addon":null,"multiple":null,"filter":"on","rent":"on","sale":"on"}}}', true);
        return view('category.moderate', ['title'=>trans("rent.moderate"), 'category' => $category, 'categories' => $categories, "locales" => config("app.locales"), 'slocale' => app()->getLocale(), 'role'=>$role]);
    }
}
