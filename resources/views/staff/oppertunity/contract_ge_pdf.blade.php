
<html>

          <head>
<style>

.table-border td{ border:1px  solid #999}
.table-border th{ padding:5px 10px}
</style>
           

        </head>

        <body>


<main> 
        <div class="firstpage">   

          
          
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:15px">
  <tr>
    <td></td>
  </tr>
  <tr>
    <td style="height:100px">&nbsp;</td>
  </tr>

  @php 

        $service_name ="";

        if($service_type =='AMC')
        {
            $service_name = 'Annual Maintenance Contract';

        }elseif ($service_type =='CMC') {
            
            $service_name ='Comprehensive Maintenance Contract';
        }
        else {
            
            $service_name ="";
        }

    @endphp


  <tr>
    <td style="text-align:center; font-size:17px; text-decoration:underline"><strong>@if(!empty($service_name)) {{$service_name}}@endif</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" >

                                <tr>

                                    <td>

                            
                                      <strong>Customer Name :<br></strong>
<?php /* @if(!empty($hospital_desi)){{$hospital_desi}}, @endif */?> {{$customer}},{{$district_name}}</h3>

                                    @if(!empty($address))

                                       

                                    @endif
                                    </td>

                                    <td style="text-align:right">

                                        Ref : {{ $quote->quote_reference_no }}  <br>

                                       Dated : {{date("d-m-Y",strtotime((empty($quote->generate_date)?date("Y-m-d"):$quote->generate_date)))}}   

                                    </td>

                

                                </tr>
                                <tr>
                                <td><br>

                                     Valid along with MSA #: <?php /*Valid along with :  {{ $quote->company_type }}  : {{ $quote->quote_reference_no }} */?> <br> <br> 

                       <strong>             Equipment Details</strong><br><br>

                                    <?php /*<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; line-height:normal; padding:5px 0; margin:0; text-align:left;">Date  : {{ date('d F, Y',strtotime($quote->created_at)) }}</p> */?>

                                    </td>
                                </tr>
                                <tr>
                                <!-- <td align="left"  width="20%" style="padding-left:5%;padding-right:0; padding-top:10px;padding-bottom:10px;vertical-align: bottom" >

                                    <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; line-height:normal; padding:5px 0; margin:0;text-align:left;">Contract Period   : from {{ isset($start_date)?date('d F, Y',strtotime($start_date)):" " }} to {{ isset($end_date)?date('d F, Y',strtotime($end_date)):"" }}</h3> 
                                    

                        
                                    </td>-->
                                </tr>

                            </table></td>
  </tr>
  <tr>
    <td>   <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table-border" >

                <tr style="background-color:#999; color:#fff;">

                    <th >Sl. No.</th>

                    <th>Equipment (Type)</th>

                    <th >Category</th>

                    <th>PM</th>

                    <th>Sl.No.</th>

                    <th >Total (Rs.)</th>
                </tr>
            @php (float)$total = 0.0; @endphp
                    @foreach($products as $key=>$value)

                      @php 
                            
                            $unit_product = $value->sale_amount;

                            $total_product = $value->quantity;

                            $total_price = $unit_product* $total_product;

                            $opurtunity_product = App\Oppertunity_product::where('oppertunity_id',$value->oppertunity_id)->first();

                            $contact_amount_cal=$value->sale_amount*$value->quantity;
                            // $tax=(int)$product->product_tax_percentage;
                            // $tax_cal=$contact_amount_cal*($tax/100);
                            // $totamount_tax=$tax_cal+$contact_amount_cal;
                            $total +=$contact_amount_cal;

                        @endphp


                        @php

if (!function_exists('numberToWords')) {
                    function numberToWords($number) {
                        $words = array(
                            '0' => '', '1' => 'One', '2' => 'Two', '3' => 'Three',
                            '4' => 'Four', '5' => 'Five', '6' => 'Six', '7' => 'Seven',
                            '8' => 'Eight', '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven',
                            '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen',
                            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
                            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
                            '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty', '90' => 'Ninety'
                        );

                        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');

                        if ($number == 0) {
                            return 'Zero';
                        }

                        $result = '';
                        $number = (string)$number;

                        if (strlen($number) > 7) {
                            $crores = substr($number, 0, -7);
                            $result .= numberToWords((int)$crores) . ' ' . $digits[4] . ' ';
                            $number = substr($number, -7);
                        }

                        if (strlen($number) > 5) {
                            $lakhs = substr($number, 0, -5);
                            $result .= numberToWords((int)$lakhs) . ' ' . $digits[3] . ' ';
                            $number = substr($number, -5);
                        }

                        if (strlen($number) > 3) {
                            $thousands = substr($number, 0, -3);
                            $result .= numberToWords((int)$thousands) . ' ' . $digits[2] . ' ';
                            $number = substr($number, -3);
                        }

                        if (strlen($number) == 3) {
                            $hundreds = substr($number, 0, 1);
                            if ($hundreds > 0) {
                                $result .= $words[$hundreds] . ' ' . $digits[1];
                            }
                            $number = substr($number, 1);
                            if ($number > 0) {
                                $result .= ' and ';
                            }
                        }

                        if ($number > 0) {
                            if ($number < 21) {
                                $result .= $words[(int)$number];
                            } else {
                                $tens = substr($number, 0, 1) . '0';
                                $units = substr($number, 1);
                                $result .= $words[$tens] . ' ';
                                if ($units != '0') {
                                    $result .= $words[$units];
                                }
                            }
                        }

                        return trim($result);
                    }
                }

                if (!function_exists('convertToRupees')) {
                    function convertToRupees($number) {
                        $parts = explode('.', number_format((float)$number, 2, '.', ''));
                        $integerPart = (int)$parts[0];
                        $decimalPart = isset($parts[1]) ? (int)$parts[1] : 0;

                        $result = 'Rupees ' . numberToWords($integerPart);
                        if ($decimalPart > 0) {
                            $result .= ' and ' . numberToWords($decimalPart) . ' Paise';
                        }
                        return $result . ' Only';
                    }
                }


                        $service_tax_percentage =  18; // Service tax percentage
                        $service_tax = $total * ($service_tax_percentage / 100);
                        $grand_total = $total + $service_tax;
                        @endphp


                    <tr>
                              <td align="center" >{{ ++$key }}</td>
                              <td align="center">{{ $value->oppertunityProduct->name }} </td>
                              <td align="center">{{optional($value->oppertunityProduct)->category_name}}</td>
                              <td align="center" >{{ $value->pm }}</td>
                              <td align="center">{{optional($value->oppertunityProductIb)->equipment_serial_no}}</td>
                        
                              <td align="right">{{ number_format($total_price, 2) }}&nbsp;&nbsp;</td>
            </tr>

            @endforeach

                    
                    <tr>
                      <td >&nbsp;</td>
                      <td>&nbsp;</td>
                      <td >&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    
                      <td align="right">&nbsp;</td>
                    </tr>

                    

                    <tr>
                      <td >&nbsp;</td>
                      <td>&nbsp;</td>
                      <td >&nbsp;</td>
                     
                      <td colspan="2" ><strong>&nbsp;SUB - TOTAL</strong></td>
                      <td align="right"><strong> {{ number_format($total, 2) }}</strong>&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                      <td >&nbsp;</td>
                      <td>&nbsp;</td>
                      <td >&nbsp;</td>
                    
                      <td colspan="2" >&nbsp;<strong> GST @ {{$service_tax_percentage}}%</strong></td>
                      <td align="right"><strong>{{ number_format($service_tax, 2) }}</strong>&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                      <td >&nbsp;</td>
                      <td>&nbsp;</td>
                      <td >&nbsp;</td>
                   
                      <td colspan="2" ><strong>&nbsp;TOTAL</strong></td>
                      <td align="right"><strong>{{ number_format($grand_total, 2) }}</strong>&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td >&nbsp;</td>
                        <td>&nbsp;</td>
                        <td >&nbsp;</td>

                <td colspan="2" >&nbsp;<strong>GRAND TOTAL</strong></td>
                    
                <td align="right"><strong>{{ number_format($grand_total, 2) }}</strong>&nbsp;&nbsp;</td>
                    
                </tr>
          
                    <tr>
                    <td colspan="6"> </td>
                    </tr>

                     
                </table>
<br><br>
                     {{ convertToRupees(round($grand_total)) }}<br><br>


                    @if(!empty($quote->terms_condition))                    
                                            
                        {!! $quote->terms_condition !!} 

                    @else

                       CONTRACT PERIOD: 1 YEAR<br>
                      PAYMENT TERMS: 100% ADVANCE<br>	
                        <br><br>
                        AUTHORISED SIGNATORY<br>

                    @endif
</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  </table>

          

        

      </div>

          

        </main>

    </body>

</html>