@extends("layouts.master")

@section('page_css')
<style>

</style>

@endsection

@section('content-title')
Sales History
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales / Sales History </a></li>
@endsection

@section("content")


<div class="col-sm-12">
    <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sales History</h3>
            </div>
        <div class="card-body">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <form action="{{ route('sales.history-search') }}" method="GET">
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md">
                            <label for="filter_product" class="form-label history-filter-label">Product</label>
                            <select class="form-select select2" name="product" id="filter_product" data-width="100%">
                                <option value="">All Products</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" @selected(request('product') == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @include('partials.date_filter', ['resetRoute' => 'sales.history'])
                    </div>
                </form>

                <hr>
                <div id="product-table" class="table-responsive">
                    <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                       
                                <th>Date</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>Customer</th>
                                <th>Payment</th>
                                <th>Added By</th>
                                @can('Edit Sales')
                                <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $s)
                            <tr>
                          
                                <td data-order="{{ $s->created_at->format('Y-m-d H:i:s') }}">{{date_format($s->created_at,'d M Y')}}</td>
                                <td>{{ $s->product->name ?? '(deleted product #'.$s->stock_id.')' }}</td>
                                <td>{{number_format($s->quantity,0)}}</td>
                                <td>{{number_format($s->selling_price,2)}}</td>
                                <td>{{number_format(($s->selling_price * $s->quantity), 2)}}</td>
                                <td>{{ $s->customer->name ?? 'Walk-in' }}</td>
                                <td>{{ $s->paymentMethod->name ?? '—' }}</td>
                                <td>{{ $s->user->name ?? '—' }}</td>
                                @can('Edit Sales')
                                <td>
                                    <a href="#">
                                        <button class="btn btn-sm btn-info"
                                                data-id="{{$s->id}}"
                                                data-name="{{ $s->product->name ?? '(deleted product)' }}"
                                                data-created_at="{{$s->created_at}}"
                                                data-quantity="{{$s->quantity}}"
                                                data-selling_price="{{$s->selling_price}}"
                                                data-buying_price="{{$s->buying_price}}"
                                                type="button"
                                                data-bs-toggle="modal" data-bs-target="#edit">Edit
                                        </button>
                                    </a>
                                    @if ($s->remarks <> '')
                                        <button class="btn btn-sm btn-success"
                                                data-updated_by="{{ $s->updater ? 'Updated by: '.$s->updater->name.'  at: '.$s->updated_at : '' }}"
                                                data-updated_at="{{$s->updated_at}}"
                                                data-remarks="{{$s->remarks}}"
                                                type="button"
                                                data-bs-toggle="modal" data-bs-target="#view">View
                                        </button>
                                    @endif
                              
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
                        <h4>Total Sales (Tshs): {{number_format($total,2)}}</h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>
    @endsection

    @push("page_scripts")

    @include('sales.edit_sale_modal')
    @include('sales.view_sale_modal')
    @include('partials.notification')

    <script>
        var title = document.title;
        document.title = title.concat(" | Sales | History");
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
        modal.find('.modal-body #saledate').val(String(button.data('created_at')).substring(0, 10));
        modal.find('.modal-body #name').val(button.data('name'));
        modal.find('.modal-body #selling_price').val(button.data('selling_price'))
        modal.find('.modal-body #quantity').val(button.data('quantity'))
        modal.find('.modal-body #buying_price').val(button.data('buying_price'))
    });

    $('#view').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('.modal-body #remarks').text(button.data('remarks'));
        modal.find('.modal-body #by').text(button.data('updated_by'));
        modal.find('.modal-body #at').text(button.data('updated_at'));


    });




    </script>
    @endpush