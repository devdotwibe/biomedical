@extends('layouts.appmasterspace')
<?php
$title       = 'Create Service';
$description = 'Create Service';
$keywords    = 'Create Service';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<script src="{{ asset('js/custom.js') }}"></script>
<header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white custom-nav">
            <!-- <i class="fa fa-bars mobile-menu"></i> -->
            <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="https://biomedicalengineeringcompany.com/images/logo.png"></a>
            <div class="collapse navbar-collapse" id="user-img">
                @include('marketspace/navbar')
                  </div>
        </nav>
    </header>


    <div class="container-fluid height100">
        <div class="row dashboard-row leftnone">

            <main class="col-md-10" id="main">
                <section class="content-wrap bg-none">
                    <div class="content-col1">
                

                       <div class="card">
                        <div class="card-top-section">
                      
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-card">
                    <div class="tab-card-header">
        
        
                        <h5>	Create Service</h5>
                    </div
              
                    
    <div class="tab-card-form">
                    
                <form autocomplete="off" role="form" name="frm_request" id="frm_request" method="post"  enctype="multipart/form-data" >
                   @csrf
                        
                        <div class="form">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Select Machine <a href="{{ route('marketspace-ib') }}" id="add-reference">Add New</a></label>
                                        <select  name="product_id" id="product_id" class="form-control" onchange="change_product(this.value)">
                                            <option value="">Select Machine</option>
                                            @if(count($ib)>0)
                                            @foreach($ib as $val)
                                            <option value="{{ $val->equipment_id }}" @if($id==$val->equipment_id) selected @endif>{{ $val->ibProduct->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="pannumber">Service Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                                     
                              </div>
    
                              <div class="form-group col-md-6">
                                <label for="start_date">Select Type</label>
                                    <select  name="service_type" id="service_type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="Break Down">Break Down</option>
                                        <option value="Preventive Maintance">Preventive Maintance</option>
                                    </select>
                            </div>
                   
                  <div class="form-group col-md-3">
                                    <label for="start_date">Select Service Date</label>
                                        <input type="text" name="start_date" id="start_date" class="form-control">
                                </div>
                          
                          <div class="form-group col-md-3">
                            <label for="start_date">Select Service Time</label>
                                <input type="time" name="start_time" id="start_time" class="form-control">
                        </div>
                    </div>
    
                  </div>
    
    
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">Description</label>
                                <textarea class="form-control" placeholder="Description" name="description" id="description"></textarea>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="form-check col-md-12">
                            <label class="control-label">Criteria preference</label>
                            <p>Drag and drop criteria based on higest to lowest priority</p>
                        </div>
    
                        <div class="form-check col-md-12">
                        <ul id="sortable1" class="connectedSortable">
                            <li class="ui-state-default"><input type="hidden" name="preference[]" value="Skills"> Skills </li>
                            <li class="ui-state-default"><input type="hidden" name="preference[]" value="Quick Availability">Quick Availability</li>
                            <li class="ui-state-default"><input type="hidden" name="preference[]" value="Lowest Rate">Lowest Rate</li>
                            <li class="ui-state-default"><input type="hidden" name="preference[]" value="Verified">Verified</li>
                    
                          </ul>
                        </div>
    
                        <div class="form-check col-md-3">
                            
                            
                            <label class="form-check-label" for="flexCheckDefault">
                                  
                                 <span  style="display:none;" id="skill_notavl">Skills <p style="color:red;">Not available</p></span> 
                                 <span style="display:none;" id="skill_avl">Skills <p style="color:green;">Available</p></span> 
                            </label>
                          </div>
                          <div class="form-check col-md-3">
                            
                            <label class="form-check-label" for="flexCheckChecked">
                                
                                <span style="display:none;"  id="date_notavl">Quick Availability  <p style="color:red;">Not available</p> </span>
                                <span style="display:none;" id="date_avl">Quick Availability <p style="color:green;">Available</p> </span>
                            </label>
                          </div>
                       
                        </div>
                     
                  
                            
                            <div class="card-savebtn">
                                <div class="loader_sec_req" style="display:none;">
                                    <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                                    
                                    <button  type="button" class="btn btn-default" id="create-request">Submit Request</button>
                                    
                                </div>
                                </form>
                                
                        </div>
                    </div>
    
                </div>
                            </div>
    
                        
                           
                          </div>
                        {{-- ----------------------------- --}}
                    </div>
                    
                </section>
                <div class="right-side-bar">
                    @include('marketspace/right-sidebar')
                </div>
        </div>
    </div>



       
<div class="modal fade" id="successmodal_req_save" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
					
                    <div class="modal-body">
                       
						<div class="thank-you-pop">
							<img src="{{ asset('images/accepted-icon.svg') }}" alt="" width="100" height="100">
							<p>Service request saved successfully. A service staff assgined to request</p>
							<!-- <p>Your job accepted service staff will contact soon.</p> -->
							
 						</div>
                         
                    </div>
					
                </div>
            </div>
        </div>




@endsection
@section('scripts')

<style>
    #sortable1, #sortable2 {
      border: 1px solid #eee;
      width: 142px;
      min-height: 20px;
      list-style-type: none;
      margin: 0;
      padding: 5px 0 0 0;
      float: left;
      margin-right: 10px;
    }
    #sortable1 li, #sortable2 li {
      margin: 0 5px 5px 5px;
      padding: 5px;
      font-size: 1.2em;
      width: 120px;
    }
    </style>

