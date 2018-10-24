<div style="background: #ffffff">
    <div class="container">
        <div class="scrollmenu-content">
            <div class="scrollmenu">
                <div style="display: inline-block" id="menu">
                @php $i=0; @endphp
                @foreach($items as $category)
                    <div style="display: inline-block">
                        <a href="#" class="dropdown-toggle cat-tit category-on-mouse" data-toggle="dropdown" aria-expanded="false">{{ $category->name }}</a>
                        <div class="dropdown-menu scrollmenu-drop">
                            <div class="custom-cat-title col-md-12">
                                <div class="input-group" style="padding-top: 0">
                                    <span class="input-group-btn"><label for="searching-{{ $i }}" class="no-border form-control custom-cat-search"><i class="fa fa-search"></i></label></span>
                                    <input id="searching-{{ $i }}" class="form-control sub-searching custom-cat-search" type="search">
                                    <span class="input-group-btn"><button type="button" class="btn btn-flat custom-close"><i class="fa fa-close"></i></button></span>
                                </div>
                            </div>
                            <div id="sub-cat-box-{{ $i }}" class="media col-md-12 scrollBody" style="max-height:285px;overflow: auto;text-align: center">
                                        @foreach($category->child as $sub)
                                                <div class="sub-cat sub-cats-{{ $i }} col-md-3">
                                                    <div onclick="window.location.href='/{{ $market }}/list/{{ $sub->id }}'" class="panel panel-default" style="padding-top:10px;height:70px;background:linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)), url('/storage/{{ $sub->image }}') center center / cover">
                                                    <a style="color:#ffffff;font-size:16px;text-shadow: 0 0 3px #000;" class="sub-url" href="/{{ $market }}/list/{{ $sub->id }}"> {{ $sub->name }}</a>
                                                    <div style="display: none" class="widget-user-header custom-category-title">
                                                        <span>{{ $sub->name }}</span>
                                                        <span>{{ $sub->description }}&nbsp</span>
                                                    </div>
                                                    </div>
                                                </div>
                                        @endforeach
                            </div>
                            <div id="sub-cat-result-box-{{ $i }}" class="scrollBody media col-md-12" style="max-height:285px;overflow: auto;display:none;text-align: center"></div>
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