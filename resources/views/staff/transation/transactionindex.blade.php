
@extends('staff/layouts.app')

@section('title', 'Manage Transaction')

@section('content')

<section class="content-header">
      <h1>
        Manage Transaction
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Transaction</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="row">
              <div class="col-md-12">
                 <h3>Pending Transaction</h3>
                </div>
            </div>
            

            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="row innertable-box staf-transtion">
              <div class="col-md-12 pd-lr-none">
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/transation/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                 
                  <th>No.</th>
                  <th>Order Id</th>
                  <th>Customer</th>
                  <th>Added Date</th>
                  <th>Action</th>
                 
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($transation_pending as $pending)
                    @if($pending->due_date!='')
                    <tr id="tr_{{$pending->id}}" data-id="{{$pending->id}}" data-from ="transation"  @if(strtotime(date("Y-m-d"))>strtotime($pending->due_date)) style="background-color:red !important;" @endif>
                    @endif
                    @if($pending->due_date=='')
                    <tr id="tr_{{$pending->id}}" data-id="{{$pending->id}}" data-from ="transation" >
                    @endif
                       
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        @if($pending->type_approval=="Sale Order")
                        <td>Trans_{{$pending->transation_id}}</td>
                        @endif

                        @if($pending->type_approval=="Invoice")
                        <td>INVOICE_{{$pending->invoice_id}}</td>
                        @endif
                        <?php
                         $user_name= '';;
                        if($pending->user_id>0){
                        $user = App\User::find($pending->user_id);
                        if($user)
                        {
                        $user_name= $user->business_name;
                            }else{
                              $user_name= '';;
                            }
                          }
                            ?>
                        <td>{{$user_name}} </td>
                        <td>{{$pending->added_date}}</td>
                        <td>
                        @if($pending->status=="Technical Approval")
                        <a href="{{ route('staff.transation.edit',$pending->transation_id) }}?type=tech" class="mdm-btn-line submit-btn"  >Edit</a>
                        <a  class="mdm-btn-line submit-btn approve_btn"  attr-id='{{$pending->id}}' attr-status='{{$pending->status}}' attr-trans='{{$pending->transation_id}}'>Approve ({{$pending->status}})</a>
                        @endif
                        @if($pending->status=="MSP Approval")
                        <a href="{{ route('staff.transation.edit',$pending->transation_id) }}?type=msp" class="mdm-btn-line submit-btn"  >Edit</a>
                        <a  class="mdm-btn-line submit-btn approve_btn" attr-id='{{$pending->id}}' attr-status='{{$pending->status}}' attr-trans='{{$pending->transation_id}}'>Approve ({{$pending->status}})</a>
                        @endif
                        @if($pending->status=="Financial Approval")
                        <a href="{{ route('staff.transation.edit',$pending->transation_id) }}?type=fin" class="mdm-btn-line submit-btn"  >Edit</a>
                        <a  class="mdm-btn-line submit-btn approve_btn" attr-id='{{$pending->id}}' attr-status='{{$pending->status}}' attr-trans='{{$pending->transation_id}}'>Approve ({{$pending->status}})</a>
                        @endif
                        @if($pending->status=="Stock Approval")
                        <a disabled="true" class="mdm-btn-line submit-btn approve_btn" attr-id='{{$pending->id}}' attr-status='{{$pending->status}}' attr-trans='{{$pending->transation_id}}'>Approve ({{$pending->status}})</a>
                        @endif
                        @if($pending->status=="Dispatch Invoice")
                       
                       @if($pending->current_status=="Pending")
                        <a href="{{ route('staff.create_dispatch',$pending->invoice_id) }}" >Create Despatch ({{$pending->status}})</a>
                        @endif

                        @if($pending->current_status=="Verification")
                        Waiting For Verification
                        @endif


                        @endif

                        @if($pending->status=="Dispatch Verify")
                       
                        <a href="{{ route('staff.dispatch_verify',$pending->dispatch_id) }}" > Despatch Verify</a>
                      
                        @endif

                        @if($pending->status=="Delivery Invoice")
                        <a  attr-id='{{$pending->id}}' attr-user_id='{{$pending->user_id}}' attr-status='{{$pending->status}}' attr-trans='{{$pending->transation_id}}' class="approve_delivery">Approve ({{$pending->status}})</a>
                        @endif

                        @if($pending->status=="User Confirmation" || $pending->status=="Department Confirmation" || $pending->status=="Finance Confirmation" || $pending->status=="Payment Confirmation")
                        <a  attr-id='{{$pending->id}}' attr-user_id='{{$pending->user_id}}' attr-status='{{$pending->status}}' attr-trans='{{$pending->transation_id}}' class="approve_below_user">Approve ({{$pending->status}})</a>
                        @endif




                         </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

              

              </table>


            </form>
             </div>
             </div>
            <div class="row">
              <div class="col-md-12 ">
                 <h3>Completed Transaction</h3>
                </div>
              </div>
               <div class="row border-row complet-trans">
                  <div class="col-md-12 ">
                <table id="cmsTable1" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                 
                  <th>No.</th>
                  <th>Transaction Id</th>
                  <th>Status</th>
                 
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($transation_approval as $approval)
                    <tr id="tr_{{$approval->id}}" data-id="{{$approval->id}}" data-from ="transation">
                       
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        @if($approval->type_approval=="Sale Order")
                        <td>Trans_{{$approval->transation_id}}</td>
                        @endif

                        @if($approval->type_approval=="Invoice")
                        <td>INVOICE_{{$approval->invoice_id}}</td>
                        @endif
                        <td>{{$approval->status}}
                        @if($approval->status=="Dispatch Verify")
                        <a href="{{ route('staff.dispatch_verify_view',$approval->invoice_id) }}" target="_blank" > View</a>
                        @endif
                         </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

              

              </table>
            </div>
            </div>
            </div>
            </div>
            </div>

    </section>



   
    <div class="modal fade" id="modal_success_tran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Success</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Successfully Approved Transaction</p>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



   
