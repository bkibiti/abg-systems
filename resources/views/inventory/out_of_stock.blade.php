@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Current Stock
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Inventory / Items out of Stock</a></li>
@endsection

@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form id="expense_form" action="{{route('current-stock-filter')}}" method="GET">
                    @csrf()

                    <div class="form-group row">

                        <div class="col-md-2">
                            <select class="form-control select2"  class="form-control" name="status"  data-placeholder="Select Status" required data-width="100%">
                                <option value="0" {{ (old('status')==0 ? "selected":"") }} >All Items</option>
                                <option value="1" {{ (old('status')==1 ? "selected":"") }} >Out of Stock</option>
                                <option value="2" {{ (old('status')==2 ? "selected":"") }} >Below Minimum Level</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control select2"  class="form-control" id="category_id" name="category_id"  data-width="100%">
                                <option value="1" {{ (old('category_id')==0 ? "selected":"") }} >All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($category->id == old('category_id') ? "selected":"") }} name="category">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                      
                       
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success">Filter</button>
                        </div>
                    </div>

                </form>

                <hr>

                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                 


                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Minimum Qty</th>
                                <th>QOH</th>
                                {{-- <th>Purchase Price</th> --}}
                                <th>Selling Price</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($Products as $prod)
                                    <tr>
                                            <td>{{$prod->name}}</td>
                                            <td>{{$prod->category->name}}</td>
                                            <td>{{$prod->min_quantinty}}</td>
                                            <td>{{$prod->quantity}}</td>
                                            {{-- <td>{{number_format($prod->unit_cost,2) }}</td> --}}
                                            <td>{{number_format($prod->sale_price_1,2) }}</td>

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
                var title = document.title;
                document.title = title.concat(" | Inventory | Out of Stock");
            </script>
            <script>

                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
                });

            </script>

    @endpush
