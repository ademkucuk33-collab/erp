@extends('layouts.app')
@section('title','Genel Bakış')
@section('content')
<div class="eyebrow">İŞLETME PERFORMANSI</div><h1 class="page-title">Genel Bakış</h1><p class="sub">Depolardaki ve siparişlerdeki güncel operasyon verileri. Henüz satış kanalı API bağlantısı yapılmadı.</p>
<div class="cards">
 <div class="card"><div class="card-label">Toplam sipariş</div><div class="metric">{{ number_format($orderCount,0,',','.') }}</div><div class="metric-foot">Kayıtlı siparişler</div></div>
 <div class="card"><div class="card-label">Yeni sipariş</div><div class="metric">{{ number_format($newOrders,0,',','.') }}</div><div class="metric-foot">Stok ayrılması bekleniyor</div></div>
 <div class="card"><div class="card-label">Stok ayrılan</div><div class="metric">{{ number_format($reservedOrders,0,',','.') }}</div><div class="metric-foot">Hazırlanabilir sipariş</div></div>
 <div class="card"><div class="card-label">Kritik stok kayıtları</div><div class="metric">{{ number_format($lowStock,0,',','.') }}</div><div class="metric-foot">Mevcut stok ≤ 5 adet</div></div>
</div>
<div class="panels">
 <div class="panel"><div class="panel-heading">Sistem envanteri</div><div class="panel-body"><div class="row-flex"><span class="muted">Katalog ürünleri</span><strong>{{ $productCount }}</strong></div><div class="divider"></div><div class="row-flex"><span class="muted">Görüntülenebilir depo</span><strong>{{ $warehouseCount }}</strong></div><div class="divider"></div><div class="row-flex"><span class="muted">Canlı pazaryeri entegrasyonu</span><span class="badge new">Henüz bağlanmadı</span></div></div></div>
 <div class="panel"><div class="panel-heading">Operasyon durumu</div><div class="panel-body"><p style="line-height:1.8;color:#66778a;margin:0">Bu yönetim paneli gerçek veritabanı kayıtlarını gösterir. Shopify, Amazon, ShipStation ve diğer sağlayıcılar bağlanana kadar otomatik sipariş aktarımı veya etiket oluşturma devre dışıdır.</p></div></div>
</div>
<div class="panel"><div class="panel-heading">Son siparişler <a class="btn small secondary" href="{{ route('orders.index') }}">Tüm siparişler</a></div><div class="table-wrap"><table><thead><tr><th>Sipariş</th><th>Kanal</th><th>Ülke</th><th>Depo</th><th>Durum</th></tr></thead><tbody>@forelse($recentOrders as $order)<tr><td><a href="{{ route('orders.show',$order) }}"><strong>#{{ $order->external_order_id }}</strong></a></td><td>{{ $order->channelAccount?->name ?? '—' }}</td><td>{{ $order->destination_country }}</td><td>{{ $order->warehouse?->code ?? '—' }}</td><td><span class="badge {{ $order->status }}">{{ $order->status }}</span></td></tr>@empty<tr><td colspan="5" class="empty">Henüz sipariş bulunmuyor. İlk API entegrasyonuyla otomatik gelecektir.</td></tr>@endforelse</tbody></table></div></div>
@endsection
