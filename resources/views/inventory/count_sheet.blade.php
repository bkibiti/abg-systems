@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
Stock Count Sheet
@endsection


@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form id="expense_form" action="{{route('daily-stock-count.print')}}" target="_blank" method="GET">
                   
                    <div class="form-group row">
                        <div class="col-md-11">
                        
                        </div>
    
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-success" >Print</button>
                        </div>
                    </div>

                </form>


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
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>QOH</th>
                                <th>Physical Count</th>
                            </tr>
                            </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->name}}</td>
                                        <td>{{$product->category->name}}</td>
                                        <td>{{$product->quantity}}</td>
                                        <td></td>
                    
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
        <script>
                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
                });

        </script>
          <script>
            var title = document.title;
            document.title = title.concat(" | Inventory | Count Sheet");
        </script>


    @endpush
