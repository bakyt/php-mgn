<?php

namespace App\Widgets;

use App\Category;
use App\GlobalCategory;
use Arrilot\Widgets\AbstractWidget;

class Search extends AbstractWidget
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

        return view('widgets.search', [
            'config' => $this->config,
            'categories'=>$categories,
            'locale'=>app()->getLocale()
        ]);
    }
}
