<div class="row">
    <!-- Left col -->
    <div class="col-md-8" style="padding-bottom: 10px">
        <!-- MAP & BOX PANE -->
        <div class="box box-default no-border">
            <div class="box-body no-padding">
                <div class="bg-purple" style="position:absolute;padding: 5px;bottom:0;right:0;z-index: 1;">{{ $item->type?trans('rent.sale'):trans('rent.rent') }}</div>
                @if($item->state)<div class="bg-green" style="position:absolute;padding: 5px;bottom:30px;right:0;z-index: 1;">{{ trans('rent.market') }}</div>@endif
                <div id="gallery{{isset($i)?$i:""}}" style="display:none;">
                    @foreach($item->images as $image)
                        <img alt="" src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif"
                             data-image="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif"
                             data-description="">
                    @endforeach
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="item-view-block">
            <div class="item-params">
                <ul class="item-params-list list-group">
                    <li class="item-params-list-item list-group-item flat">
                        <span class="item-params-label">{{ trans('rent.price') }}: </span>{{ $item->price." ".trans('rent.som') }} @if(!isset($item->features['payment_time']) and isset($item->payment_time) and !$item->type) {{ $item->payment_time }} @endif
                    </li>
                    @foreach($item->features as $value)
                        <li class="item-params-list-item list-group-item flat">
                            <span class="item-params-label">{{ $value[0] }}: </span>{{ $value[1] }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @if($item->additional_info)<div class="item-description">
                <span class="item-description-text">{{ trans('rent.additional_info') }}: {{ $item->additional_info }}</span>
            </div>@endif
        </div>
    </div>
    <div class="col-md-4 " >

        @if(!Auth::guest() and $item->author->id == Auth::id())
            <div class="info-box bg-green">
                <a style="color: #ffffff" href="/{{ $item->market?$item->market:"item" }}/edit/{{ $item->id }}/{{ $item->market?"":$item->type }}">
                    <span class="info-box-icon"><i class="fa fa-edit"></i></span>
                    <div class="info-box-content">
                        <h2>{{ trans('rent.edit') }}</h2>
                    </div>
                </a>
            </div>
        @endif
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <div class="form-group pull-left">
                    <div class="input-group">
                        <div class="input-group-btn"><button class="btn btn-default minus-quantity"><i class="fa fa-chevron-left"></i> </button></div>
                        <div><input name="quantity" type="number" min="1" class="form-control" placeholder="{{ trans('rent.quantity') }}"/></div>
                        <div class="input-group-btn"><button class="btn btn-default plus-quantity"><i class="fa fa-chevron-right"></i> </button></div>
                    </div>
                    <button style="margin-top: 10px" type="button" class="btn btn-success pull-right cursor-pointer add-to-cart" data-name="{{ $item->title }}" data-id="{{ $item->id }}" data-price="{{ $item->price }}" data-category-name="{{ $category->name }}" data-category-id="{{ $item->category }}" data-market-slug="{{ $Market->slug }}" data-market-name="{{ $Market->name }}">{{ trans('rent.to_cart') }} </button>
                </div>
            </div>
        </div>
            @if($item->author->id == Auth::id())
        <div class="info-box bg-green">

                <a style="color:white" target="_blank" href="tel:{{ $item->phone_number->phone[0] }}"><span class="info-box-icon"><i class="fa fa-phone"></i></span></a>

                <div class="info-box-content">
                    <div class="info-box-number">
                        <a style="color: white" href="/users/{{ $item->author->id }}"><i class="fa fa-user"></i> {{ $item->author->name }} <span class="label label-success">{{ $item->author->isOnline() }}</span></a>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>

                    <span class="progress-description">
                    <span id="rent-auth-{{ $item->id }}" data-toggle="modal" data-target="#modal-message" onclick="$('#feedback-link').html('<a href=\'/view/{{ $item->id }}\'>{{ $item->title }}</a>');$.Nukura.newMessage('{{ $item->author->id }}', '{{ $item->author->name }}', '/storage/{{ $item->author->avatar }}', '{{ $item->id }}')" class="pull-left cursor-pointer"><i class="fa fa-envelope"></i></span>
                        @if(isset($item->phone_number->whatsapp[0]))<span id="rent-auth-{{ $item->id }}"  class="pull-right cursor-pointer"><a style="color:#ffffff;" target="_blank" href="whatsapp://send?&phone={{ explode(",", $item->phone_number->whatsapp)[0] }}&text=https://ijara.kg/view/{{ $item->id }}"><i class="fa fa-whatsapp"></i> Whatsapp <i class="fa fa-external-link"></i></a></span>@endif
                  </span>
                </div>
        <!-- /.info-box-content -->
        </div>
            @endif
        {{--<div class="info-box bg-purple text-center" style="padding-top:15px;">--}}
            {{--<span class="ymaps-geolink">{{ $item->address }} </span>--}}
            {{--<!-- /.info-box-content -->--}}
        {{--</div>--}}
        <a href="#feedback" onclick="$('#feedback-link').html('<a href=\'/view/{{ $item->id }}\'>{{ $item->title }}</a>')">
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-edit"></i></span>
                <div class="info-box-content">
                    <span class="pull-right"><i class="fa fa-external-link"></i></span>
                    <h4>{{ trans('app.problems_feedback_text') }} </h4>
                </div>
            </div>
        </a>
        <!-- /.info-box -->
    </div>
</div>