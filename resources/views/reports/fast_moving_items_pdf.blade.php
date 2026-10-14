@include('reports_pdf.header')

    <div class="row" style="padding-top: -3%">

        <h3 align="center" style="color:SteelBlue;">Fast Moving Items</h3>
        

        <div class="row">

            <table width="100%" style="width:100%" border="1" Cellspacing="0">

                <thead>
                    <tr>
                        <th bgcolor="#bfbfbf" style="text-align:center" >No.</th>
                        <th bgcolor="#bfbfbf">Item</th>
                        <th bgcolor="#bfbfbf" style="text-align:center">Sold Quantity</th>
                        <th bgcolor="#bfbfbf" style="text-align:center" >Average Qty/Day</th>                     
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0 ?>
              
                    @foreach($data as $d)
                        <?php $i++ ?>

                        <tr>
                            <td style="text-align:center">{{ $i }}</td>
                            <td>{{$d->name}}</td>
                            <td style="text-align:center">{{$d->qty}}</td>
                            <td style="text-align:center">{{number_format($d->qty/$days->count(),1)}}</td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
   
    </div>

@include('reports_pdf.footer')