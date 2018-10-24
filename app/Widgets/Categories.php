<?php

namespace App\Widgets;

use App\Category;
use App\GlobalCategory;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Cache;

class Categories extends AbstractWidget
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
        if(\cache()->has('menu_categories')){
            $items = \cache('menu_categories');
        }
        else {
            $items = [];
            $glob = GlobalCategory::all();
            foreach (Category::all()->where('active', 1)->sortBy('order')->sortBy('parent_id') as $item) {
                if ($item->parent_id) {
                    if (!isset($items[$item->parent_id])) continue;
                    $item->name = json_decode($item->name);
                    $org = [];
                    foreach ($glob->where('parent_id', $item->id) as $pub) {
                        $pub->name = json_decode($pub->name);
                        array_push($org, $pub);
                    }
                    $item->children = $org;
                    $it = $items[$item->parent_id]->children;
                    array_push($it, $item);
                    $items[$item->parent_id]->children = $it;
                } else {
                    $item->name = json_decode($item->name);
                    $item->children = [];
                    $org = [];
                    foreach ($glob->where('parent_id', $item->id) as $pub) {
                        $pub->name = json_decode($pub->name);
                        array_push($org, $pub);
                    }
                    $item->self = $org;
                    $items += [$item->id => $item];
                }
            }
            cache()->forever('menu_categories', $items);
        }
        return view('widgets.categories', [
            'config' => $this->config,
            'items' =>array_sort($items, 'order'),
            'locale'=>app()->getLocale()
        ]);
    }
}
