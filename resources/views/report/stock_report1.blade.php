
@extends('layouts.app')
@section('title', __('report.stock_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('report.stock_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
              {!! Form::open(['url' => action('ReportController@getStockReport'), 'method' => 'get', 'id' => 'stock_report_filter_form' ]) !!}
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('location_id',  __('purchase.business_location') . ':') !!}
                        {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('category_id', __('category.category') . ':') !!}
                        {!! Form::select('category', $categories, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'category_id']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                        {!! Form::select('sub_category', array(), null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'sub_category_id']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('brand', __('product.brand') . ':') !!}
                        {!! Form::select('brand', $brands, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('unit',__('product.unit') . ':') !!}
                        {!! Form::select('unit', $units, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                @if($show_manufacturing_data)
                    <div class="col-md-3">
                        <div class="form-group">
                            <br>
                            <div class="checkbox">
                                <label>
                                  {!! Form::checkbox('only_mfg', 1, false, 
                                  [ 'class' => 'input-icheck', 'id' => 'only_mfg_products']); !!} {{ __('manufacturing::lang.only_mfg_products') }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
            <table class="table no-border">
                <tr>
                    <td>@lang('report.closing_stock') (@lang('lang_v1.by_purchase_price'))</td>
                    <td>@lang('report.closing_stock') (@lang('lang_v1.by_sale_price'))</td>
                    <td>@lang('lang_v1.potential_profit')</td>
                    <td>@lang('lang_v1.profit_margin')</td>
                </tr>
                <tr>
                    <td><h3 id="closing_stock_by_pp" class="mb-0 mt-0"></h3></td>
                    <td><h3 id="closing_stock_by_sp" class="mb-0 mt-0"></h3></td>
                    <td><h3 id="potential_profit" class="mb-0 mt-0"></h3></td>
                    <td><h3 id="profit_margin" class="mb-0 mt-0"></h3></td>
                </tr>
            </table>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
            <div style="overflow: scroll;">
             <table class="table table-bordered table-striped" id="new_table" >
    <thead>
        <tr>
            <th>SKU</th>
            <th>@lang('business.product')</th>
            <th>@lang('sale.location')</th>
            <th>@lang('sale.unit_price')</th>
            <th>@lang('report.current_stock')</th>
            @can('view_product_stock_value')
            <th >@lang('lang_v1.total_stock_price') <br><small>(@lang('lang_v1.by_purchase_price'))</small></th>
            <th>@lang('lang_v1.total_stock_price') <br><small>(@lang('lang_v1.by_sale_price'))</small></th>
            <th>@lang('lang_v1.potential_profit')</th>
            @endcan
            <th>Order Status</th>
            <th>Packed Status</th>
            <th>Total</th> 
            <th>@lang('report.total_unit_sold')</th>
            <th>@lang('lang_v1.total_unit_transfered')</th>
            <th>@lang('lang_v1.total_unit_adjusted')</th>
            @if($show_manufacturing_data)
                <th >@lang('manufacturing::lang.current_stock_mfg') @show_tooltip(__('manufacturing::lang.mfg_stock_tooltip'))</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @php
    $current_stoc=0;
    $current_stocper=0;
    $current_stocsal=0;
    $profit_stoc=0;
    $order_status=0;
    $packed_status=0;
    $current_total=0;
    $total_sold=0;
    $total_tras=0;
    $total_adjust=0;
    $cs=0;
    @endphp

     @foreach($business as $bus)
    
     @foreach($bus->appveri as $var )
    
 
<tr role="row" class="odd">
<td class="sorting_1">{{$var->product->sku}}</td>
<td>{{$var->product->name}}</td>
<td>{{$var->location->name}}</td>
<td><span class="display_currency" data-currency_symbol="true">{{$var->variation->sell_price_inc_tax}}</span></td>
<td>{{$var->qty_available}}</td>
<td>{{$var->variation->default_purchase_price * $var->qty_available}}</td>
<td>{{$var->variation->sell_price_inc_tax * $var->qty_available}}</td>
<td>{{($var->variation->sell_price_inc_tax * $var->qty_available)-($var->variation->default_purchase_price * $var->qty_available)}}</td>
@php
$ordered=0;
$packed=0;
$quant=0;
@endphp


@foreach($var->variation->sell_lines as $sell)


@php
$quant+=$sell->quantity;
if($sell->transaction->shipping_status=="ordered")
{
	$ordered++;
}
elseif($sell->transaction->shipping_status=="packed")
{
	$packed++;
}
@endphp

@endforeach

<td>{{$ordered}}</td>
<td>{{$packed}}</td>
<td>{{$packed+$ordered+$var->qty_available}}</td>
<td>{{$quant}}</td>
<td>0</td>
<td>0</td>
<td>0</td>

</tr>
@php
    $current_stoc += $var->qty_available;
    $current_stocper += ($var->variation->default_purchase_price * $var->qty_available);
    $current_stocsal +=($var->variation->sell_price_inc_tax * $var->qty_available);
    $profit_stoc += ($var->variation->sell_price_inc_tax * $var->qty_available)-($var->variation->default_purchase_price * $var->qty_available);
    $order_status += $ordered;
    $packed_status += $packed;
    $current_total += $packed+$ordered+$var->qty_available;
    $total_sold += $quant;
    $total_tras += 0;
    $total_adjust += 0;
    $cs += 0;
@endphp

@endforeach
    @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-gray font-17 text-center">
            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
            <td >{{$current_stoc}}</td>
            @can('view_product_stock_value')
            <td >{{$current_stocper}}</td>
            <td >{{$current_stocsal}}</td>
            <td >{{$profit_stoc}}</td>
            @endcan
            <td >{{$order_status}}</td>
            <td >{{$packed_status}}</td>
            <td >{{$current_total}}</td>
            <td >{{$total_sold}}</td>
            <td >{{$total_tras}}</td>
            <td >{{$total_adjust}}</td>
            @if($show_manufacturing_data)
                <td >{{$cs}}</td>
            @endif
        </tr>
    </tfoot>
</table>
</div>
            @endcomponent
        </div>
    </div>

</section>
<!-- /.content -->
@endsection
@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
