

@extends('staff/layouts.app')

@section('title', 'Manage Staff Sales')

@section('content')

<style>
  .custom .modal-sm {
      max-width: 280px; 
  }

  .custom .modal-dialog {
      position: fixed;
      top: 10px;
      right: 10px;
      margin: 0;
      z-index: 1050;
  }

  .custom .modal-content {
      border-radius: 10px;
  }

  .custom-modal-header {
      padding: 10px 15px;
      border-bottom-color: #f4f4f4;
      background: #00a65a !important;
      color: #fff;
  }

  .custom .modal-body {
      padding: 15px;
      font-size: larger;
  }
</style>

<section class="content-header preventclick">
      <h1>
        Manage Customer Location
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> Manage Customer Location</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

                <div class="col-lg-12 margin-tb">


                {{-- <div class="pull-left">

                        <a class="add-button " href="{{ route('staff.staff.create') }}">Add Staff Sales</a>

                    </div> --}}

                </div>

    </div>

          


            <div class="box-body">


              <form name="dataForm" id="dataForm" method="post" action="#" >

              @csrf

              <table id="" class="table table-bordered table-striped data-">
                <thead>

                  <tr>
                
                    <th>Sales</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>District</th>
                    <th>Taluk</th>
                    <th>Customer</th>
                    <th>Brand</th>
                    <th>Action</th>
                  
                  </tr>

                </thead>

                @foreach($staffs as $stfgrp => $stflst)

                    <tbody>

                      <tr>
                        <th colspan="8">
                          <strong>
                            {{$stfgrp}}
                          </strong>
                        </th>
                      </tr>

                    </tbody>

                    @foreach ($stflst as $staff)

                      <tbody id="staff-group-{{$staff->id}}" data-staff="{{$staff->id}}" class="collapse-body">

                        <tr class="staff-main" data-staff="{{$staff->id}}" >

                            <th>  {{$staff->name}} </th>
                            
                            <th>
                              <select name="country" id="country_staff{{ $staff->id }}" class="form-control {{ optional($staff->customerlocationstaff)->country_id }}" data-staff="{{ $staff->id }}" onchange="FilterState(this)">

                                <option value="" id="country{{ $staff->id }}">Select Country</option>

                                @foreach($country as $item)
  
                                  <option value="{{ $item->id }}" {{ $item->id == optional($staff->customerlocationstaff)->country_id ? 'selected' : '' }}>{{ $item->name }} </option>
  
                                @endforeach
  
                            </select>

                          </th>

                          <th  data-staff="{{ $staff->id }}">

                            <select name="state" id="staff_state{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control"  onchange="FilterDistrict(this)">

                              <option value="" id="state{{ $staff->id }}" >Select State</option>

                                @if(!empty(optional($staff->customerlocationstaff)->state_id))

                                  @foreach($state as $item)
      
                                  <option value="{{ $item->id }}" {{ $item->id == optional($staff->customerlocationstaff)->state_id ? 'selected' : '' }}>{{ $item->name }} </option>

                                  @endforeach

                                @endif

                            </select>
                            
                          </th>

                          <th>

                              <select name="district[]" id="staff_district{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control district_multi_select"  onchange="FilterTaluk(this)" multiple="multiple">

                                <option value="selected" id="district{{ $staff->id }}" >Select All District</option>

                                <option value="unselected" id="" >UnSelect All District</option>


                                @if(!empty(optional($staff->customerlocationstaff)->district_id))

                                    @foreach($district->where('state_id',optional($staff->customerlocationstaff)->state_id) as $item)

                                        @php
                                            $districtIds = json_decode(optional($staff->customerlocationstaff)->district_id, true);
                                        @endphp
          
                                      <option value="{{ $item->id }}"  {{ is_array($districtIds) && in_array($item->id, $districtIds) ? 'selected' : '' }} >{{ $item->name }} </option>

                                    @endforeach
                                @endif

                              </select>

                          </th>

                          <th> 

                            <select name="taluk[]" id="staff_taluk{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control taluk_multi_select"  onchange="FilterCustomer(this)" multiple="multiple">

                              <option value="selected" id="taluk{{ $staff->id }}" >Select Taluk</option>

                              <option value="unselected" id="" >UnSelect All Taluk</option>

                               @if(!empty(optional($staff->customerlocationstaff)->taluk_id))

                               @php
                                    $districtids = json_decode(optional($staff->customerlocationstaff)->district_id, true);
                                @endphp

                                  @foreach($taluk->whereIn('district_id',$districtids) as $item)

                                      @php
                                          $talukids = json_decode(optional($staff->customerlocationstaff)->taluk_id, true);
                                      @endphp

                                    <option value="{{ $item->id }}"  {{ is_array($talukids) && in_array($item->id, $talukids) ? 'selected' : '' }} >{{ $item->name }} </option>

                                  @endforeach

                                @endif

                            </select>

                          </th>

                          <th>

                            <select name="customer[]" id="staff_customer{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control customer_multi_select"  multiple="multiple" onchange="BrandFilter(this)">

                              <option value="" id="customer{{ $staff->id }}" >Select Customer</option>

                                @if(!empty(optional($staff->customerlocationstaff)->customer_id))

                                  @php
                                      $talukids = json_decode(optional($staff->customerlocationstaff)->taluk_id, true);
                                  @endphp

                                  @foreach($customer->whereIn('taluk_id',$talukids)  as $item)

                                      @php
                                          $cutomers = json_decode(optional($staff->customerlocationstaff)->customer_id, true);
                                      @endphp

                                    <option value="{{ $item->id }}"  {{ is_array($cutomers) && in_array($item->id, $cutomers) ? 'selected' : '' }} >{{ $item->business_name }} </option>

                                  @endforeach

                                @endif

                            </select>

                          </th>

                          <th>
                              <select name="brand[]" id="staff_brand{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control brand_multi_select" onchange="ShowSave(this)" multiple="multiple">

                                <option value="" id="brand{{ $staff->id }}">Select Brand</option>

                                  @if(!empty(optional($staff->customerlocationstaff)->brand_id))

                                    @foreach($brands as $item)

                                      @php
                                          $brandids = json_decode(optional($staff->customerlocationstaff)->brand_id, true);
                                      @endphp

                                      <option value="{{ $item->id }}"  {{ is_array($brandids) && in_array($item->id, $brandids) ? 'selected' : '' }} > {{ $item->name }} </option>

                                    @endforeach

                                  @endif

                              </select>

                          </th>

                          <th>
                            
                            <button style="display:none;" type="button" class="savebtn{{ $staff->id }}"  class="btn btn-success" data-staff="{{ $staff->id }}" onclick="SubmitLocation(this)">Save</button>

                            <button style="display:none;" type="button" class="savebtn{{ $staff->id }}"  class="btn btn-danger" data-staff="{{ $staff->id }}" onclick="CancelAjaxRow(this)">Cancel</button>

                          </th>


                        </tr> 



                      </tbody>

                    @endforeach

             
                @endforeach 

              </table>
            </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>



    <div class="modal fade" id="myModal" role="dialog">
      <div class="custom modal-dialog modal-sm" style="position: fixed; top: 10px; right: 10px; margin: 0;">
          <!-- Modal content-->
          <div class="custom modal-content">
              <div class="custom-modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                  <h4 class="custom modal-title">Success</h4>
              </div>
              <div class="custom modal-body">
                  <p id="txtMsg"></p>
              </div>
              <div class="custom modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>


