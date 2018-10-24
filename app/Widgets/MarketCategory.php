<?php

namespace App\Widgets;

use App\Category;
use App\GlobalCategory;
use App\Market;
use Arrilot\Widgets\AbstractWidget;

class MarketCategory extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $categories = [];
        $market = explode('/', request()->decodedPath())[0];
        $locale = app()->getLocale();
        $cats = json_decode(Market::all('slug', 'categories')->where('slug', $market)->first()['categories']);
        if($cats) {
            if(cache()->has('global_categories')) $category = cache('global_categories');
            else {
                $category = GlobalCategory::all();
                cache()->forever('global_categories', $category);
            }
            foreach ($cats as $section){
                $temp = [];
                if(isset($section->cats)) foreach ($section->cats as $sub){
                    $cat = $category->where('id', $sub)->first();
                    if(!$cat) continue;
                    $cat->name = isset(json_decode($cat->name)->$locale)?json_decode($cat->name)->$locale:$cat->name;
                    array_push($temp, $cat);
                }
                $section->name = $section->section->$locale;
                $section->child = $temp;
                array_push($categories, $section);
            }
        }

        return view('widgets.market_category', [
            'config' => $this->config,
            'items'=>$categories,
            'market'=>$market
        ]);
    }
}
