



         @php
          $sale_amount=0;
          $j=1;

          $myTotal = 0; 
          $tot_cgst=0;
          $tot_sgst=0;
          $tot_igst=0;
          $tot_cess=0;
          $tot_taxable=0;
        @endphp
            
         @foreach($transaction_product as $values)

      @php
         $amt    = $values->des_quantity * $values->sale_amount;
               $taxab_amount=$amt;
               $tran_type=$user[0]->tran_type;
              
                $tax=$values->tax_percentage;
           
                if($tran_type=="Intra State Registered Sales" || $tran_type=="Government Sales Registered")
                {
                  $cgst=$tax/2;
                  $cgst=$taxab_amount*$cgst/100;
                  $sgst=$tax/2;
                  $sgst=$taxab_amount*$sgst/100;
                  $igst=0;   
                  $cess=0;
                  $tax_cal= $taxab_amount*$tax/100;
                 }

              
                  if($tran_type=="Intra State Un-Registered Sales" || $tran_type=="Government Sales Unregistered")
                   {
                    $cgst=$tax/2;
                    $cgst=$taxab_amount*$cgst/100;
                    $sgst=$tax/2;
                    $sgst=$taxab_amount*$sgst/100;
                    $igst=0;   
                    $cess=1;
                    $cess=$taxab_amount*$cess/100;
                    $tax=$tax+$cess;

                    $tax_cal= $taxab_amount*$tax/100;
                    }
                 if($tran_type=="InterState Registered Sales")
                  {
                    $igst=$tax;
                    $igst= $taxab_amount*$igst/100;
                    $cgst=0;
                    $sgst=0;
                    $cess=0;
                    $tax_cal= $taxab_amount*$tax/100;
                  }
                  if($tran_type=="InterState Un-Registered Sales")
                  {
                    $igst=$tax;
                    $cgst=0;
                    $sgst=0;
                    $cess=1;
                    $cess=$taxab_amount*$cess/100;
                    $tax=$tax+$cess;
                    $tax_cal= $taxab_amount*$tax/100;
                  }

                  $amt=$taxab_amount+$tax_cal;

           @endphp 
              
        <tr class="tr_{{$values->trans_id}}">
        <td data-th="" ><input type="checkbox" class="dataCheck" checked="true" disabled="true"  name="idscheck[]" value="{{$values->trans_id}}" id="idscheck{{$values->trans_id}}">
        <input type="hidden" class="dataCheck"   name="ids[]" value="{{$values->trans_id}}" id="check{{$values->trans_id}}">
        </td>
        <td data-th="No." >{{$j}}</td><td>{{$values->product_name}}</td>
        
        <td data-th="Qty" ><input readonly="true" type="number" value="{{$values->des_quantity}}" name="quantity[]" id="qn_{{$values->trans_id}}" class="quantity form-control"  data-id="{{$values->trans_id}}"  ></td>
 
      
      <td data-th="Unit Price" ><input type="text" readonly="true" name="sale_amount[]" value="{{$values->sale_amount}}" id="sa_{{$values->sale_amount}}" onchange="change_sale_amt(this.value,'{{$values->sale_amount}}')" class="sale_amt form-control" data-id="{{$values->sale_amount}}" style="width:60px;"></td>
      
      <td data-th="HSN" ></td>
              
        <td data-th="CGST" ><input type="text" readonly="true" value="{{$cgst}}" id="cgst_{{$values->trans_id}}"  class="cgst form-control" name="cgst[]" data-id="{{$values->trans_id}}" ></td>
        <td data-th="SGST" ><input type="text"  readonly="true" value="{{$sgst}}" id="sgst_{{$values->trans_id}}"  class="sgst form-control" name="sgst[]" data-id="{{$values->trans_id}}"></td>
        <td data-th="IGST" ><input type="text" readonly="true" value="{{$igst}}" id="igst_{{$values->trans_id}}"  class="igst form-control" name="igst[]" data-id="{{$values->trans_id}}" ></td>
        <td data-th="Cess" ><input type="text" readonly="true" value="{{$cess}}" id="cess_{{$values->trans_id}}"  class="cess form-control" name="cess[]" data-id="{{$values->trans_id}}" ></td>
        <td data-th="Net Amount" ><input type="text" value="{{$amt}}" id="am_{{$values->trans_id}}" class="amt form-control" name="amt[]" data-id="{{$values->trans_id}}" readonly>
        <input type="hidden" value="{{$values->tax_percentage}}" id="tax_percentage_{{$values->trans_id}}" class="tax_percentage form-control" name="tax_percentage[]" data-id="{{$values->trans_id}}" readonly></td></tr>
             
              @php
           
              $j++;

              $myTotal += $amt;
              $tot_cgst += $cgst;
              $tot_sgst += $sgst;
              $tot_igst += $igst;
              $tot_cess += $cess;
              
              @endphp
              
           @endforeach
         


            <tr class="footertr">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
           
                <td></td>
              
                <td>{{$tot_cgst}}</td>
                <td>{{$tot_sgst}}</td>
                <td>{{$tot_igst}}</td>
                
                <td>{{$tot_cess}}</td>
               <td>{{$myTotal}}</td><td></td>
            </tr>

            <tr class="footertr">
                
                <th colspan="5">Customer Name</th>
               <th colspan="5">Address</th>
            </tr>
            <tr class="footertr">
                
                <td colspan="5">{{$user[0]->business_name}}</td>
               <td colspan="5">{{$user[0]->address1}}</td>
            </tr>


            