@endsection

@section('scripts')

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script type="text/javascript">



        function popup(msg){
            document.getElementById("txtMsg").innerHTML = msg;
            $("#myModal").modal();
        }
        
    $(document).ready(function() {

      $('.taluk_multi_select').multiselect({
          enableFiltering: true,
       
      });

      $('.customer_multi_select').multiselect({
     
          enableFiltering: true,
      
      });

       $('.district_multi_select').multiselect({
            enableFiltering: true,
           
        });

        $('.brand_multi_select').multiselect({
          
            enableFiltering: true,
      
        });


        $('input[type="checkbox"][value="unselected"]').each(function() {
            
            $(this).css('visibility', 'hidden');
            
          $(this).closest('label.checkbox').css('visibility', 'hidden');
        });

    });

//     function CancelRow(element) {


//     var staff_id = $(element).attr('data-staff');

//     // // var country_save = $(`#country_save${staff_id}`).val();
//     // // $(`#country_staff${staff_id}`).val(country_save).prop('selected', true);

//     // // var state_save = $(`#state_save${staff_id}`).val();

//     // // $(`#staff_state${staff_id}`).val(state_save).prop('selected', true);

//     // // const district_save = $(`#district_save${staff_id}`).val();
//     // // const taluk_save = $(`#taluk_save${staff_id}`).val();
//     // // const customer_save = $(`#customer_save${staff_id}`).val();
//     // // const brand_save = $(`#brand_save${staff_id}`).val();

//     // function reinitializeMultiselect(selector, values) {

//     //     const element = $(selector);


//     //     console.log('Before destroying multiselect:', element);
//     //     element.multiselect('destroy');

//     //     console.log('After destroying multiselect:', element);
        
