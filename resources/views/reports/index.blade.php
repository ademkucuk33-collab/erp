@extends('layouts.app')
@section('title','Satış Raporları')
@section('content')
<div class="eyebrow">İŞ ZEKÂSI</div><h1 class="page-title">Satış Raporları</h1><p class="sub">Kayıtlı siparişlerden hesaplanan satış tutarları. GBP ile EUR tutarları kur dönüşümü yapılmadan birleştirilmez.</p>
<div class="panel"><div class="panel-heading">Dönem seçimi <form method="get"><select name="period" onchange="this.form.submit()" style="padding:9px;border:1px solid #dce3ea;border-radius:8px">@foreach(['week'=>'Bu hafta','month'=>'Bu ay','year'=>'Bu yıl'] as $key => $label)<option value="{{ $key }}" @selected($period === $key)>{{ $label }}</option>@endforeach</select></form></div></div>
<div class="panels">
<div class="panel"><div class="panel-heading">Ülkeye göre satışlar</div><div class="table-wrap"><table><thead><tr><th>Ülke</th><th>Sipariş</th><th>Ciro</th></tr></thead><tbody>@forelse($countries as $r)<tr><td>{{ $r->destination_country }}</td><td>{{ $r->order_count }}</td><td><strong>{{ number_format($r->revenue_minor/100,2,',','.') }} {{ $r->currency }}</strong></td></tr>@empty<tr><td colspan="3" class="empty">Bu dönemde veri yok.</td></tr>@endforelse</tbody></table></div></div>
<div class="panel"><div class="panel-heading">Satış kanalına göre</div><div class="table-wrap"><table><thead><tr><th>Kanal</th><th>Sipariş</th><th>Ciro</th></tr></thead><tbody>@forelse($channels as $r)<tr><td>{{ strtoupper($r->platform) }}</td><td>{{ $r->order_count }}</td><td><strong>{{ number_format($r->revenue_minor/100,2,',','.') }} {{ $r->currency }}</strong></td></tr>@empty<tr><td colspan="3" class="empty">Bu dönemde veri yok.</td></tr>@endforelse</tbody></table></div></div>
</div>
<p class="note">Bu raporlar ilk sürümde brüt sipariş tutarlarını toplar ve iadeleri, pazaryeri komisyonlarını ve vergi düzeltmelerini henüz hesaba katmaz; finansal net kâr raporu değildir.</p>
@endsection
