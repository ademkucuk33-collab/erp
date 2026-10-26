@extends('layouts.app')
@section('title','Sipariş Detayı')
@section('content')
<div class="eyebrow">SİPARİŞ DETAYI</div><h1 class="page-title">#{{ $order->external_order_id }}</h1><p class="sub">{{ $order->channelAccount?->name }} · {{ $order->destination_country }} · {{ $order->placed_at?->format('d.m.Y H:i') }}</p>
<div class="cards" style="grid-template-columns:repeat(3,minmax(0,1fr))"><div class="card"><div class="card-label">Durum</div><div style="margin-top:14px"><span class="badge {{ $order->status }}">{{ $order->status }}</span></div></div><div class="card"><div class="card-label">Depo</div><div class="metric" style="font-size:20px">{{ $order->warehouse?->code ?? 'Atanmadı' }}</div></div><div class="card"><div class="card-label">Sipariş tutarı</div><div class="metric" style="font-size:20px">{{ number_format($order->total_minor/100,2,',','.') }} {{ $order->currency }}</div></div></div>
<div class="panel"><div class="panel-heading">Sipariş kalemleri</div><div class="table-wrap"><table><thead><tr><th>SKU</th><th>Ürün</th><th>Miktar</th><th>Birim fiyat</th></tr></thead><tbody>@foreach($order->items as $item)<tr><td>{{ $item->external_sku }}</td><td>{{ $item->product?->name ?? 'Eşleştirilmemiş ürün' }}</td><td>{{ $item->quantity }}</td><td>{{ number_format($item->unit_price_minor/100,2,',','.') }} {{ $order->currency }}</td></tr>@endforeach</tbody></table></div></div>
@if($order->status === 'new' && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'warehouse_manager'))<form method="post" action="{{ route('orders.reserve', $order) }}">@csrf<button class="btn" type="submit">Sipariş Stoklarını Rezerve Et</button></form>@endif
<p class="note">Gönderi oluşturma ve kargo API işlemleri canlı bağlantılar sağlanana kadar kapalıdır.</p>
@endsection
