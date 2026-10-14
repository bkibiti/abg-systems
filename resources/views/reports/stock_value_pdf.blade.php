@include('reports_pdf.header')

    <div class="row" style="padding-top: -3%">

        <h3 align="center" style="color:SteelBlue;">Current Stock Value</h3>
        

        <div class="row">

            <table width="100%" style="width:100%" border="1" Cellspacing="0">

                <thead>
                    <tr>
                        <th bgcolor="#bfbfbf" style="text-align:center" >No.</th>
                        <th bgcolor="#bfbfbf">Item</th>
                        <th bgcolor="#bfbfbf" style="text-align:center">QOH</th>
                        <th bgcolor="#bfbfbf" style="text-align:center" >Value by Purchase Price</th> 
                        <th bgcolor="#bfbfbf" style="text-align:center" >Value by Selling Price</th>    
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0 ?>
              
                    @foreach($data as $d)
                        <?php $i++ ?>

                        <tr>
                            <td style="text-align:center">{{ $i }}</td>
                            <td>{{$d->name}}</td>
                            <td style="text-align:center">{{$d->quantity}}</td>
                            <td style="text-align:right">{{number_format($d->purchase_value,2)}}</td>
                            <td style="text-align:right">{{number_format($d->sale_value,2)}}</td>
                        </tr>
                    @endforeach

                </tbody>
                <tr>
                    <td bgcolor="#bfbfbf" style="text-align:center" colspan="3" >Total Stock Value</td>
                    <td bgcolor="#bfbfbf" style="text-align:right">{{number_format($total[0]->total_purchase_value,2)}}</td>
                    <td bgcolor="#bfbfbf" style="text-align:right">{{number_format($total[0]->total_sale_value,2)}}</td>
                </tr>

            </table>

        </div>
   
    </div>

@include('reports_pdf.footer')