<script>
change_product();
function change_product(){
    var product_id=$("#product_id").val();
    if(product_id>0){
    $.ajax({
          type: "POST",
          cache: false,
           url:'{{ route("check_servicereq_date") }}',
           headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data:'product_id='+product_id,
          success: function (data)
          {
           var  myArray =data.split("*");
          if(myArray[1]==0){
            $("#skill_notavl").show();
            $("#skill_avl").hide();
           
          }else{
            $("#skill_avl").show();
            $("#skill_notavl").hide();
          }
          if(myArray[2]==0){
            $("#date_notavl").show();
            $("#date_avl").hide();
            }else{
                $("#date_avl").show();
            $("#date_notavl").hide();
            }

            if(myArray[1]>0 && myArray[2]>0){
                $('#create-request').removeAttr("disabled");
            }else{
                $("#create-request").attr('disabled');
            }
            
           var datelist=jQuery.parseJSON(myArray[0]);
           
           $('#start_date').datepicker("destroy");
            $('#start_date').datepicker({
                dateFormat:'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                minDate: 0, 
                yearRange: "-0:+10",
                beforeShowDay: function(d) {
                    var dmy = "";
                    dmy += ("00" + d.getDate()).slice(-2) + "-";
                    dmy += ("00" + (d.getMonth() + 1)).slice(-2) + "-";
                    dmy += d.getFullYear();
                    if ($.inArray(dmy, datelist) >= 0) {
                        return [true, ""];
                    }
                    else {
                        return [false, ""];
                    }
                },
            // minDate: 0  
            });
            $('#start_date').datepicker("refresh");
           
          }
        });
    }
}

