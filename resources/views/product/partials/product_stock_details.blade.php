@php
use App\Product;
use App\Variation;
$business_id = session()->get('user.business_id');
@endphp
@if($business_id=='7')
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-condensed bg-gray">
				<thead>
					<tr class="bg-green">
						<th>SKU</th>
		                <th>@lang('business.product')</th>
		                <th>@lang('business.location')</th>
		                <th>@lang('sale.unit_price')</th>
		                <th>@lang('report.current_stock')</th>
		                <th>@lang('lang_v1.total_stock_price')</th>
		                <th>@lang('report.total_unit_sold')</th>
		                <th>@lang('lang_v1.total_unit_transfered')</th>
		                <th>@lang('lang_v1.total_unit_adjusted')</th>
                        <th style="text-align: center;">Ordered</th>
                        <th style="text-align: center;">Packed</th>
                        <th style="text-align: center;">Total Inventory in Warehouse</th>
		            </tr>
	            </thead>
	            <tbody>
	            	@foreach($product_stock_details as $product)
                  
	            		<tr>
	            			<td>{{$product->sku}}</td>
	            			<td>
	            				@php
                               
	            				$name = $product->product;
			                    if ($product->type == 'variable') {
			                        $name .= ' - ' . $product->product_variation . '-' . $product->variation_name;
			                    }
			                    @endphp
			                    {{$name}}
	            			</td>
	            			<td>{{$product->location_name}}</td>
	            			<td>
                        		<span class="display_currency"data-currency_symbol=true >{{$product->unit_price ?? 0}}</span>
                        	</td>
	            			<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->stock ?? 0}}</span>{{$product->unit}}
                        	</td>
                        	<td>
                        		<span class="display_currency"data-currency_symbol=true >{{$product->unit_price * $product->stock}}</span>
                        	</td>
                        	<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->total_sold ?? 0}}</span>{{$product->unit}}
                        	</td>
                        	<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->total_transfered ?? 0}}</span>{{$product->unit}}
                        	</td>
                        	<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->total_adjusted ?? 0}}</span>{{$product->unit}}
                        	</td>
                            	<td style="text-align: center;">
                                @php
                                $ordered=0;
                                $packed=0;
                                $quant=0;
                                 if ($product->type == 'variable') {
                                    $vars=Variation::where('sub_sku',$product->sku)->get();
                                   
                                   
                                   foreach($vars as $vari)
                                   {
                                    foreach($vari->sell_lines as $trop)
                                    {
                                        if($trop->transaction->shipping_status=="ordered" && $trop->transaction->location_id==$product->location_id)
{
	$ordered++;
}
elseif($trop->transaction->shipping_status=="packed" && $trop->transaction->location_id==$product->location_id)
{
	$packed++;
}
                                        
                                    }
                                    
                                   }
                                   
                                   
                                   
                                   
                                    }
                                    else{
                                $minisku1=explode("-",$product->sku);
                                $minisku=$minisku1[0];
                                
                                $prec= Product::where('sku', $minisku)->get();
                                @endphp
                                
                                
                                @foreach($prec as $pro)
                                @foreach($pro->trans_id as $trans)
                                
                                @php
if($trans->transaction->shipping_status=="ordered" && $trans->transaction->location_id==$product->location_id)
{
	$ordered++;
}
elseif($trans->transaction->shipping_status=="packed" && $trans->transaction->location_id==$product->location_id)
{
	$packed++;
}
@endphp



                                @endforeach 
                                @endforeach
                                
                                @php
                                }
                                @endphp
                               {{$ordered}}
                               @php
                               $quant=($product->stock)+$packed+$ordered;
                               @endphp
                        	</td>
                            	<td style="text-align: center;">
                        		{{$packed}}
                        	</td>
                            	<td style="text-align: center;">
                        		{{$quant}}
                        	</td>
	            		</tr>
	            	@endforeach
	            </tbody>
	     	</table>
     	</div>
    </div>
</div>
@else
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-condensed bg-gray">
				<thead>
					<tr class="bg-green">
						<th>SKU</th>
		                <th>@lang('business.product')</th>
		                <th>@lang('business.location')</th>
		                <th>@lang('sale.unit_price')</th>
		                <th>@lang('report.current_stock')</th>
		                <th>@lang('lang_v1.total_stock_price')</th>
		                <th>@lang('report.total_unit_sold')</th>
		                <th>@lang('lang_v1.total_unit_transfered')</th>
		                <th>@lang('lang_v1.total_unit_adjusted')</th>
		            </tr>
	            </thead>
	            <tbody>
	            	@foreach($product_stock_details as $product)
	            		<tr>
	            			<td>{{$product->sku}}</td>
	            			<td>
	            				@php
	            				$name = $product->product;
			                    if ($product->type == 'variable') {
			                        $name .= ' - ' . $product->product_variation . '-' . $product->variation_name;
			                    }
			                    @endphp
			                    {{$name}}
	            			</td>
	            			<td>{{$product->location_name}}</td>
	            			<td>
                        		<span class="display_currency"data-currency_symbol=true >{{$product->unit_price ?? 0}}</span>
                        	</td>
	            			<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->stock ?? 0}}</span>{{$product->unit}}
                        	</td>
                        	<td>
                        		<span class="display_currency"data-currency_symbol=true >{{$product->unit_price * $product->stock}}</span>
                        	</td>
                        	<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->total_sold ?? 0}}</span>{{$product->unit}}
                        	</td>
                        	<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->total_transfered ?? 0}}</span>{{$product->unit}}
                        	</td>
                        	<td>
                        		<span data-is_quantity="true" class="display_currency"data-currency_symbol=false >{{$product->total_adjusted ?? 0}}</span>{{$product->unit}}
                        	</td>
	            		</tr>
	            	@endforeach
	            </tbody>
	     	</table>
     	</div>
    </div>
</div>
@endif
