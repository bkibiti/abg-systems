@include('reports_pdf.header')

    <div class="row" style="padding-top: -3%">

        <h3 align="center" style="color:SteelBlue;">Daily Sale Report</h3>
        

        <div class="row">

            <table width="100%" style="width:100%" border="1" Cellspacing="0">

                <thead>
                    <tr>
                        <th bgcolor="#bfbfbf" style="text-align:center" >No.</th>
                        <th bgcolor="#bfbfbf" width="32%" >Item</th>
                        <th bgcolor="#bfbfbf" width="10%" style="text-align:center" >Opening Stock (A)</th>
                        <th bgcolor="#bfbfbf" width="10%" style="text-align:center" >Purchase (B)</th> 
                        <th bgcolor="#bfbfbf" width="10%" style="text-align:center" >Closing Stock (C)</th>   
                        <th bgcolor="#bfbfbf" width="10%" style="text-align:center" >Sold (A+B-C)</th>    
                        <th bgcolor="#bfbfbf"  style="text-align:center" >Unit Price</th>    
                        <th bgcolor="#bfbfbf" width="15%" style="text-align:center" >Amount</th>    
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                        $category = 'na';
                    @endphp
              
                    @foreach($data as $d)
                        <?php $i++ ?>
                        
                        @if ($category <> $d->category)
                            <tr>
                                <td colspan="2"> <strong> {{$d->category}} </strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>                           
                            </tr>
                            <?php $category = $d->category ?>

                        @endif

                        <tr>
                            <td style="text-align:center">{{ $i }}</td>
                            <td>{{$d->name}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">{{number_format($d->price,0)}}</td>
                            <td></td>                           
                        </tr>
                    @endforeach

                </tbody>
                <tr>
                    <td bgcolor="#bfbfbf" style="text-align:center" colspan="7" > <strong>Total</strong> </td>
                    <td bgcolor="#bfbfbf" style="text-align:right"> </td>
                </tr>

            </table>

        </div>
   
    </div>

@include('reports_pdf.footer')