<div class="modal fade" id="modal_delvery_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form role="form" name="frm_delinvoice" id="frm_delinvoice" method="post" action="{{route('staff.delivery_approve')}}" enctype="multipart/form-data" >
               @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Approve</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body box-body row">
      <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
        <label for="name">Please upload the  deliverd bill </label>
        <input type="file" id="upload_del_bill"  name="upload_del_bill" class="form-control" >
        <input type="hidden" name="tran_staff_id" id="tran_staff_id">
      </div>

      <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
        <label for="name">Delivery Date</label>
        <input id="del_date" name="del_date"   class="form-control" placeholder="Delivery Date">
        <span class="error_message" id="del_date_message" style="display: none">Required Field!</span>
      </div>

      
      <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
        <label for="name">Contact person</label>
        <select id="contact_person_id" name="contact_person_id"   class="form-control" >
            <option value="">Contact person</option>
            
        </select>
        <span class="error_message" id="contact_person_id_message" style="display: none">Required Field!</span>
      </div>



      <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
        <label for="name">Please Add Comment*</label>
        <textarea id="comment" name="comment"   class="form-control" placeholder="Add Comment"></textarea>
        <span class="error_message" id="comment_message" style="display: none">Required Field!</span>
      </div>

     

      </div>
      <div class="modal-footer">
                  
      <button type="button" class="lg-btn submit-btn " onclick="validate_verify_form()">Approve</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>

    </form>
  </div>
</div>



<div class="modal fade" id="modal_bel_user_permission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form role="form" name="frm_user" id="frm_user" method="post" action="{{route('staff.after_user_approve')}}" enctype="multipart/form-data" >
               @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Approve</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body box-body row">
     

      <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
        <label for="name">Please Add Comment*</label>
        <textarea id="comment_user" name="comment_user"   class="form-control" placeholder="Add Comment"></textarea>
        <span id="dep" style="display:none;">
        Verify that all the require document submit to department like contract,agreement,installation report, CMC report etc ,if applicable contact person
        </span>
        <span id="fin" style="display:none;">
        Verify that all the require document are revived in finance department for payment
        </span>
        <span class="error_message" id="comment_user_message" style="display: none">Required Field!</span>
      </div>

      <div class="form-group col-md-12 col-sm-6 col-lg-12 bank_details" style="display:none;">
        <label for="name">Bank Details</label>
        <textarea id="bank_details" name="bank_details"   class="form-control" placeholder="Bank Details"></textarea>
      
        <span class="error_message" id="bank_details_message" style="display: none">Required Field!</span>
      </div>

      <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
        <label for="name">Contact person</label>
        <select id="user_contact_person_id" name="user_contact_person_id"   class="form-control" >
            <option value="">Contact person</option>
            
        </select>
        <span class="error_message" id="user_contact_person_id_message" style="display: none">Required Field!</span>
      </div>

      <input type="hidden" name="tran_staff_id_user" id="tran_staff_id_user">

      </div>
      <div class="modal-footer">
                  
      <button type="button" class="lg-btn submit-btn" onclick="validate_user_form()">Approve</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>

    </form>
  </div>
