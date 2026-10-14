@include('reports_pdf.header')

    <div class="row" style="padding-top: -3%">

        <h3 align="center" style="color:SteelBlue;">Daily Gross Profit</h3>
        <h5 align="center" >{{ $filterMsg }}</h5>

        <div class="row">

            <table width="100%" style="width:100%" border="1" Cellspacing="0">

                <thead>
                    <tr>
                        <th bgcolor="#bfbfbf" >Date</th>
                        <th bgcolor="#bfbfbf" style="text-align:center">Profit</th>
                    </tr>
                </thead>
                <tbody>
              
                    @foreach($data as $d)
                        <tr>
                            <td >{{date_format(new DateTime($d->date),'d M Y')}}</td>
                            <td style="text-align:center">{{number_format($d->profit,2)}}</td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <td bgcolor="#bfbfbf">Total Gross Profit</td>
                        <td bgcolor="#bfbfbf" style="text-align:center">{{number_format($total,2)}}</td>
                    </tr>
                </tfoot>

            </table>

        </div>
   
    </div>

@include('reports_pdf.footer')