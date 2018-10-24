@extends('layouts.market')
@section('after_styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@endsection
@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#orders" data-toggle="tab" title="{{ trans('rent.orders') }}"><i class="fa fa-cart-arrow-down"></i> {{ trans('rent.orders') }}<span class="label label-danger {{ $quantity?'':'hidden' }}">{{ $quantity }}</span></a></li>
            <li><a href="#history" data-toggle="tab" title="{{ trans('rent.history') }}"><i class="fa fa-clock-o"></i> {{ trans('rent.history') }}</a></li>
        </ul>
        <div class="tab-content">
            <div class="active tab-pane" id="orders" style="overflow: auto;">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>{{ trans('rent.date') }}</th>
                        <th>{{ trans('rent.client') }}</th>
                        <th>{{ trans('rent.products') }}</th>
                        <th>{{ trans('rent.address') }}</th>
                        <th>{{ trans('rent.total_price') }}</th>
                        <th>{{ trans('rent.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Orders as $order)
                        <tr>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                            <td>{{ $order->name }}<br/>{{ $order->phone }}</td>
                            <td>
                                @foreach($order->items as $item)
                                    <li><a href="/view/{{ $item->id }}">{{ $item->title }}</a>, {{ trans('rent.quantity').": ".$item->quantity }} ({{ $item->category }})</li>
                                @endforeach
                            </td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->total_price }} {{ trans('rent.som') }}</td>
                            <td><form action="/item/order/to_history" method="post">{{ csrf_field() }}<input type="hidden" name="type" value="market" /><input type="hidden" name="id" value="{{ $order->id }}"/><button title="{{ trans('rent.ready') }}" class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> {{ trans('rent.ready') }}</button></form></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>{{ trans('rent.date') }}</th>
                        <th>{{ trans('rent.client') }}</th>
                        <th>{{ trans('rent.products') }}</th>
                        <th>{{ trans('rent.address') }}</th>
                        <th>{{ trans('rent.total_price') }}</th>
                        <th>{{ trans('rent.action') }}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane" id="history" style="overflow: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>{{ trans('rent.date') }}</th>
                        <th>{{ trans('rent.client') }}</th>
                        <th>{{ trans('rent.products') }}</th>
                        <th>{{ trans('rent.address') }}</th>
                        <th>{{ trans('rent.total_price') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Orders_history as $order)
                        <tr>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                            <td>{{ $order->name }}<br/>{{ $order->phone }}</td>
                            <td>
                                @foreach($order->items as $item)
                                    <li><a href="/view/{{ $item->id }}">{{ $item->title }}</a>, {{ trans('rent.quantity').": ".$item->quantity }} ({{ $item->category }})</li>
                                @endforeach
                            </td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->total_price }} {{ trans('rent.som') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>{{ trans('rent.date') }}</th>
                        <th>{{ trans('rent.client') }}</th>
                        <th>{{ trans('rent.products') }}</th>
                        <th>{{ trans('rent.address') }}</th>
                        <th>{{ trans('rent.total_price') }}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('after_scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function() {
            $('#example1').DataTable({
                "language": {
                    "url": "/plugins/datatables/langs/{{ $locale }}.json"
                },
                "ordering": false
            });
            $('#example2').DataTable({
                "language": {
                    "url": "/plugins/datatables/langs/{{ $locale }}.json"
                }
                ,
                "ordering": false
            });
        });
    </script>
@endsection