</div>


    


@endsection

@section('scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
function validate_user_form()
{
  var comment_user=$("#comment_user").val();
  if(comment_user=="")
  {
    $("#comment_user_message").show();
  }
  else{
    $("#comment_user_message").hide();
  }
  if(comment_user!='')
  {
    $("#frm_user").submit();
  }
}
function validate_verify_form()
{
  var comment=$("#comment").val();
  var del_date=$("#del_date").val();
  
  if(comment=="")
  {
    $("#comment_message").show();
  }
  else{
    $("#comment_message").hide();
  }
  if(del_date=="")
  {
    $("#del_date_message").show();
  }
  else{
    $("#del_date_message").hide();
  }
  if(comment!='' && del_date!='')
  {
    $("#frm_delinvoice").submit();
  }
}
jQuery(document).ready(function() {
  if ($(window).width() <= 1024) {
  }
  else{
    $(".sidebar-toggle").trigger("click");
  }
});
jQuery(".approve_below_user").click(function() {
  $("#dep").hide();
  $("#fin").hide();
  var id=$(this).attr('attr-id');
    var status=$(this).attr('attr-status');
    var trans_id=$(this).attr('attr-trans');
    $("#modal_bel_user_permission").modal("show");
    $("#tran_staff_id_user").val(id);
    if(status=="Department Confirmation")
    {
      $("#dep").show();
    }
    
    if(status=="Finance Confirmation")
    {
      $("#fin").show();
    }
    if(status=="Payment Confirmation")
    {
      $(".bank_details").show();
    }
    
    var user_id=$(this).attr('attr-user_id');
   
    var url = APP_URL+'/staff/get_user_contact_details';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id: user_id
          },
          success: function (data)
          {  
            var contact_person = JSON.parse(data);
            var html='';
            html +='<option value="">Select Contact </option>';
          for (var i = 0; i < contact_person.length; i++) {
            html +='<option value='+contact_person[i]["id"]+'>'+contact_person[i]["name"]+' </option>';
          }
            $("#user_contact_person_id").html(html);
          }
        });
});
jQuery(".approve_delivery").click(function() {
  var id=$(this).attr('attr-id');
    var status=$(this).attr('attr-status');
    var trans_id=$(this).attr('attr-trans');
    $("#modal_delvery_invoice").modal("show");
    var user_id=$(this).attr('attr-user_id');
   
    var url = APP_URL+'/staff/get_user_contact_details';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id: user_id
          },
          success: function (data)
          {  
            var contact_person = JSON.parse(data);
            var html='';
            html +='<option value="">Select Contact </option>';
          for (var i = 0; i < contact_person.length; i++) {
            html +='<option value='+contact_person[i]["id"]+'>'+contact_person[i]["name"]+' </option>';
          }
            $("#contact_person_id").html(html);
          }
        });
    $("#tran_staff_id").val(id);
    $('#del_date').datepicker({
        //dateFormat:'yy-mm-dd',
        dateFormat:'yy-mm-dd',
        minDate: 0
    });
    
});
jQuery(".approve_btn").click(function() {
 
    if (confirm('Do you want to approval current transaction!')) {
    var id=$(this).attr('attr-id');
    var status=$(this).attr('attr-status');
    var trans_id=$(this).attr('attr-trans');
    var url = APP_URL+'/staff/approval_transaction_staff';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            id: id,status:status,trans_id:trans_id
          },
          success: function (data)
          {  
            $("#modal_success_tran").modal('show');
            setTimeout(function(){ location.reload(); }, 2000);
          }
        });
      }
   
  });
