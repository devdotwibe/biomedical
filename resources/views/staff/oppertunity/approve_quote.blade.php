

@extends('staff/layouts.app')

@section('title', ' Approve Opportunity')

@section('content')


<section class="content-header">
      <h1>
      Approve Opportunity
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Products</li>
      </ol>
</section>

    <!-- Main content -->
<div class="se-pre-con1"></div>
<section class="content approve_quote_page">
      <div class="row">
        

        <div class="se-pre-con1"></div>

        <div class="col-md-12 rightside-menu">
          <div class="box box-primary">
              <div class="panel-body padding-10">
                  
              </div>

              <div class="alert alert-success alert-block fade in alert-dismissible" style="display:none;" id="approve_message">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Quote Approved </strong>
              </div>

              <table id="cmsTable" class="table table-bordered table-striped data-" onmousedown="return false" onselectstart="return false">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Engineer</th>
                  <th>Customer</th>
                  <!-- <th>District</th> -->
                  <th>Quote No</th>
                  <th>Created Date</th>
                  <th>Amount</th>
                  <th>Opportunity Name</th>
                 
                
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
              
                </tbody>
              </table>
           </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="modal fade inprogress-popup" id="mailModal"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" >
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Mail</h4>
            </div>
            <div class="modal-body" id="contain">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
          </div>
        </div>
      </div>

</section>


<div class="modal fade" id="termmodal"  role="dialog" aria-labelledby="term">
        <div class="modal-dialog" >
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" >Edit Terms</h4>
            </div>
            <div class="modal-body" >
            <div class="form-group col-md-6">
      <label>Terms</label>
      <textarea name="terms_condition" placeholder="Edit Terms" id="terms_condition" rows="3" class="form-control ays-ignore" required="" >
   
 
      
      </textarea>
       
    </div>
            </div>
            <div class="modal-footer">
            <input type="hidden" id="history_id" name="history_id">
              <button type="button" class="mdm-btn cancel-btn  " data-dismiss="modal">Close</button>
              <button type="button" class="mdm-btn submit-btn  " onclick="save_quote_terms()">Save</button>
              <span class="success_message" id="term_success" style="display: none;color:green">Saved Successfully</span>
            </div>
          </div>
        </div>
      </div>

@endsection

@section('scripts')
<script type="text/javascript">

function save_quote_terms()
{

  var url = APP_URL+'/staff/save_quote_terms';
  var id=$("#history_id").val();
  var terms_condition= CKEDITOR.instances['terms_condition'].getData();
  
    $.ajax({
     url:url,
     method:'POST',
     data:{id:id,terms_condition:terms_condition},
     success:function()
     {
      $("#term_success").show();
     // location.reload();
     $('#cmsTable').DataTable().ajax.reload();


     }
     
    });

}
      

  $("#cmsTable").on("click", ".edit_terms", function(e) {
$("#termmodal").modal("show");
$("#term_success").hide();
var id=$(this).attr("data-id");
$("#history_id").val(id);

var desc=$(this).attr("data-desc");

$('#terms_condition').val(desc);
//CKEDITOR.replace('#terms_condition'); // ADD THIS
  //  $('#terms_condition').ckeditor();


      if (CKEDITOR.instances['terms_condition']) {
            // If it exists, reset it
            CKEDITOR.instances['terms_condition'].setData(desc);
            
        } else {

          editorInstance=CKEDITOR.replace('terms_condition',
             {
                        customConfig: '{{ asset('AdminLTE/bower_components/ckeditor/custom/config.js') }}',
                        height: '300',
                        width:'885',
                       // toolbar: 'Basic'
                });
                
                CKEDITOR.instances.editorInstance.setData(desc)

        }
             
});

  
    $("#cmsTable").on("click", ".approve", function(e) {
          var id = $(this).attr('data-id');
         
          var element = $(this);

          var view_id = 'view_btn_'+id; 

          var url = APP_URL+'/staff/approve_quote_history';
   
          $('#approve_message').show();

          $.ajax({

              url:url,
              method:'POST',
              data:{id:id},
              success:function()
              {
                $(element).hide();

                $('#'+view_id).show();

                $('#approve_message').hide();

                // location.reload(true);
              }
          
          });

        });

    jQuery(document).ready(function() {
       


        // Add event listener for opening and closing details
      // $('#cmsTable tbody').on('click', 'td.details-control', function () {
        
      
    /*  $('.openTable').on('click',  function () {alert()
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
        });*/

        // Sortable rows


    });

  </script>

  <script>
  $(document).ready(function(){
    $("#cmsTable").on("click", ".viewer", function(e) {
   
  
        $(".se-pre-con1").fadeIn();
      var data=$(this).data('id');
     // alert(data);
      $.post("{{url('staff/quote_send')}}", {id: data, "_token": "{{ csrf_token() }}"}, function(result,status){
        
        $('#contain').html(result);
        $('#mailModal').modal();
        $(".se-pre-con1").fadeOut();
      });
      e.preventDefault();
    });

  });

  /*
$( "#save_mail" ).click(function() {
  var contact_value=$("#contact").val();
  if(contact_value=="")
  {
    alert( "Please select any contact person" );
  }else{alert(1)
$("#contactformedit").submit();
  }
  
});
*/
  </script>

  <script>
