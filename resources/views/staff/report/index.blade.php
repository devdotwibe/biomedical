@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

    <section class="content-header">
        <h1>
            Staff Report
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Staff Report</li>
        </ol>
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="service-index">
            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="period">Period (Calandar)</label>
                                        <select name="period" id="period" onchange="loaddata()" > 
                                            <option value="thisyear"> {{ Carbon\Carbon::now()->startOfYear()->format('F j') }} - {{ Carbon\Carbon::now()->endOfMonth()->format('F j') }}</option>
                                            <option value="thisyear">This Year</option>
                                            <option value="preyear">Prev. Year</option>
                                            <option value="preyear">Next Year</option>
                                            <option value="last_quarter">Last Quarter</option>
                                            <option value="this_quarter">This Quarter</option>
                                            <option value="next_quarter">Next Quarter</option>

                                            <option value="last_month">Last Month</option>
                                            <option value="this_month">This Month</option>
                                            <option value="next_month">Next Month</option>

                                            <option value="last_month_this">Last Month & This Month</option>
                                            <option value="this_month_next">This Month & Next Month</option>
                                            <option value="next_month_last">Last Month & Next Month</option>

                                            <option value="last_week">Last Week</option>
                                            <option value="this_week">This Week</option>
                                            <option value="next_week">Next Week</option>

                                            <option value="last_week_this">Last Week & This Week</option>
                                            <option value="this_week_next">This Week & Next Week</option>
                                            <option value="last_week_next">Last Week & Next Week</option>
                                           
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">Brand</label>
                                    <select id="brand_id" name="brand_id" class="form-control" multiple="multiple" onchange="FilterUpdate('brand')">
                                        <option value="">Select Brand</option>
            
                                        @foreach ($brand as $item)
                                            <option value="{{ $item->id }}" id="brand_id_{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
            
                                    </select>
            
                                </div>
                                
                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">Modality</label>
                                    <select id="modality" name="modality" class="form-control" multiple="multiple" onchange="FilterUpdate()">

                                        <option value="" id="select_modality">Select Modality</option>
            
                                       
                                    </select>
            
                                </div>
                                
                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">State</label>
                                    <select id="state_id" name="state_id" class="form-control"  onchange="FilterUpdate('state')">
                                        <option value="">Select State</option>
            
                                        @foreach ($states as $item)
                                            <option value="{{ $item->id }}" id="state_id_{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
            
                                    </select>
            
                                </div>

                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">District</label>
                                    <select id="district_id" name="district_id" class="form-control"  onchange="FilterUpdate()">
                                        <option value="">Select District</option>
            
                                        @foreach ($districts as $item)
                                            <option value="{{ $item->id }}" id="district_id_{{ $item->id }}" style="display:none;" class="district_map" >
                                                {{ $item->name }}</option>
                                        @endforeach
            
                                    </select>
            
                                </div>

                                
                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">Category Engineer</label>
                                    <select id="category_engineer" name="category_engineer" class="form-control"  onchange="Categorystaff()">
                                        <option value="">Select Category Engineer</option>
            
                                        @foreach ($staff_category as $item)

                                            <option value="{{ $item->id }}" id="category_engineer_{{ $item->id }}"  >

                                                {{ $item->name }}</option>

                                        @endforeach
            
                                    </select>
            
                                </div>

                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">Engineer</label>

                                    <select id="engineer_id" name="engineer_id" class="form-control" multiple="multiple"  onchange="FilterUpdate()">

                                        <option value="" id="select_engineer">Select Engineer</option>
            
                                      
                                    </select>
            
                                </div>


                                


                            </div>
                            <input type="hidden" id="staff" value="{{session('STAFF_ID')}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header">
                            <h3>Sales-Accessories</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Target</th>
                                                <th>Acheivement</th>
                                                <th>Commited</th>
                                                <th>Todo</th>
                                                <th>MSP</th>
                                                <th>Commission</th>
                                                <th>Lost</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sale-accessories">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>MSA Service</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Target</th>
                                                <th>Acheivement</th>
                                                <th>Cr %</th>
                                                <th>Todo</th>
                                                <th>Commited</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>

                                        <tbody id="sale-service">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Sales-Equipment</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Target</th>
                                                <th>Acheivement</th>
                                                <th>Commited</th>
                                                <th>Todo</th>
                                                <th>MSP</th>
                                                <th>Commission</th>
                                                <th>Lost</th>
                                            </tr>
                                        </thead>

                                        <tbody id="sale-equipment">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>


                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Sales-Part</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Target</th>
                                                <th>Acheivement</th>
                                                <th>Commited</th>
                                                <th>Todo</th>
                                                <th>MSP</th>
                                                <th>Commission</th>
                                                <th>Lost</th>
                                            </tr>
                                        </thead>

                                        <tbody id="sale-parts">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Opportunity - Accessories</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Total Opportunity(amt)</th>
                                                <th>New Opportunity (nos)</th>
                                                <th>Committed (nos)</th>
                                                <th>Commited with risk (nos)</th>
                                                <th>Open (nos)</th>
                                                <th>Upside (nos)</th>
                                              
                                            </tr>
                                        </thead>

                                        <tbody id="oppertunity_acccesseries">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                
                
                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Opportunity - Equipment</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Total Opportunity(amt)</th>
                                                <th>New Opportunity (nos)</th>
                                                <th>Committed (nos)</th>
                                                <th>Commited with risk (nos)</th>
                                                <th>Open (nos)</th>
                                                <th>Upside (nos)</th>
                                              
                                            </tr>
                                        </thead>

                                        <tbody id="oppertunity_equipment">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Opportunity - Parts</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Total Opportunity(amt)</th>
                                                <th>New Opportunity (nos)</th>
                                                <th>Committed (nos)</th>
                                                <th>Commited with risk (nos)</th>
                                                <th>Open (nos)</th>
                                                <th>Upside (nos)</th>
                                              
                                            </tr>
                                        </thead>

                                        <tbody id="oppertunity_parts">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                
                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Opportunity - MSA</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>Total Opportunity(amt)</th>
                                                <th>New Opportunity (nos)</th>
                                                <th>Committed (nos)</th>
                                                <th>Commited with risk (nos)</th>
                                                <th>Open (nos)</th>
                                                <th>Upside (nos)</th>
                                              
                                            </tr>
                                        </thead>

                                        <tbody id="oppertunity_msa_staff">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <?php /*
                
                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Special task</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engneer Name</th>
                                                <th>No of Task  Assigned (no)</th>
                                                <th>No of task participaged</th>
                                                <th>% Archeive</th>
                                            
                                            </tr>
                                        </thead>

                                        <tbody id="special_task">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>




                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Special task detail page</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Task name</th>
                                                <th>Target (no)</th>
                                                <th>Archeived nos</th>
                                        
                                            </tr>
                                        </thead>

                                        <tbody id="special_task">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div> */?>


                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Qucik links</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engineer Name</th>
                                                <th>No of Customers</th>
                                                <th>No of Department</th>
                                                <th>No of IBs</th>
                                                <th>No of visit</th>
                                                <th>No of calls</th>
                                                <th>No. Quote sent</th>
                                        
                                            </tr>
                                        </thead>

                                        <tbody id="quick_links">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>


                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Corrective repairs</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engineer Name</th>
                                                <th>Total Call (no)</th>
                                                <th>Total Call Closed (no)</th>
                                                <th>Pending (no)</th>
                                                <th>Ageing</th>
                                     
                                            </tr>
                                        </thead>

                                        <tbody id="staff_corrective">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>


                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>PM</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engineer Name</th>
                                                <th>Total Call (no)</th>
                                                <th>Total Call Closed (no)</th>
                                                <th>Pending (no)</th>
                                                <th>Un assigned PMs (no)</th>
                                                <th>Ageing</th>
                                     
                                            </tr>
                                        </thead>

                                        <tbody id="staff_pm">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

            <?php /*
            
                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Installation</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engineer Name</th>
                                                <th>Total Call (no)</th>
                                                <th>Total Call Closed (no)</th>
                                                <th>Pending (no)</th>
                                                <th>Ageing</th>
                                     
                                            </tr>
                                        </thead>

                                        <tbody id="staff_installation">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                */?>
                

                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Expense</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engineer Name</th>
                                                <th>Travel</th>
                                                <th>Other expense </th>
                                                <th>Salary</th>
                                                <th>Comission</th>
                                                <th>Total expense</th>
                                     
                                            </tr>
                                        </thead>

                                        <tbody id="staff_expense">
                                           

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div> 


                <div class="col-md-6">
                    <div class="box">  
                        <div class="box-header">
                            <h3>Work update</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Engineer Name</th>
                                                <th>Total dates</th>
                                                <th>No of days worked</th>
                                                <th>Work Update Pending</th>
                                                <th>Attendance Pending</th>
                                    
                                            </tr>
                                        </thead>

                                        <tbody id="staff_work_update">
                                        

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                


                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
    <div class="modal fade" id="deleteService" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="req-part">
                        <h3 class="modal-title">Do you want to delete ?</h3><br>
                        <form id="part-details" method="post" action="{{ route('staff.service-destroy') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="seviceId" name="seviceId" value="">
                            <input type="submit" class="btn btn-primary" name="submit" value="Yes">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

    <script>


        $(document).ready(function() {

            $('#brand_id').multiselect({
                nonSelectedText: 'Select Brand',
                enableFiltering: true,
                // enableCaseInsensitiveFiltering: true,
                // buttonWidth: '610px'
            });
        
        });

        $('#modality').multiselect({
            nonSelectedText: 'Select Modality',
            enableFiltering: true,
            // enableCaseInsensitiveFiltering: true,
            // buttonWidth: '610px'
        });

        $('#engineer_id').multiselect({
            nonSelectedText: 'Select Engineer',
            enableFiltering: true,
            // enableCaseInsensitiveFiltering: true,
            // buttonWidth: '610px'
        });

        function FilterUpdate(type)
        {
            var ids = $('#brand_id').val();


            if(type =='state')
            {
                $.get('{{route("staff.staff_report_district")}}',{
                    state:$('#state_id').val(),
                },function(res){

                    console.log(res.district);
                    $('.district_map').hide();

                    $.each(res.district,function(i,v)
                    {
                        $('#district_id_'+v).show();
                    })
                    
                },'json');

            }

            if(type =='brand')
            {
                $.get('{{route("staff.staff_report_modality")}}',{

                    brand_id:$('#brand_id').val(),
                    
                },function(res){

                    console.log(res.modality);

                    $('.modality_tab').remove();

                    var  option_html ='';

                    $.each(res.modality,function(i,v)
                    {
                        option_html+=`<option value="${v.id}" class="modality_tab">${v.name}</option>`;
                    });

                $('#select_modality').after(option_html);

                $('#modality').multiselect('rebuild');

                },'json');
            }
           

            loaddata();
        }

        function Categorystaff()
        {
            $.get('{{route("staff.staff_report_category")}}',{

                category_id:$('#category_engineer').val(),
                staff_id:$('#staff').val(),

            },function(res){

                console.log(res.staff);

                $('.cat_engineer').remove();

                var  option_html ='';

                $.each(res.staff,function(i,v)
                {
                    option_html+=`<option value="${v.id}" class="cat_engineer">${v.name}</option>`;
                });

                $('#select_engineer').after(option_html);

                $('#engineer_id').multiselect('rebuild');
                
            },'json');

        }


        function loaddata(){

            $.get('{{route("staff.staff-report")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#sale-accessories').html('')
                $.each(res,function(k,v){
                    $('#sale-accessories').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.accessories.target}</td>
                        <td>${v.accessories.achives}</td>
                        <td>${v.accessories.commeted}</td>
                        <td>${v.accessories.todo}</td>
                        <td>${v.accessories.msp}</td>
                        <td>${v.accessories.commission}</td>
                        <td>${v.accessories.lost}</td>
                    </tr>
                    `)
                })
            },'json');

        /****************MSA Service ***************************/

          $.get('{{route("staff.staff_msa_service")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#sale-service').html('')
                $.each(res,function(k,v){
                    $('#sale-service').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.mas_contract.target}</td>
                        <td>${v.mas_contract.achives}</td>
                        <td>${v.mas_contract.cr_percentage}</td>
                        <td>${v.mas_contract.todo}</td>
                        <td>${v.mas_contract.commeted}</td>
                        <td>${v.mas_contract.revenue}</td>
                
                    </tr>
                    `)
                })
            },'json');


        /**************** sales equipment section ***************************/

        $.get('{{route("staff.staff_equip_sales")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#sale-equipment').html('')
                $.each(res,function(k,v){
                    $('#sale-equipment').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.equipment.target}</td>
                        <td>${v.equipment.achives}</td>
                        <td>${v.equipment.commeted}</td>
                        <td>${v.equipment.todo}</td>
                        <td>${v.equipment.msp}</td>
                        <td>${v.equipment.commission}</td>
                        <td>${v.equipment.lost}</td>
                    </tr>
                    `)
                })
            },'json');


    /**************** sales equipment section ***************************/

            $.get('{{route("staff.staff_sales_parts")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#sale-parts').html('')
                $.each(res,function(k,v){
                    $('#sale-parts').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.equipment.target}</td>
                        <td>${v.equipment.achives}</td>
                        <td>${v.equipment.commeted}</td>
                        <td>${v.equipment.todo}</td>
                        <td>${v.equipment.msp}</td>
                        <td>${v.equipment.commission}</td>
                        <td>${v.equipment.lost}</td>
                    </tr>
                    `)
                })
            },'json');


 /**************** oppertunity accessseries ***************************/

             $.get('{{route("staff.oppertunity_accesseries")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#oppertunity_acccesseries').html('')
                $.each(res,function(k,v){
                    $('#oppertunity_acccesseries').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.opp_accesseries.total_oppertunity}</td>
                        <td>${v.opp_accesseries.new_oppertunity}</td>
                        <td>${v.opp_accesseries.Committed_nos}</td>
                        <td>${v.opp_accesseries.Committed_with_risk_nos}</td>
                        <td>${v.opp_accesseries.open_nos}</td>
                        <td>${v.opp_accesseries.upside_nos}</td>
                      
                    </tr>
                    `)
                })
            },'json');



/**************** oppertunity accessseries ***************************/

            $.get('{{route("staff.oppertunity_equipment")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#oppertunity_equipment').html('')
                $.each(res,function(k,v){
                    $('#oppertunity_equipment').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.opp_equipment.total_oppertunity}</td>
                        <td>${v.opp_equipment.new_oppertunity}</td>
                        <td>${v.opp_equipment.Committed_nos}</td>
                        <td>${v.opp_equipment.Committed_with_risk_nos}</td>
                        <td>${v.opp_equipment.open_nos}</td>
                        <td>${v.opp_equipment.upside_nos}</td>
                      
                    </tr>
                    `)
                })
            },'json');

            
    /***************** oppertunity parts ***************************/

            $.get('{{route("staff.oppertunity_parts")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#oppertunity_parts').html('')
                $.each(res,function(k,v){
                    $('#oppertunity_parts').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.opp_parts.total_oppertunity}</td>
                        <td>${v.opp_parts.new_oppertunity}</td>
                        <td>${v.opp_parts.Committed_nos}</td>
                        <td>${v.opp_parts.Committed_with_risk_nos}</td>
                        <td>${v.opp_parts.open_nos}</td>
                        <td>${v.opp_parts.upside_nos}</td>
                      
                    </tr>
                    `)
                })
            },'json');


    /***************** oppertunity MSA ***************************/

         $.get('{{route("staff.oppertunity_msa_staff")}}',{
                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#oppertunity_msa_staff').html('')
                $.each(res,function(k,v){
                    $('#oppertunity_msa_staff').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.opp_parts.total_oppertunity}</td>
                        <td>${v.opp_parts.new_oppertunity}</td>
                        <td>${v.opp_parts.Committed_nos}</td>
                        <td>${v.opp_parts.Committed_with_risk_nos}</td>
                        <td>${v.opp_parts.open_nos}</td>
                        <td>${v.opp_parts.upside_nos}</td>
                      
                    </tr>
                    `)
                })
            },'json');


            
 /***************** oppertunity MSA ***************************/

         $.get('{{route("staff.special_task")}}',{

                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
            },function(res){
                $('#special_task').html('')
                $.each(res,function(k,v){
                    $('#special_task').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.special_task.no_of_task_assigned}</td>
                        <td>${v.special_task.no_of_partisipate}</td>
                        <td>${v.special_task.archeive} % </td>
                
                    </tr>
                    `)
                })
            },'json');


            /***************** quick links ***************************/

            $.get('{{route("staff.quick_links")}}',{

                    staff:$('#staff').val(),
                    period:$('#period').val(),
                    brand_id:$('#brand_id').val(),
                    modality:$('#modality').val(),
                    state:$('#state_id').val(),
                    district : $('#district_id').val(),
                    engineer_id:$('#engineer_id').val(),
                },function(res){
                $('#quick_links').html('')
                $.each(res,function(k,v){
                    $('#quick_links').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.quick_links.no_of_user}</td>
                         <td>${v.quick_links.no_of_departments}</td>
                        <td>${v.quick_links.no_of_ib}</td>
                        <td>${v.quick_links.no_of_visits}</td>
                        <td>${v.quick_links.no_of_calls}</td>
                        <th>${v.quick_links.no_of_quote}</td>
                 
                    </tr>
                    `)
                })
                },'json');

                

             /***************** Staff Corrective ***************************/

                $.get('{{route("staff.staff_corrective")}}',{

                        staff:$('#staff').val(),
                        period:$('#period').val(),
                        brand_id:$('#brand_id').val(),
                        modality:$('#modality').val(),
                        state:$('#state_id').val(),
                        district : $('#district_id').val(),
                        engineer_id:$('#engineer_id').val(),
                        },function(res){
                        $('#staff_corrective').html('')
                        $.each(res,function(k,v){
                        $('#staff_corrective').append(`
                        <tr>
                            <td>${v.staff.name}</td>
                            <td>${v.staff_corrective.no_of_calls}</td>
                            <td>${v.staff_corrective.total_calls_closed}</td>
                            <td>${v.staff_corrective.total_calls_pending}</td>
                            <td>${v.staff_corrective.total_calls_ageing}</td>
                        
                        </tr>
                        `)
                        })
                    },'json');



        /***************** Staff pm ***************************/

                $.get('{{route("staff.staff_pm")}}',{

                            staff:$('#staff').val(),
                            period:$('#period').val(),
                            brand_id:$('#brand_id').val(),
                            modality:$('#modality').val(),
                            state:$('#state_id').val(),
                            district : $('#district_id').val(),
                            engineer_id:$('#engineer_id').val(),
                            },function(res){
                            $('#staff_pm').html('')
                            $.each(res,function(k,v){
                            $('#staff_pm').append(`
                            <tr>
                                <td>${v.staff.name}</td>
                                <td>${v.staff_pm.no_of_calls}</td>
                                <td>${v.staff_pm.no_of_calls_closed}</td>
                                <td>${v.staff_pm.no_of_calls_pending}</td>
                                <td>${v.staff_pm.un_assigned_pm}</td>
                                <td>${v.staff_pm.total_calls_ageing}</td>

                            </tr>
                        `)
                        })
                    },'json');

                    

        /***************** Staff Installation ***************************/

           $.get('{{route("staff.staff_installation")}}',{

                    staff:$('#staff').val(),
                    period:$('#period').val(),
                    brand_id:$('#brand_id').val(),
                    modality:$('#modality').val(),
                    state:$('#state_id').val(),
                    district : $('#district_id').val(),
                    engineer_id:$('#engineer_id').val(),
                    },function(res){
                    $('#staff_installation').html('')
                    $.each(res,function(k,v){
                    $('#staff_installation').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.staff_installation.no_of_calls}</td>
                        <td>${v.staff_installation.no_of_calls_closed}</td>
                        <td>${v.staff_installation.no_of_calls_pending}</td>
                        <td>${v.staff_installation.un_assigned_pm}</td>
                        <td>${v.staff_installation.total_calls_ageing}</td>

                    </tr>
                    `)
                })
                },'json');

         /***************** Staff Expense ***************************/

         $.get('{{route("staff.staff_expense")}}',{

                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
                },function(res){
                $('#staff_expense').html('')
                $.each(res,function(k,v){
                $('#staff_expense').append(`
                <tr>
                    <td>${v.staff.name}</td>
                    <td>${v.staff_travel_exp.tot_price_exp}</td>
                    <td>${v.staff_travel_exp.no_of_calls_closed}</td>
                    <td>${v.staff_travel_exp.no_of_calls_pending}</td>
                    <td>${v.staff_travel_exp.un_assigned_pm}</td>
                    <td>${v.staff_travel_exp.total_calls_ageing}</td>

                </tr>
                `)
                })
            },'json');


        /***************** Staff Work Update ***************************/

         $.get('{{route("staff.staff_work_update")}}',{

                staff:$('#staff').val(),
                period:$('#period').val(),
                brand_id:$('#brand_id').val(),
                modality:$('#modality').val(),
                state:$('#state_id').val(),
                district : $('#district_id').val(),
                engineer_id:$('#engineer_id').val(),
                },function(res){
                    $('#staff_work_update').html('')
                    $.each(res,function(k,v){
                    $('#staff_work_update').append(`
                    <tr>
                        <td>${v.staff.name}</td>
                        <td>${v.staff_work_update.no_of_calls}</td>
                        <td>${v.staff_work_update.no_of_calls_closed}</td>
                        <td>${v.staff_work_update.no_of_calls_pending}</td>
                        <td>${v.staff_work_update.un_assigned_pm}</td>
                        <td>${v.staff_work_update.total_calls_ageing}</td>

                    </tr>
                    `)
                })
            },'json');

        
            
        }

        $(function(){
            loaddata()
        })
    </script>
@endsection
