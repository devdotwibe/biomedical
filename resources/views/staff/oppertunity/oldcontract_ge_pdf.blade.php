<html>

          <head>

              <style>

              @page {size :794.993324432577px 1123.7650200267px; margin: 134.15220293725px 0px 114.15220293725px 0px;}

              .otherpages {margin:0px 3% 0px 3%;color:#000; 

				font-family:Arial, Helvetica, sans-serif; 

				font-size:15px;

                font-weight:normal;position:relative;display:inline-block;page-break-before: always;overflow:auto; }

              .firstpage{page-break-inside: never;}

              #body {color:#000; 

				font-family:Arial, Helvetica, sans-serif; 

				font-size:15px;

				font-weight:normal;display:inline-block; margin-top:114.15220293725px; margin-right:19.9599465954606px; margin-bottom:72.0961281708945px; margin-left:0;} 

             

              ._page:after { content: counter(page);}

              .header { position: fixed; top:-125px; left: -1.5%; right:0; height:145px; }

			   .footer { position: fixed; left:0; bottom:-120px; right:0; height:80px; background:#003f86;}

              .footer-wrap{width:94%; margin-left:3%; margin-right:3%;page-break-inside: never;}

			  .footer-wrap h3{top:-40px !important;position:relative;}

			 	ul{

				margin:20px 0 0 0!important;

				padding:0 !important;

				}	

			 ul li, li, ol li{

				color:#000; 

				font-family:Arial, Helvetica, sans-serif; 

				font-size:15px;

				font-weight:normal; 

				margin:0 0 5px 15px!important;

				line-height:22px;

				padding:0 !important;

				

			 }
       .h3tag h3{
        color:#003f86;
        font-family:Arial, Helvetica, sans-serif;
        font-size:19px;
        font-weight:normal; 
        margin:30px 0 20px 0;
        line-height:normal; 
        padding:0;
        text-align:left;
       }

              </style>

        </head>

        <body>


<main> 
        <div class="firstpage">   

          

            <table width="100%" cellpadding="0" cellspacing="0" border="0"  align="center" style="">

                  <tr>

                      <td>

                          <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="">

                              <tr>

                                  <td>

                                      <table width="100%"  cellpadding="0" cellspacing="0" border="0" style="background-color: #fff;  padding:0; margin-top:-35px;">				

                                          <tr valign="top">

                                              <td align="left" width="69%"  style="background-color:#fff; padding:7px 0 7px 5%;   vertical-align: middle; height:15px;" >ANNEXURE- I</td>

                                          </tr>

                                      </table>

                                  </td>

                              </tr>

                              <tr>

                                <td height="10"></td>

                              </tr>

                              <tr>

                                <td>

                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style=" margin-left:-3%;" >

                                        <tr>

                                            <td align="left"  width="72%" style="padding:10px 0;vertical-align: bottom;" >

                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:19px;font-weight:normal; margin:0;line-height:normal; padding:15px 0 15px 30px;text-align:left;">Customer Name :{{ $customer }} -MSA Proposal</h3>

                                             @if(!empty($address))

                                          

                                            @endif

                                            </td>

                         

                                        </tr>
                                        <tr>
                                        <td align="left"  width="20%" style="padding-left:5%;padding-right:0; padding-top:10px;padding-bottom:10px;vertical-align: bottom" >

                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; line-height:normal; padding:5px 0; margin:0;text-align:left;">Valid along with {{ $quote->company_type }}  : {{ $quote->quote_reference_no }}</h3> 

                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; line-height:normal; padding:5px 0; margin:0; text-align:left;">Date  : {{ $quote->created_at->format('d F, Y') }}</p>    

                                            </td>
                                        </tr>
                                        <tr>
                                        <!-- <td align="left"  width="20%" style="padding-left:5%;padding-right:0; padding-top:10px;padding-bottom:10px;vertical-align: bottom" >

                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; line-height:normal; padding:5px 0; margin:0;text-align:left;">Contract Period   : from {{ isset($start_date)?\Carbon\Carbon::parse($start_date)->format('d F, Y'):" " }} to {{ isset($end_date)?\Carbon\Carbon::parse($end_date)->format('d F, Y'):"" }}</h3> 

                              
                                            </td> -->
                                        </tr>

                                    </table>

                                </td>

                            </tr>

                              

                            <tr>

                                <td height="20">

                                </td>

                            </tr>



                             </table>

                            </td>

                        </tr>

                   </table>

                </div>

                <div class="otherpages">   

                    <table width="100%" cellpadding="0" cellspacing="0" border="0"  >

                        <tr>

                            <th align="center"   style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767;background:#676767;">Sl. No:</th>

                            <th align="left"     style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px;border: 1px solid #676767; background:#676767;" colspan="2">Equipment (Type)  .</th>

                            <th align="center"  style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">PM</th>

                            <th align="center"  style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Sl.no of Equp.</th>

                            <th align="center"  style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Qty (Nos)</th>

                            <th align="center"  style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Rate (Rs.)</th>


                            <th align="center"  style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px;border: 1px solid #676767; background:#676767;">Total (Rs.)</th>
                        </tr>
                     @php (float)$total = 0.0; @endphp
                          @foreach($products as $key=>$value)
                          <tr>
                          <td align="center"    style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ ++$key }}</td>

                        <td align="left" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;" colspan="2">{{ $value->oppertunityProduct->name }}

                        </td>

                        <td align="center"   style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ $value->pm }}</td>

                        <td align="center"  style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{optional($value->oppertunityProductIb)->equipment_serial_no}}</td>

                        <td align="center"   style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ $value->quantity }}</td>
                            
                        <td align="center"   style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ $value->single_amount }}</td>


                        <td align="right"   style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ sprintf('%0.2f', $value->single_amount* $value->quantity ) }}</td>
                             
                        </tr>
                     @php $total += $value->single_amount* $value->quantity; @endphp
                              
                          @endforeach
                          <tr>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;" colspan="8"> </td>
                          </tr>
                        <tr>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;"colspan="4"></td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;" colspan="3">SUB - TOTAL</td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ sprintf('%0.2f', $total) }}</td>
                        </tr>
                        <tr>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;"colspan="4"></td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;" colspan="3">ADD :  SERVICE  TAX @ 18%</td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ sprintf('%0.2f', round((float)$total*18/100,2)) }}</td>
                        </tr>
                        <tr>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;"colspan="4"></td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;" colspan="3">TOTAL</td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ sprintf('%0.2f', round((float)$total+($total*18/100),2)) }}</td>
                        </tr>
                        <tr>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;"colspan="4"></td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;" colspan="3">GRAND TOTAL</td>
                            <td align="right" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">{{ round($total+($total*18/100)) }}</td>
                        </tr>
                    </table>

                    <h5><b>CONTRACT PERIOD: 1 YEAR</b></h5>	
                    <h5><b>PAYMENT TERMS: 100% ADVANCE</b></h5>	
                    <br>

                    <h5>FOR Wipro GE Healthcare Pvt. Ltd.</h5>
                    <h5>AUTHORISED SIGNATORY</h5>
                </div>

        </main>

    </body>

</html>