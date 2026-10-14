@include('reports_pdf.header')

    <div class="row" style="padding-top: -2%">

        <h3 align="center" style="color:SteelBlue;">Stock Count Sheet</h3>


        <div class="row">

            <table width="100%" style="width:100%" border="1" Cellspacing="0">

                <thead>
                    <tr>
                        <th bgcolor="#bfbfbf" style="text-align:center" >No.</th>
                        <th bgcolor="#bfbfbf">Item</th>
                        <th bgcolor="#bfbfbf">Category</th>
                        <th bgcolor="#bfbfbf" style="text-align:center">QOH</th>
                        <th bgcolor="#bfbfbf">Physical Count</th>
                        <th bgcolor="#bfbfbf">Difference</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0 ?>
                    @foreach($products as $product)
                         <?php $i++ ?>

                        <tr>
                            <td style="text-align:center">{{ $i }}</td>
                            <td>{{$product->name}}</td>
                            <td>{{$product->category->name}}</td>
                            <td style="text-align:center">{{$product->quantity}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
   
    </div>

@include('reports_pdf.footer')