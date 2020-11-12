@component('mail::message')
{{$store->user->name}}<br>
{{$order->contact['lastName']}} has just placed an order for {{$order->item_count}} item(s) totalling {{$currency}} {{$order->amount_formatted}}


<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td style="text-align: center">
<a href="{{"http://store.quickqart.com/orders/{$order->slug}"}}" class="button ml-3 button-{{ $color ?? 'primary' }}" style="margin-top: 5px" target="_blank" rel="noopener">View Order Details</a>
<a href="{{"http://{$store->slug}.quickqart.com/track-order/{$order->slug}"}}" class="button button-success ml-3" style="margin-top: 5px" target="_blank" rel="noopener">View Order in Store</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