$(document).ready(function(){
 
 /* $( "#save_mail" ).click(function() {
  var contact_value=$("#contact").val();
  if(contact_value=="")
  {
    alert( "Please select any contact person" );
  }else{alert(1)
$("#contactformedit").submit();
  }
  
});*/

 $('#btn_quote').click(function(){

   if ($('#hide_tech').is(":checked"))
    {
      var hide_tech="Y";
    }
    else{
      var hide_tech="N";
    }

   if ($('#show_total').is(":checked"))
    {
      var show_total="Y";
    }
    else{
      var show_total="N";
    }

  

  
  
   var id = [];
   $('.dataCheck:checked').each(function(i){
      //id[i] = $(this).data(id);
      id.push($(this).data('prid'));
   });
   
   
   if(id.length === 0) //tell you if the array is empty
   {
    alert("Please Select atleast one product");
   }
   else
   {
var generate_date=$("#generate_date").val();
    var url   = APP_URL+'/staff/generate_quote';
    var op_id = $('#cmsTable').data('id');
    $.ajax({
     url:url,
     method:'POST',
     data:{id:id, op_id:op_id,hide_tech:hide_tech,show_total:show_total,generate_date:generate_date},
     success:function()
     {
       /*$('#btn_quote').hide();
       $('#btn_preview').show();
       $('#btn_send').show();*/
       location.reload(true);
     }
     
    });
   }
   
 });
 
});
</script>

<script type="text/javascript">
  $(document).on('click', '#select_all', function() {
      $(".dataCheck").prop("checked", this.checked);
      $("#select_count").html($("input.dataCheck:checked").length+" Selected");
  });
  $(document).on('click', '.dataCheck', function() {
      if ($('.dataCheck:checked').length == $('.dataCheck').length) {
      $('#select_all').prop('checked', true);
      } else {
      $('#select_all').prop('checked', false);
      }
      $("#select_count").html($("input.dataCheck:checked").length+" Selected");
  });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script>

jQuery(document).ready(function() {
     
     var oTable = $('#cmsTable').DataTable({
         processing:true,
         serverSide:true,
        
         ajax:{
           url:"{{ route('staff.approve_quote') }}",
         },
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
         columns:[
           {
               "data": 'DT_RowIndex',
               orderable: false, 
               searchable: false
           },
        
           
           {
             data:'name',
             name: 'staff.name',
             orderable: true, 
              searchable: true,
           },
         
            {
             data:'user_name',
             name:'user_name',
             orderable: true, 
              searchable: true,
           },
          //     {
          //    data:'district',
          //    name:'district',
          //    orderable: true, 
          //      searchable: true,
          //  },

           {
             data:'quote_no',
             name:'quote_reference_no',
             orderable: true, 
               searchable: true,
           },
            {
              data:'created_at_time',
             name:'created_at',
             orderable: true, 
               searchable: true,
           },
         {
             data:'opper_amount',
             name:'quote_amount',
             orderable: true, 
               searchable: true,
           },

           {
             data:'oppertunity_name',
             name:'oppertunities.oppertunity_name',
             orderable: true, 
               searchable: true,
           },
         
       
           {
             data:'action',
             name:'action',
             orderable:false
           }
         ]
          

       });

       $('#cmsTable').on('click', '.deleteItem', function () {
    
        var id= $(this).attr('id');
      var url = $(this).attr('href');
      $('#btnDeleteItem').attr('data-id', id);
      $('#btnDeleteItem').attr('data-href', url);
      $('#modalDelete').modal();        
      return false;

  });


   });
   


$(document).ready(function() {

 
     
    $('#contact').multiselect({
      nonSelectedText: 'Select Contact',
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      buttonWidth:'400px'
     });
   
});
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('#generate_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         //  minDate: 0  
        });
        </script>
  
@endsection
