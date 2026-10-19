<form action="{{route('reports.getreport')}}" method="get" >
    @csrf()

    <div class="row mb-3">
        <div class="col-md-4">
            <div style="border: 2px solid white; border-radius: 6px;">
                <select id="report_type" name="report" class="form-select" required>
                    <option value="">Select Report</option>
                    <option value="1" {{ (old('report')==1 ? "selected":"") }}>Total Daily Sales</option>
                    <option value="2" {{ (old('report')==2 ? "selected":"") }}>Total Monthly Sales</option>
         
                </select>
            </div>
        </div>
        <div class="col-md-2" id="product_list" style="display: none">
            <select class="form-select select2"  class="form-select" name="product" data-width="100%" required>
                    <option value="1" }}>Select Product</option>

                    @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ (old('product')==$p->id ? "selected":"") }}>{{ $p->name }}</option>
                    @endforeach
            </select>
        </div>
        <div class="col-md-2" id = "fromDateDiv">
            <div style="border: 2px solid white; border-radius: 6px;" >
                <input type="text" name="from_date" class="form-control" id="from_date" value="{{ old('from_date') }}" >
            </div> 
        </div>
        <div class="col-md-2" id = "toDateDiv" >
            <div style="border: 2px solid white; border-radius: 6px;" >
                <input type="text" name="to_date" class="form-control" id="to_date" value="{{ old('to_date') }}">
            </div>
        </div>
      
      
        <div class="col-md-2">
            <button type="submit" class="btn btn-success" name="action" value="view">View</button>
            <button type="submit" class="btn btn-info" name="action" value="print"  >Print</button>
        </div>
       

    </div>

</form>