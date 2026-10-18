@extends("layouts.master")

@section('page_css')
    <style>

    </style>
@endsection

@section('content-title')
    Purchase History
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase / Purchase History </a></li>
@endsection

@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Purchase History</h3>
            </div>
            <div class="card-body">

                <form action="{{ route('goods-receiving.search') }}" method="GET">
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md">
                            <label for="filter_supplier" class="form-label history-filter-label">Supplier</label>
                            <select class="form-select select2" name="supplier" id="filter_supplier" data-width="100%">
                                <option value="0">All Suppliers</option>
                                @foreach($suppliers as $supp)
                                    <option value="{{ $supp->id }}" @selected(request('supplier') == $supp->id)>{{ $supp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md">
                            <label for="filter_product" class="form-label history-filter-label">Product</label>
                            <select class="form-select select2" name="product" id="filter_product" data-width="100%">
                                <option value="">All Products</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" @selected(request('product') == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @include('partials.date_filter', ['resetRoute' => 'goods-receiving.history'])
                    </div>
                </form>
                <hr>

                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-9">
                        </div>
                        <div class="col-md-3">
                          
                        </div>
                    </div>


                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Purchase Date</th>
                                <th>Item</th>
                                <th>Purchase Qty</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>Supplier</th>
                                <th>Added By</th>
                                @can('Edit Purchase')
                                <th>Action</th>
                                @endcan
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($purchases as $p)
                                    <tr>
                                            <td data-order="{{ $p->created_at->format('Y-m-d H:i:s') }}">{{date_format($p->created_at,'d M Y')}}</td>
                                            <td>{{ $p->product->name ?? '(deleted product)' }}</td>
                                            <td>{{number_format($p->quantity,0) }}</td>
                                            <td>{{number_format($p->unit_cost,2)}}</td>
                                            <td>{{number_format(($p->unit_cost * $p->quantity), 2)}}</td>
                                            <td>{{ $p->supplier->name ?? '—' }}</td>
                                            <td>{{ $p->user->name ?? '—' }}</td>

                                            @can('Edit Purchase')
                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-info"
                                                            data-id="{{$p->id}}"
                                                            data-name="{{ $p->product->name ?? '(deleted product)' }}"
                                                            data-quantity="{{$p->quantity}}"
                                                            data-created_at="{{$p->created_at}}"
                                                            data-unit_cost="{{$p->unit_cost}}"
                                                            data-supplier_id="{{$p->supplier_id}}"
                                                            type="button"
                                                            data-bs-toggle="modal" data-bs-target="#edit">Edit
                                                    </button>
                                                </a>
                                          
                                            </td>
                                            @endcan
                                    </tr>

                                    
                                    @endforeach
                                    
                                </tbody>
                        </table>
                        
                    </div>
                 <hr>
                    <div class="row">
             
                            <div class="col-md-12">
                                <h4>Total Purchases (Tshs): {{number_format($total,2)}}</h4>
                            </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
        @endsection

@push("page_scripts")
@include('partials.notification')
@include('purchases.edit_purchase_modal')

<script>
    var title = document.title;
    document.title = title.concat(" | Purchase History");
</script>

<script>

    $('#fixed-header1').DataTable({
        bAutoWidth: true,
        order: [[0, "desc"]]
    });

    $('#edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('.modal-body #id').val(button.data('id'));
        modal.find('.modal-body #created_at').val(String(button.data('created_at')).substring(0, 10));
        modal.find('.modal-body #name').val(button.data('name'));
        modal.find('.modal-body #unit_cost').val(button.data('unit_cost'))
        modal.find('.modal-body #quantity').val(button.data('quantity'))
        modal.find('.modal-body #supplier_id').val(button.data('supplier_id'))
    });



</script>

    @endpush
