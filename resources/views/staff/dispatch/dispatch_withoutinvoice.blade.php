

              <table id="cmsTable" class="table table-bordered table-striped data- hideform">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Dispatch Quantity</th>
                  <th>HSN</th>
                  <th>Source</th>
                  <th>Dispatch From</th>
                  <th>Action</th>
                 
                </tr>
                </thead>
                <tbody>
                @php($i=1)
                  @if(count($transaction_product)>0)
                  @foreach($transaction_product as $values)
                
                    @if($values->quantity-$values->out_product_quantity>0)
                  <tr class="row_{{$values->id}}">
                  <td>{{$i}}</td>
                  <td>{{$values->product_name}}
                  <input type="hidden"  id="product_name_{{$values->id}}" name="product_name[]" value="{{$values->product_name}}" class="form-control" >
                  <input type="hidden"  id="ids_{{$values->id}}" name="ids[]" value="{{$values->id}}" class="form-control" >
                  </td>
                  <td>{{$values->quantity-$values->out_product_quantity}}
                  <input attr-id="{{$values->id}}" type="hidden" name="invoice_qty[]" id="invoice_qty_{{$values->id}}" value="{{$values->quantity-$values->out_product_quantity}}"> 
                  <input attr-id="{{$values->id}}" type="hidden" name="product_id[]" id="product_id_{{$values->product_id}}" value="{{$values->product_id}}"> 
                
                  <span class="error_message" id="error_tot_qty_{{$values->id}}" style="display: none"></span>
                  </td>
                  <td>
                  <input attr-id="{{$values->id}}" attr-row="0" onchange="change_qty(this.value,{{$values->id}},0)" type="number" @if($values->quantity==1) readonly="true" @endif id="quantity_{{$values->id}}_0" name="quantity[]" value="{{$values->quantity-$values->out_product_quantity}}" class="form-control" placeholder="Quantity">
                  <span class="error_message" id="quantity_message_{{$values->id}}_0" style="display: none">Field is required</span>
                  </td>
                  <td>
                  <input readonly="true" attr-id="{{$values->id}}" attr-row="0" type="text" id="hsn_{{$values->id}}_0" name="hsn[]" value="{{$values->hsn}}" class="form-control hideform" placeholder="HSN">
                  <span class="error_message" id="hsn_message_{{$values->id}}_0" style="display: none">Field is required</span>
                  </td>
                  <td>
                  <select attr-id="{{$values->id}}" attr-row="0"  id="source_{{$values->id}}_0" name="source[]" class="form-control" onchange="change_source(this.value,{{$values->id}},0)">
                  <option value="">Source</option>
                  <option value="Staff">Staff</option>
                  <option value="Warehouse">Warehouse</option>
                  </select>
                  <span class="error_message" id="source_message_{{$values->id}}_0" style="display: none">Field is required</span>
                  </td>
                  <td>
                  <select id="staff_id_{{$values->id}}_0" name="staff_id[]"  class="form-control stafftext staff_sel" disabled="true">
                  <option value="">Staff</option>
                  @foreach($staff as $staff_value)
                  <option value="{{$staff_value->id}}">{{$staff_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="staff_id_message_{{$values->id}}_0" style="display: none">Field is required</span>

                  <select id="warehouse_id_{{$values->id}}_0" name="warehouse_id[]"  class="form-control" style="display:none">
                  <option value="">Warehouse</option>
                  @foreach($warehouse as $warehouse_value)
                  <option value="{{$warehouse_value->id}}">{{$warehouse_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="warehouse_id_message_{{$values->id}}_0" style="display: none">Field is required</span>

                  </td>
                  <td >
                  @if($values->quantity!=1)
                  <input type="hidden" name="countpro[]" id="countpro_{{$values->id}}" value="0">
                  <button type="button"  attr-id="{{$values->id}}" attr-name="{{$values->product_name}}" attr-product_id="{{$values->product_id}}"  attr-hsn="{{$values->hsn}}" class="sml-btn submit-btn add_dispatch addbtn_{{$values->id}}_0">+</button>
                  @endif
                  </td>
                 
                </tr>
                @php($i++)
                @endif

                  @endforeach
                  @endif
                </tbody>
                </table>

                </div>

                <div class="box-body row">

                <div class="form-group col-md-4">
                  <label for="name">Courier*</label>
                  <select id="courier_id" name="courier_id"  class="form-control" >
                  <option value="">Courier</option>
                  @foreach($courier as $courier_value)
                  <option value="{{$courier_value->id}}">{{$courier_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="courier_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-4 ">
                  <label for="name">Verified Staff*</label>
                  <select id="verified_staff" name="verified_staff"  class="form-control" >
                  <option value="">Verified Staff</option>
                  @foreach($staff as $staff_value)
                  <option value="{{$staff_value->id}}">{{$staff_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="verified_staff_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-12">
                
                <button type="button" class="lg-btn submit-btn " onclick="validate_form()">Submit For Verification</button>
                </div>

                <input type="hidden" name="invoice_id" id="invoice_id" value="0">

                </div>


              <!-- /.box-body -->

       


            <script>
$(".add_dispatch").click(function() {
var id=$(this).attr("attr-id");
var pro_name=$(this).attr("attr-name");
var hsn=$(this).attr("attr-hsn");
var count_pro=$("#countpro_"+id).val();
var product_id=$(this).attr("attr-product_id");
var add=parseInt(count_pro)+1;
$("#countpro_"+id).val(add);
var htmls='<tr class="childrow_'+id+'_'+count_pro+'">'+
'<input type="hidden" id="product_name_'+id+'" name="product_name[]" value="'+pro_name+'" >'+
'<input type="hidden" id="product_id_'+id+'" name="product_id[]" value="'+product_id+'" >'+
'<input type="hidden" id="ids_'+id+'" name="ids[]" value="'+id+'" >'+
'<td></td>'+
'<td></td>'+
'<td></td>'+
'<td> <input attr-id="'+id+'" attr-row="'+add+'" onchange="change_qty(this.value,'+id+','+add+')" type="number" id="quantity_'+id+'_'+add+'" name="quantity[]" value="" class="form-control" placeholder="Quantity">'+
 '<span class="error_message" id="quantity_message_'+id+'_'+add+'" style="display: none">Field is required</span></td>'+
'<td> <input attr-id="'+id+'" readonly="true" attr-row="'+add+'" type="text" id="hsn_'+id+'_'+add+'" name="hsn[]" value="'+hsn+'" class="form-control hideform" placeholder="HSN">'+
 '<span class="error_message" id="hsn_message_'+id+'_'+add+'" style="display: none">Field is required</span></td>'+
'<td> <select attr-id="'+id+'" attr-row="'+add+'" id="source_'+id+'_'+add+'" name="source[]"  class="form-control" onchange="change_source(this.value,'+id+','+add+')">'+
' <option value="">Source</option>'+
' <option value="Staff">Staff</option>'+
' <option value="Warehouse">Warehouse</option>'+
' </select><span class="error_message" id="source_message_'+id+'_'+add+'" style="display: none">Field is required</span></td>'+
'<td>'+
'<select id="staff_id_'+id+'_'+add+'" name="staff_id[]"  class="form-control stafftext staff_sel" disabled="true">'+
 '<option value="">Staff</option>'+
  @foreach($staff as $staff_value)
  '<option value="{{$staff_value->id}}">{{$staff_value->name}}</option>'+
  @endforeach
  '</select>'+
  '<span class="error_message" id="staff_id_message_'+id+'_'+add+'" style="display: none">Field is required</span>'+
  '<select id="warehouse_id_'+id+'_'+add+'" name="warehouse_id[]"  class="form-control" style="display:none">'+
  '<option value="">Warehouse</option>'+
  @foreach($warehouse as $warehouse_value)
  '<option value="{{$warehouse_value->id}}">{{$warehouse_value->name}}</option>'+
  @endforeach
  '</select>'+
  '<span class="error_message" id="warehouse_id_message_'+id+'_'+add+'" style="display: none">Field is required</span>'+
'</td>'+
'<td><button type="button" attr-id="'+id+'" attr-row="'+count_pro+'" class="sml-btn submit-btn remove_dispatch">-</button></td>';
$(".row_"+id).closest( "tr" ).after(htmls);
$(".remove_dispatch").click(function() {
  var id=$(this).attr("attr-id");
  var row_no=$(this).attr("attr-row");
  
  var count_pro=$("#countpro_"+id).val();
var add=parseInt(count_pro)-1;
$("#countpro_"+id).val(add);
$('.childrow_'+id+'_'+row_no).remove();
});
});
$(".remove_dispatch").click(function() {
  var id=$(this).attr("attr-id");
  var row_no=$(this).attr("attr-row");
  
  var count_pro=$("#countpro_"+id).val();
var add=parseInt(count_pro)-1;
$("#countpro_"+id).val(add);
$('.childrow_'+id+'_'+row_no).remove();
});
            </script>
            