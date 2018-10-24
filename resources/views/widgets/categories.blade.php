<div style="background: #ffffff; z-index:991;">
<div class="container">
<div class="scrollmenu-content">
    <div class="scrollmenu">
        <div style="display: inline-block" id="menu">
            <div style="display: inline-block">
                <a href="/markets" class="dropdown-toggle cat-tit">{{ trans('rent.markets') }}</a>
                <div class="rent-cat" style="display: none">
                        <a href="/markets"></a>
                        <span>{{ trans('rent.markets') }}</span>
                        <span></span>
                </div>
            </div>
        @php $i=0; @endphp
        @foreach($items as $category)
            <div style="display: inline-block">
                <a href="/category/{{ $category->id }}" class="dropdown-toggle cat-tit category-on-mouse" data-toggle="dropdown" aria-expanded="false">{{ $category->name->$locale }}</a>
                <div class="rent-cat" style="display: none">
                        <a href="/category/{{ $category->id }}"></a>
                        <span>{{ $category->name->$locale }}</span>
                        <span>{{ $category->description }}</span>
                </div>
                <div class="dropdown-menu scrollmenu-drop">
                    <div class="custom-cat-title col-md-12">
                        <div class="input-group" style="padding-top: 0">
                            <span class="input-group-btn"><label for="searching-{{ $i }}" class="no-border form-control custom-cat-search"><i class="fa fa-search"></i></label></span>
                            <input id="searching-{{ $i }}" class="form-control sub-searching custom-cat-search" type="search">
                            <span class="input-group-btn"><button type="button" class="btn btn-flat custom-close"><i class="fa fa-close"></i></button></span>
                        </div>
                    </div>
                    <div id="sub-cat-box-{{ $i }}" class="media col-md-6 scrollBody" style="max-height:285px;overflow: auto;text-align: center">
                            <div class="item-view-block">
                                <div class="item-params" style="padding-top:0;">
                                    <ul style="text-align: left" class="item-params-list list-group">
                                        @foreach($category->children as $sub)
                                        <li style="border: 0;padding-top: 0" class="item-params-list-item list-group-item flat">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <td>
                                                        <a href="/category/{{ $sub->id }}"><b>{{ $sub->name->$locale }}</b></a>
                                                        <div class="rent-cat" style="display: none">
                                                            <a href="/category/{{ $sub->id }}"></a>
                                                            <span>{{ $sub->name->$locale }}</span>
                                                            <span>{{ $sub->description }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($sub->children as $pub)
                                                <tr>
                                                    <td>
                                                        <div class="sub-cat sub-cats-{{ $i }}">
                                                        <a class="sub-url" href="/list/{{ $pub->id }}"> - {{ $pub->name->$locale }}</a>
                                                        <div style="display: none" class="widget-user-header custom-category-title">
                                                            <span>{{ $pub->name->$locale }}</span>
                                                            <span>{{ $pub->description }}&nbsp</span>
                                                        </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </li>
                                        @endforeach
                                            @if($category->self)
                                                <li style="border: 0;padding-top: 0" class="item-params-list-item list-group-item flat">
                                                    <table>
                                                        <thead>
                                                        <tr>
                                                            <td>
                                                                <a href="/category/{{ $category->id }}"><b>{{ trans('rent.others') }}</b></a>
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($category->self as $pub)
                                                            <tr>
                                                                <td>
                                                                    <div class="sub-cat sub-cats-{{ $i }}">
                                                                        <a class="sub-url" href="/list/{{ $pub->id }}"> - {{ $pub->name->$locale }}</a>
                                                                        <div style="display: none" class="widget-user-header custom-category-title">
                                                                            <span>{{ $pub->name->$locale }}</span>
                                                                            <span>{{ $pub->description }}&nbsp</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </li>
                                            @endif
                                    </ul>
                                </div>
                            </div>

                        @if(!$category->children and !$category->self) <h4 style="color:white;padding-bottom:10px" class="widget-user-username">{{ trans("rent.empty_category") }}</h4>
                        @endif
                    </div>
                    <div id="sub-cat-result-box-{{ $i }}" class="scrollBody media col-md-6" style="max-height:285px;overflow: auto;display:none;text-align: left"></div>
                    <div class="col-md-6 hidden-xs">
                        <div id="carousel-example-generic" class="carousel slide" data-interval="3000" data-ride="carousel" style="margin-top:15px;">

                            <div class="carousel-inner">
                                @foreach($category->children as $sub)
                                    @if(!$sub->icon) @continue @endif
                                    <div class="item">
                                        <img src="/storage/default-images/slider.png" style="background:url('/storage/{{ $sub->image }}') center center transparent no-repeat;background-size: cover">

                                        <div class="carousel-caption" style="left:0;right:0;width:100%;font-size: 18pt; background:linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5))">
                                            {{ $sub->name->$locale }}
                                        </div>
                                    </div>
                                @endforeach
                                    <div class="item active">
                                        <img src="/storage/default-images/slider.png" style="background:url('/storage/{{ $category->image }}') center center transparent no-repeat;background-size: cover">

                                        <div class="carousel-caption" style="left:0;right:0;font-size: 18pt; background:linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5))">
                                            {{ $category->name->$locale }}
                                        </div>
                                    </div>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="fa fa-angle-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="fa fa-angle-right"></span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12" style="height:15px;"></div>
                </div>
            </div>
            @php $i++ @endphp
        @endforeach
        <div class="pull-left cursor-pointer double-arrow left"><i class="fa fa-angle-double-left"></i></div>
        <div class="pull-right cursor-pointer double-arrow right"><i class="fa fa-angle-double-right"></i></div>
    </div>
    </div>
</div>
</div>
</div>