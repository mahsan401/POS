

<table>
<tr>
<th>sku</th>
<th>Product</th>
<th>Location</th>
<th>Unit Price</th>
<th>Current stock</th>
<th>Current Stock Value purchase</th>
<th>Current Stock Value sale</th>
<th>Potential profit</th>
<th>Order Status</th>
<th>Packed Status</th>
<th>Total</th>
<th>Total unit sold</th>
<th>Total Unit Transfered</th>
<th>Total Unit Adjusted</th>
<th>Current Stock (Manufacturing)</th>
</tr>
@foreach($veriation as $var)
<tr>
<td>{{$var->product->sku}}</td>
<td>{{$var->product->name}}</td>
<td>{{$var->location->name}}</td>
<td>{{$var->variation->sell_price_inc_tax}}</td>
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

</tr>
@endforeach
</table>