//     //     // Reinitialize the multiselect after destroying
//     //     element.multiselect({
//     //         enableFiltering: true,
//     //     });

//     //     // Ensure the multiselect is fully reinitialized
//     //     setTimeout(function() {
//     //         // If values are provided, parse the JSON and select them
//     //         if (values) {
//     //             const parsedValues = JSON.parse(values);

//     //             if (Array.isArray(parsedValues)) {
//     //                 console.log('Values:', parsedValues);

//     //                 // Deselect all values before selecting the new ones
//     //                 element.multiselect('deselectAll');

//     //                 // Select only the values passed in the array
//     //                 parsedValues.forEach(value => {
//     //                     element.multiselect('select', value);
//     //                 });
//     //             } else {
//     //                 console.log('Values are not a valid array');
//     //             }
//     //         }
//     //     }, 100); 
//     // }




//     // reinitializeMultiselect(`#staff_district${staff_id}`, district_save);

//     // // reinitializeMultiselect(`#staff_taluk${staff_id}`, taluk_save);

//     // // reinitializeMultiselect(`#staff_customer${staff_id}`, customer_save);

//     // // reinitializeMultiselect(`#staff_brand${staff_id}`, brand_save);

//     // // $('input[type="checkbox"][value="unselected"]').each(function () {
//     // //     $(this).css('visibility', 'hidden');
//     // //     $(this).closest('label.checkbox').css('visibility', 'hidden');
//     // // });

