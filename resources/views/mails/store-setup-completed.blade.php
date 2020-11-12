@component('mail::message')
Hurray! Your store has been setup successfully.<br><br>
To manage your products, orders and other information about your store, click on <strong>Manage Store</strong> below.
To visit your store, preview your products and get links to share with your customer and friends, click on <strong>Visit Store</strong>

<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td style="text-align: center">
<h4 style="margin-top: 15px; margin-bottom: 15px; text-align: center">
<a  rel="noopener" target="_blank" href="{{$store->url}}">{{$store->url}}</a>
</h4>
</td>
<td style="text-align: center">
<a href="http://store.quickqart.com" class="button button-{{ $color ?? 'primary' }} ml-3" target="_blank" rel="noopener">Manage Store</a>
<a href="{{$store->url}}" class="button button-success ml-3" target="_blank" rel="noopener">Visit Store</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

Regards,<br>
{{ config('app.name') }}
@endcomponent