$( function() {
    $( "#sortable1" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
  } );
$('#successmodal_req_save').on('hidden.bs.modal', function () {
    location.href='{{ route("marketspace/allservicerequest") }}';
})
    
    function remove_contact_div(){
    $(".contact_sec").hide();
    $(".contact_checksec").show();
    $("#add_contact").val(0);
    $("#contact_person").val('');
}

function add_contact_div(){
    $(".contact_sec").show();
    $(".contact_checksec").hide();
    $("#add_contact").val(1);
}
function add_contact_div_hide(){
    $(".contact_sec").hide();
    $(".contact_checksec").hide();
    $("#add_contact").val(1);
}

function validate_contact()
{

 var form = $("#contactform");
form.validate({
 rules: {
    name: {
        required:true,
     },
     title: {
        required:true,
     },
    //  email: {
    //     required:true,
    //     email: true,
    //  },
     mobile: {
        required:true,
     },
     phone: {
        required:true,
     },
     password: {
        required:true,
     },
     department: {
        required:true,
     },
     designation: {
        required:true,
     },
     
     
     
 },
 messages: {
   
    name: {
         required:"Field is required!",
     },
     title: {
         required:"Field is required!",
     },
    //  email: {
    //      required:"Field is required!",
    //  },
     mobile: {
         required:"Field is required!",
     },
     phone: {
         required:"Field is required!",
     },
     password: {
         required:"Field is required!",
     },
     department: {
         required:"Field is required!",
     },
     designation: {
         required:"Field is required!",
     },
    
 }
}); 
if(form.valid() === true) {
  
  var name=$("#name").val();
  var title=$("#title").val();
  var last_name=$("#last_name").val();
  var mobile=$("#mobile").val();
  var whatsapp=$("#whatsapp").val();
  var email=$("#email").val();
  var phone=$("#phone").val();
  var department=$("#department").val();
  var designation=$("#designation").val();
  var user_id=$("#user_id").val();
  var password=$("#password").val();
  var contact_type=$("input[name='contact_type']:checked").val();

var formData = new FormData();
            
            var image_name1 = $('#image_name1').val();
              if(image_name1 != '') {    
              formData.append('image_name1',$("#image_name1")[0].files[0]);
              }
           var image_name2 = $('#image_name2').val();
            if(image_name2 != '') {    
              formData.append('image_name2',$("#image_name2")[0].files[0]);
            }
          formData.append('title',title);  
          formData.append('mobile',mobile);
          formData.append('whatsapp',whatsapp); 
          formData.append('last_name',last_name); 
          formData.append('contact_type',contact_type); 
          
          formData.append('name',name);
         
          formData.append('email',email);
          formData.append('phone',phone);
         
          
          formData.append('department',department);
          formData.append('designation',designation);
          formData.append('user_id',user_id);
          formData.append('password',password);

          formData.append('remark',$("#remark").val());
         
          
          $('.load-add').show();
         
          $.ajax({
          type: "POST",
          cache: false,
           url:'{{ route("add_contact_person") }}',
           headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
            },
          processData: false,
          contentType: false,
         // data:$("#contactform").serialize(),
         data:formData,
          success: function (data)
          {   $('.load-add').hide();
          $("#successshow").show();
          var response = JSON.parse(data);
      var html='<option value="">Select Authorised Person</option>';
      for (var i = 0; i < response.length; i++) {
        html +='<option value="'+response[i]["id"]+'">'+response[i]["name"]+'</option>';
      }
      
      $("#contact_person").html(html);
      $("#myModal").modal("hide");
          }
        }); 

}

}


    function contact(){
$("#myModal").modal("show");
    }
    

    $("#create-request").click(function () {

        var num_staff=$("#num_staff").val();
  var flag=0;
 if(num_staff==0)
 {
    $(".num_staff_error").show();
 
        

 }
 else{
     flag=1;
    $(".num_staff_error").hide();
 }

 var contact_check=$("input[name='auth_by_user']:checked").val();
 
 if(contact_check=="Y")
 {
    flag=0;
 }
 else{
    if (jQuery("#contact_person").val()=='') 
    {flag=1;
        jQuery("#contact_person_message").show();
    }
    else{flag=0;
        jQuery("#contact_person_message").hide();
    }
 }

 var form = $("#frm_request");
form.validate({
 rules: {
    product_id: {
        required:true,
     }, 
    title: {
        required:true,
     },    
     start_date: {
        required:true,
     },
     service_type: {
        required:true,
     },
     start_time: {
        required:true,
     },
     description: {
        required:true,
     },
    
 },
 messages: {
    product_id: {
         required:"Field is required!",
     },
    title: {
         required:"Field is required!",
     },
    start_date: {
         required:"Field is required!",
     },
     start_type: {
         required:"Field is required!",
     },
     start_time: {
         required:"Field is required!",
     },
     description: {
         required:"Field is required!",
     },
     
   
 }
}); 
if(form.valid() === true) {
  
 if(flag==0)
 {
    var product_id=$("#product_id").val();
    $(".loader_sec_req").show();
         $.ajax({
            url:'{{ route("save_service_request") }}',
        headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        type: 'post',
        data:$("#frm_request").serialize()+'&product_id='+product_id,
        
        success: function( data ) {
          
            $("#loader").hide();
            $("#successmodal_req_save").modal("show");

           /*
            $(".loader_sec_req").hide();
           var result = data.split('*');
            if(result[1]=="service"){
                var num='91'+result[0];
                $.ajax({
                type: "POST",
                headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
            url:'{{ route("send_sms_message") }}',
               
                data:"number="+num+'&type=service_request',
                  //  dataType: "json",
                 
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });
            }
            else{
                
               var num='91'+result[0];
                $.ajax({
                type: "POST",
                headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                data:"number="+num+'&type=service_request',
            
              url:'{{ route("send_sms_message") }}',
                  //  dataType: "json",
                   
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });
            }

            $("#loader").hide();
            $("#successmodal_req_save").modal("show");
            */
        }
        });
 }

 
   // $("#frm_request").submit();

}

}); 