//     // console.log('Complete execution');
// }

  function CancelAjaxRow(element)
  {

    var staff_id = $(element).attr('data-staff');

    var url = "{{ route('staff.undo_action') }}";

      $.ajax({

          type: "GET",

          cache: false,
          url: url,
          data: {
              staff_id: staff_id,
          },

          success: function(res) {

            if (res.undohtml) {
                $(`#staff-group-${staff_id}`).html(res.undohtml);

                $('.taluk_multi_select').multiselect({

                    enableFiltering: true,
          
                });

            $('.customer_multi_select').multiselect({
          
                enableFiltering: true,
            
            });

            $('.district_multi_select').multiselect({
                  enableFiltering: true,
                
              });

              $('.brand_multi_select').multiselect({
                
                  enableFiltering: true,
            
              });


              $('input[type="checkbox"][value="unselected"]').each(function() {
                  
                  $(this).css('visibility', 'hidden');
                  
                $(this).closest('label.checkbox').css('visibility', 'hidden');
              });
              
            }
        }

      });

  }


  function ShowSave(element)
  {
      var staff_id = $(element).attr('data-staff');

      var brand_id =  $(element).val();

      if (brand_id && brand_id.includes('selected')  && !brand_id.includes('unselected')) {

        $(`#staff_brand${staff_id}`).multiselect('selectAll');

      }

      if (brand_id && !brand_id.includes('selected')  && brand_id.includes('unselected')) {
          
        $(`#staff_brand${staff_id}`).multiselect('deselectAll');

      }

      $(`.savebtn${staff_id}`).show();
  }

  function SubmitLocation(element)
  {
      var staff_id = $(element).attr('data-staff');

      var country_id = $(`#country_staff${staff_id}`).val();

      var state_id = $(`#staff_state${staff_id}`).val();

      var district_id = $(`#staff_district${staff_id}`).val();

      var taluk_id = $(`#staff_taluk${staff_id}`).val();

      var customer_id = $(`#staff_customer${staff_id}`).val();

      var brand_id = $(`#staff_brand${staff_id}`).val();

      var url = "{{ route('staff.cusomer_loacation_save') }}";

        $.ajax({

          type: "POST",
          cache: false,
          url: url,
          data: {
              staff_id: staff_id,
              country_id : country_id,
              state_id : state_id,
              district_id : district_id,
              taluk_id : taluk_id,
              customer_id : customer_id,
              brand_id : brand_id,
          },

          success: function(res) {

            popup('Customer Location Successfully Updated');
          }
      });

  }

  function BrandFilter(element)
  {

    var staff_id = $(element).attr('data-staff');

    var customer_id_temp =  $(element).val();

    if (customer_id_temp && customer_id_temp.includes('selected')  && !customer_id_temp.includes('unselected')) {

      $(`#staff_customer${staff_id}`).multiselect('selectAll');

    }

    if (customer_id_temp && !customer_id_temp.includes('selected')  && customer_id_temp.includes('unselected')) {
        
      $(`#staff_customer${staff_id}`).multiselect('deselectAll');

    }

    $(`.savebtn${staff_id}`).show();

    var customer_id =  $(element).val();

    var url ="{{ route('staff.staff_location_brand') }}";

      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data: {
              customer_id: customer_id,
              staff_id : staff_id,
          },

          success: function(res) {
            
            var staff_id = res.staff_id;

            states_val = '';

            for (var i = 0; i < res.brand.length; i++) {

                var selected = '';

                states_val += '<option value="' + res.brand[i]["id"] + '" ' + selected + ' >' + res.brand[i]["name" ]  + '</option>';   
            }


              $(`#staff_brand${staff_id}`).html("");

              $(`#staff_brand${staff_id}`).append('<option value="selected"> Select All Brand</option>');

              $(`#staff_brand${staff_id}`).append('<option value="unselected" id="" >UnSelect All Brand</option>');

              $(`#staff_brand${staff_id}`).append(states_val);

              $(`#staff_brand${staff_id}`).multiselect('destroy');

              $(`#staff_brand${staff_id}`).multiselect({

                  enableFiltering: true,
              });

              const selectedStaffsrep = $(`#staff_brand${staff_id}`).val();
              if (selectedStaffsrep) {
                  selectedStaffsrep.forEach(value => {
                      $(`#staff_brand${staff_id}`).multiselect('select', value);
                  });
              }

              $('input[type="checkbox"][value="unselected"]').each(function() {

                $(this).css('visibility', 'hidden');
                  
                $(this).closest('label.checkbox').css('visibility', 'hidden');
              });

          }
      });

  }


  function FilterDistrict(element)

  {
    var state_id =  $(element).val();

    var staff_id = $(element).attr('data-staff');

    var url ="{{ route('staff.staff_location_district') }}";

    $(`.savebtn${staff_id}`).show();

    $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data: {
            state_id: state_id,
            staff_id : staff_id,
        },

        success: function(res) {
          
          var staff_id = res.staff_id;

          var states_val = '';

          for (var i = 0; i < res.state.length; i++) {

              var selected = '';

              states_val += '<option value="' + res.state[i]["id"] + '" ' + selected + ' >' + res.state[i]["name" ]  + '</option>';   
          }

            
            $(`#staff_district${staff_id}`).html("");

            $(`#staff_district${staff_id}`).append('<option value="selected"> Select All District</option>');

            $(`#staff_district${staff_id}`).append('<option value="unselected" id="" >UnSelect All District</option>');

            $(`#staff_district${staff_id}`).append(states_val);

            $(`#staff_district${staff_id}`).multiselect('destroy');

            console.log(staff_id,'drop distect');

            $(`#staff_district${staff_id}`).multiselect({

                enableFiltering: true,
            });

            const selectedStaffsrep = $(`#staff_district${staff_id}`).val();
              if (selectedStaffsrep) {
                  selectedStaffsrep.forEach(value => {
                      $(`#staff_district${staff_id}`).multiselect('select', value);
                  });
              }

            $('input[type="checkbox"][value="unselected"]').each(function() {
            
              $(this).css('visibility', 'hidden');
                
              $(this).closest('label.checkbox').css('visibility', 'hidden');
            });

        }
    });
    
  }



  function FilterCustomer(element)

    {

      var staff_id = $(element).attr('data-staff');

      var taluk_id_temp =  $(element).val();

      if (taluk_id_temp && taluk_id_temp.includes('selected')  && !taluk_id_temp.includes('unselected')) {
      
      $(`#staff_taluk${staff_id}`).multiselect('selectAll');

      }

      if (taluk_id_temp && !taluk_id_temp.includes('selected')  && taluk_id_temp.includes('unselected')) {
          
        $(`#staff_taluk${staff_id}`).multiselect('deselectAll');

      }

      var taluk_id =  $(element).val();

      $(`.savebtn${staff_id}`).show();


      var url ="{{ route('staff.staff_location_customer') }}";

      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data: {
              taluk_id: taluk_id,
              staff_id : staff_id,
          },

          success: function(res) {
            
            var staff_id = res.staff_id;

            var states_val = '';

            for (var i = 0; i < res.customer.length; i++) {

                var selected = '';

                states_val += '<option value="' + res.customer[i]["id"] + '" ' + selected + ' >' + res.customer[i]["business_name" ]  + '</option>';   
            }

              $(`#staff_customer${staff_id}`).html("");

              $(`#staff_customer${staff_id}`).append('<option value="selected"> Select All Customer</option>');

              $(`#staff_customer${staff_id}`).append('<option value="unselected" id="" >UnSelect All Customer</option>');

              $(`#staff_customer${staff_id}`).append(states_val);

              $(`#staff_customer${staff_id}`).multiselect('destroy');

              $(`#staff_customer${staff_id}`).multiselect({

                  enableFiltering: true,
              });

              const selectedStaffsrep = $(`#staff_customer${staff_id}`).val();
              if (selectedStaffsrep) {
                  selectedStaffsrep.forEach(value => {
                      $(`#staff_customer${staff_id}`).multiselect('select', value);
                  });
              }

              $('input[type="checkbox"][value="unselected"]').each(function() {

                $(this).css('visibility', 'hidden');
                  
                $(this).closest('label.checkbox').css('visibility', 'hidden');
              });


          }
      });
      
    }

  

  function FilterTaluk(element)

    {
      var district_id_temp =  $(element).val();

      var staff_id = $(element).attr('data-staff');

      if (district_id_temp && district_id_temp.includes('selected')  && !district_id_temp.includes('unselected')) {
      
          $(`#staff_district${staff_id}`).multiselect('selectAll');

      }

      if (district_id_temp && !district_id_temp.includes('selected')  && district_id_temp.includes('unselected')) {
          
        $(`#staff_district${staff_id}`).multiselect('deselectAll');

      }


      var district_id =  $(element).val();
      
      $(`.savebtn${staff_id}`).show();


      var url ="{{ route('staff.staff_location_taluk') }}";

      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data: {
              district_id: district_id,
              staff_id : staff_id,
          },

          success: function(res) {
            
            var staff_id = res.staff_id;

            states_val = '';

            for (var i = 0; i < res.taluk.length; i++) {

                var selected = '';

                states_val += '<option value="' + res.taluk[i]["id"] + '" ' + selected + ' >' + res.taluk[i]["name" ]  + '</option>';   
            }

              $(`#staff_taluk${staff_id}`).html(states_val);

              $(`#staff_taluk${staff_id}`).html("");

              $(`#staff_taluk${staff_id}`).append('<option value="selected"> Select All Taluk</option>');

              $(`#staff_taluk${staff_id}`).append('<option value="unselected" id="" >UnSelect All Taluk</option>');

              $(`#staff_taluk${staff_id}`).append(states_val);

              $(`#staff_taluk${staff_id}`).multiselect('destroy');

              $(`#staff_taluk${staff_id}`).multiselect({

                  enableFiltering: true,
              });

              const selectedStaffsrep = $(`#staff_taluk${staff_id}`).val();
              if (selectedStaffsrep) {
                  selectedStaffsrep.forEach(value => {
                      $(`#staff_taluk${staff_id}`).multiselect('select', value);
                  });
              }

              $('input[type="checkbox"][value="unselected"]').each(function() {
            
                  $(this).css('visibility', 'hidden');
                  
                $(this).closest('label.checkbox').css('visibility', 'hidden');
              });


          }
      });
      
    }


    function FilterState(element)

    {
      var state_id =  $(element).val();

      var staff_id = $(element).attr('data-staff');

      $(`.savebtn${staff_id}`).show();

      var url ="{{ route('staff.staff_location_state') }}";

      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data: {
              state_id: state_id,
              staff_id : staff_id,
          },

          success: function(res) {
            
            var staff_id = res.staff_id;

            states_val = '';

            states_val += '<option value="">Select State</option>';

            for (var i = 0; i < res.state.length; i++) {

                var selected = '';

                states_val += '<option value="' + res.state[i]["id"] + '" ' + selected + ' >' + res.state[i]["name" ]  + '</option>';   
            }

              $(`#staff_state${staff_id}`).html(states_val);

          }
      });
      
    }

// $(function(){

// $('#staffSalesTable').DataTable({
//     processing:true,
//     serverSide:true,
//     order: [[7, 'desc']],
//     columnDefs: [
//       { "width": "150px", "targets": 7 }
//     ],
//     ajax:{
//       url:"{{ route('admin.staff.sales.index') }}",
//     },
//     drawCallback:function (settings) {
//       $('.togglebutton').bootstrapToggle();
//     },
//     columns:[
//       {
//           "data": 'DT_RowIndex',
//           orderable: false, 
//           searchable: false
//       },
//       {
//         data:'engneer',
//         name:'engineer_name',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'customer',
//         name:'customer_name',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'carearea',
//         name:'care_area',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'contact_name',
//         name:'contact_person_name',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'contact_phone',
//         name:'contact_person_phone',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'contact_designation',
//         name:'contact_person_designation',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'created_at',
//         name:'created_at',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'approvestatus',
//         name:'status',
//         orderable: true, 
//         searchable: true,
//       },
//       {
//         data:'action',
//         name:'action',
//         orderable:false
//       }
//     ]
    

//   });
// })
</script>


@endsection