function view_product(transation_id)
{
  var url = APP_URL+'/staff/view_transation_all_product';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            transation_id: transation_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            var htmlscontent='';
            var c=1
            for (var i = 0; i < proObj.length; i++) {
              if(proObj[i]["image_name"]==null || proObj[i]["image_name"]=='')
              {
                var imgs="{{asset('images/')}}/no-image.jpg";
              }
              else{
                var imgs="{{asset('storage/app/public/products/thumbnail/')}}/"+proObj[i]["image_name"];
               }
               var quantity=proObj[i]["qty"];
               var sale_amount=proObj[i]["sale_amount"];
               var amt=proObj[i]["amt"];
               var cgst=proObj[i]["cgst"];
               var sgst=proObj[i]["sgst"];
               var igst=proObj[i]["igst"];
               var cess=proObj[i]["cess"];
               var surplus_amt=proObj[i]["surplus_amt"];
              htmlscontent +='<tr class="tr_'+proObj[i]["id"]+'"><td>'+c+'</td><td>'+proObj[i]["name"]+'</td>';
                         
              htmlscontent +='  </tr>';
              //tabledata
              c++;
            }
            $("#tabledata").html(htmlscontent);
            $("#modal_trans_product").modal("show");
          }
        });
}
    jQuery(document).ready(function() {
        var oTable = $('#cmsTable').DataTable({

          initComplete: function(settings) {

            var info = this.api().page.info();
            var api = this.api();

            if (info.pages > 1) {

                $(".dataTables_paginate").show();
            } else {
                $(".dataTables_paginate").hide();

            }

            var searchInput = $('<input type="number" min="1" step="1" class="page-search-input" placeholder="Search pages...">');
            $(".col-sm-7").append(searchInput);

            if (info.pages > 1) {

                searchInput.on('input', function() {

                    var searchValue = $(this).val().toLowerCase();

                    var pageNum = searchValue - 1;

                    api.page(pageNum).draw('page');
                });
            }


            if (info.recordsTotal == 0) {

                $(".dataTables_info").hide();
            } else {
                $(".dataTables_info").show();
            }
            },

            createdRow: function(row, data, dataIndex) {

            // $(row).find('td').each(function(i, e) {

            //     $(e).attr('data-th', theader[i]);
                
            // });
            },
            drawCallback: function() {

            },

        });
        var oTable = $('#cmsTable1').DataTable({

          initComplete: function(settings) {

            var info = this.api().page.info();
            var api = this.api();

            if (info.pages > 1) {

                $(".dataTables_paginate").show();
            } else {
                $(".dataTables_paginate").hide();

            }

            var searchInput = $('<input type="number" min="1" step="1" class="page-search-input" placeholder="Search pages...">');
            $(".col-sm-7").append(searchInput);

            if (info.pages > 1) {

                searchInput.on('input', function() {

                    var searchValue = $(this).val().toLowerCase();

                    var pageNum = searchValue - 1;

                    api.page(pageNum).draw('page');
                });
            }


            if (info.recordsTotal == 0) {

                $(".dataTables_info").hide();
            } else {
                $(".dataTables_info").show();
            }
            },

            createdRow: function(row, data, dataIndex) {

            // $(row).find('td').each(function(i, e) {

            //     $(e).attr('data-th', theader[i]);
                
            // });
            },
            drawCallback: function() {

            },

        });
        
        // Add event listener for opening and closing details
       // $('#cmsTable tbody').on('click', 'td.details-control', function () {
        $('.openTable').on('click',  function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var id = $(tr).attr('data-id');
            var from = $(tr).attr('data-from');
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                var resp = getRowDetails(id, from, row,tr);
            }
        });
    });
    
</script>



<script>
$('#del_date').datepicker({
    dateFormat:'yy-mm-dd',
    minDate: 0
});
        </script>
@endsection
<style>
.ui-datepicker 
{
    z-index: 1600 !important; /* has to be larger than 1050 */
}
</style>