function checkcount_null() {
    var num_staff = $("#num_staff").val();
    if (num_staff>0) {
        return true;
    } else {
        return false;

    }
  }

</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/multi/multi.min.css') }}" />
<script src="{{ asset('public/assets/multi/multi.min.js') }}"></script>


    <script>
    //       var select = document.getElementById("preference_select");
    // multi(select, {
    //     enable_search: true
    // });

        // $('#start_date').datepicker({
        //             //dateFormat:'yy-mm-dd',
        //             dateFormat:'yy-mm-dd',
        //             changeYear: true,
        //             yearRange: "1990:2040",
        //             minDate: 0  
        //         });
      
 var eve=<?php echo $json_encode_date;?>;


	var datelist = eve;
	/*
$('#start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
			changeMonth: true,
            changeYear: true,
			
			minDate: 0, 
			yearRange: "-0:+10",
		beforeShowDay: function(d) {
           
            var dmy = "";
            dmy += ("00" + d.getDate()).slice(-2) + "-";
            dmy += ("00" + (d.getMonth() + 1)).slice(-2) + "-";
            dmy += d.getFullYear();
            if ($.inArray(dmy, datelist) >= 0) {
                return [true, ""];
            }
            else {
                return [false, ""];
            }
        },
           // minDate: 0  
            
        });
*/


                $("#kb_drangeInput").on('input', function(){   

                    $(".kb_show_range").text($(this).val());
                    fetch_data()
                    let countRange = parseFloat($(".kb_show_range").text());

                    })

                    $("#kb_drangeInput1").on('input', function(){   

                    $(".kb_show_range1").text($(this).val());
                    fetch_data()
                    let countRange = parseFloat($(".kb_show_range1").text());

                    })

                    $("#start_date").on("change",function (){ 
                      fetch_data()
                    });

                    function fetch_data()
                    {
                        var search_time=$(".kb_show_range").text();
                        var search_price=$(".kb_show_range1").text();
                        var start_date=$("#start_date").val();
                        var product_id=$("#product_id").val();
                        $("#loader").show();
                        $.ajax({
                            url:'{{ route("search_request_service") }}',
                        headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                        type: 'post',
                        //dataType: "json",
                        data: {
                            search_time:search_time,
                            search_price:search_price,
                            start_date:start_date,
                            product_id:product_id,
                        },
                        success: function( data ) {
                            $("#loader").hide();
                            $("#staff_count").html(data);
                            $("#num_staff").val(data);
                            
                        }
                        });
                    }
    </script>
    <style>
.main-footer{display:none;}
</style>
@endsection
