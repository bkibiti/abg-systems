@extends("layouts.master")

@section('content-title')
Deleted Sales
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="{{ route('sales.history') }}">Sales / Sales History</a></li>
<li class="breadcrumb-item">Deleted Sales</li>
@endsection

@section("content")

<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Deleted Sales</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Deleted On</th>
                            <th>Deleted By</th>
                            <th>Reason</th>
                            <th>Receipt</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Original Sale Date</th>
                            <th>Recorded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deletions as $d)
                        <tr>
                            <td data-order="{{ \Carbon\Carbon::parse($d->deleted_at)->format('Y-m-d H:i:s') }}">{{ \Carbon\Carbon::parse($d->deleted_at)->format('d M Y H:i') }}</td>
                            <td>{{ $d->deleter->name ?? '—' }}</td>
                            <td>{{ $d->reason }}</td>
                            <td>{{ $d->receipt_number }}</td>
                            <td>{{ $d->product_name ?? '(deleted product)' }}</td>
                            <td>{{ number_format($d->quantity, 0) }}</td>
                            <td>{{ number_format($d->amount, 2) }}</td>
                            <td>{{ $d->sale_date ? \Carbon\Carbon::parse($d->sale_date)->format('d M Y') : '—' }}</td>
                            <td>{{ $d->saleCreator->name ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push("page_scripts")
@include('partials.notification')

<script>
    document.title = document.title.concat(" | Sales | Deleted sales");
</script>
<script>
    $('#fixed-header1').DataTable({
        bAutoWidth: true,
        order: [[0, "desc"]]
    });
</script>
@endpush
