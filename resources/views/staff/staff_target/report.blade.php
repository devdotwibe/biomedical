@extends('staff/layouts.app')
@section('title', 'Staff Sales Report')
@section('content')

<style>
    .search-list{
        display: none;
        overflow: hidden;
        position: absolute;
        z-index: 99;
        width: 100%;
        height: auto;
        padding-right: 20px;
    }
    .search-box:focus+.search-list{
        display: block;
    }
    .report-table-over {
        height: 100%;
        position: absolute;
        background: aliceblue;
        width: 99%;
    }
    .report-table-over span{
        position: relative;
        top: 45%;
        left: 40%;
        height: 10%;
    }
    .report-table-over img{
        position: relative;
        height: 10%;
        widows: 10%;
        left: 45%;
        top: 45%
    }
</style>

<section class="content-header">
    <h1>
      Staff Sales Report 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Staff Sales Report </li>
    </ol>
</section>
  
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    @if (session('success'))
                        <div class="alert alert-success alert-block fade in alert-dismissible show">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="box-body">  
                    <div class="row">
                        <div class="col-xs-12">
                            <form action="javascript:void(0)">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="engineer">Engineer</label>
                                            <select name="engineer" id="engineer" class="form-control" data-placeholder="Select an Engineer" onchange="refreshdata()">
                                                <option value="">Select an Engineer</option>
                                                @foreach ($engineers as $engineer)
                                                    <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>                            
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="coordinator">Coordinator</label>
                                            <select name="coordinator" id="coordinator" class="form-control" data-placeholder="Select an Coordinator" onchange="refreshdata()">
                                                <option value="">Select an Coordinator</option>
                                                @foreach ($engineers as $engineer)
                                                    <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>                            
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="year">Year</label>
                                            <select name="year" id="year" class="form-control "  data-placeholder="Select an Year" onchange="refreshdata()">
                                                <option value="">Select an Year</option>
                                                @for($i=2022;$i<=(date("Y")+2);$i++)
                                                <option value="{{$i}}" @if($i==date("Y")) selected @endif>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>                            
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="period">Period</label>
                                            <select name="period" id="period" class="form-control "  data-placeholder="Select an Period" onchange="refreshdata()">
                                                <option value="">Select an Period</option>
                                                <option value="1st Quarter" @if(\Carbon\Carbon::now()->quarter==1) selected @endif>1<sup>st</sup> Quarter</option>    
                                                <option value="2nd Quarter" @if(\Carbon\Carbon::now()->quarter==2) selected @endif>2<sup>nd</sup> Quarter</option>    
                                                <option value="3rd Quarter" @if(\Carbon\Carbon::now()->quarter==3) selected @endif>3<sup>rd</sup> Quarter</option>    
                                                <option value="4th Quarter" @if(\Carbon\Carbon::now()->quarter==4) selected @endif>4<sup>th</sup> Quarter</option>                                    
                                            </select>
                                        </div>                            
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <select name="state" id="state" class="form-control "  data-placeholder="Select an State" onchange="refreshdata()" multiple >
                                                
                                                @foreach ($states as $state)
                                                    <option value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach                                  
                                            </select>
                                        </div>                            
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="district">District</label>
                                            <select name="district" id="district" class="form-control "  data-placeholder="Select an District" onchange="refreshdata()" multiple>
                                                {{-- <option value="">Select an District</option> --}}
                                            </select>
                                        </div>                            
                                    </div>
        
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="customer">Customer</label>
                                            <select name="customer" id="customer" class="form-control select2"  data-placeholder="Select an Customer" onchange="refreshdata()" multiple >
                                                {{-- <option value="">Select an Customer</option>                                  --}}
                                            </select>
                                        </div>                            
                                    </div>
        
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="equipmenaccessory">Equipment/Accessories</label>
                                            <select name="equipmenaccessory" id="equipmenaccessory" class="form-control"  data-placeholder="Select an Equipment/Accessories" onchange="refreshdata()">
                                                <option value="">Select an Equipment/Accessories</option> 
                                                <option value="equipments">Equipments</option>
                                                <option value="accessories">Accessories</option>
                                            </select>
                                        </div>                            
                                    </div>
        
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="brand">Brand</label>
                                            <select name="brand" id="brand" class="form-control "  data-placeholder="Select an Brand" onchange="refreshdata()">
                                                <option value="">Select an Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                @endforeach                                  
                                            </select>
                                        </div>                            
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label for="order"><input type="checkbox" class="form-check-input" id="order" name="order" value="order" onchange="orderopurtunity();refreshdata()" checked>Order</label>
                                            </div>
                                            <div class="form-check">
                                                <label for="opportunity"><input type="checkbox" class="form-check-input" id="opportunity" name="opportunity" value="opportunity" onchange="orderopurtunity();refreshdata()"  checked>Opportunity</label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="search">Search</label>
                                            <input type="search" name="q" id="search" class="form-control search-box" data-placeholder="Search..." placeholder="Search..." aria-autocomplete="none" autocomplete="off" data-list="#search-list" onkeyup="serachlist()" onchange="refreshdata()">
                                            <div class="search-list">
                                                <ul class="list-group" id="search-list">
                                                </ul>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <br>
                                            <button type="reset" class="btn btn-danger btn-sm" id="reset" onclick="resetaction()"> Reset All </button>
                                        </div>                            
                                    </div>
                                </div>
                            </form>   
                        </div>
                    </div>     
                    <div class="row" id="report-detail-area" style="display: none">
                        <div class="col-xs-12">
                            <div class="report-table-over "  id="report-detail-area-loading"  ><img src="{{ asset('images/wait.gif') }}"></div>
                            <div id="report-empty" class="report-table-over"  style="display: none">
                                <span>Empty Data</span>
                            </div>
                            <div class="table-responsive ">
                                <table class="table table-bordered table-striped"  id="report-table" >
                                    <thead>
                                        <tr>
                                            <th>Category Show Actions</th>
                                            <th>Total Amount</th>
                                            <th id="equipments">T.Equipments</th>
                                            <th id="accessories">T.Accessories</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="target">
                                            <td >Target</td>
                                            <td id="total-target">-</td>
                                            <td id="equipments-target">-</td>
                                            <td id="accessories-target">-</td>
                                        </tr>
                                        {{-- <tr>
                                            <td >Other Opp</td>
                                            <td id="total-other">-</td>
                                            <td id="equipments-other">-</td>
                                            <td id="accessories-other">-</td>
                                        </tr> --}}
                                        <tr class="opportunity">
                                            <td >Commited with risk</td>
                                            <td id="total-commited-risk">-</td>
                                            <td id="equipments-commited-risk">-</td>
                                            <td id="accessories-commited-risk">-</td>
                                        </tr>
                                        <tr class="opportunity">
                                            <td >Commited</td>
                                            <td id="total-commited">-</td>
                                            <td id="equipments-commited">-</td>
                                            <td id="accessories-commited">-</td>
                                        </tr>
                                        <tr class="opportunity">
                                            <td >Won</td>
                                            <td id="total-won">-</td>
                                            <td id="equipments-won">-</td>
                                            <td id="accessories-won">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >New Orders</td>
                                            <td id="total-new-order">-</td>
                                            <td id="equipments-new-order">-</td>
                                            <td id="accessories-new-order">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >Initial Check</td>
                                            <td id="total-initial-check">-</td>
                                            <td id="equipments-initial-check">-</td>
                                            <td id="accessories-initial-check">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >TA</td>
                                            <td id="total-ta">-</td>
                                            <td id="equipments-ta">-</td>
                                            <td id="accessories-ta">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >FA</td>
                                            <td id="total-fa">-</td>
                                            <td id="equipments-fa">-</td>
                                            <td id="accessories-fa">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >Billing</td>
                                            <td id="total-billing">-</td>
                                            <td id="equipments-billing">-</td>
                                            <td id="accessories-billing">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >Despatch</td>
                                            <td id="total-despatch">-</td>
                                            <td id="equipments-despatch">-</td>
                                            <td id="accessories-despatch">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >Documentation</td>
                                            <td id="total-documentation">-</td>
                                            <td id="equipments-documentation">-</td>
                                            <td id="accessories-documentation">-</td>
                                        </tr>
                                        <tr class="order" >
                                            <td >Supply Issues</td>
                                            <td id="total-supply-issues">-</td>
                                            <td id="equipments-supply-issues">-</td>
                                            <td id="accessories-supply-issues">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >Payment Followup</td>
                                            <td id="total-payment-followup">-</td>
                                            <td id="equipments-payment-followup">-</td>
                                            <td id="accessories-payment-followup">-</td>
                                        </tr>
                                        <tr class="order">
                                            <td >Audit</td>
                                            <td id="total-audit">-</td>
                                            <td id="equipments-audit">-</td>
                                            <td id="accessories-audit">-</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@section('scripts')


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script>
    function orderopurtunity(){
        if($("input#order").is(":checked")){
            $('tr.order').show();
        }else{
            $('tr.order').hide();
        }
        if($("input#opportunity").is(":checked")){
            $('tr.opportunity').show();
        }else{
            $('tr.opportunity').hide();
        }
    }
    function serachlist(){
        if($("#search").val()==""){
            $("#search-list").html("");
        }else{
            $("#search-list").html("");
        }
    }
    var resetrefresh=false;
    function  resetaction() {
        resetrefresh=true;
        $('#customer').val([])
        $('#district').val([])
        $('#state').val([]).change()
        setTimeout(() => {
            resetrefresh=false;
            refreshdata();
        }, 500);
    }
    $(function(){

        $('#customer').select2({
            placeholder:"Select an Customer",
            ajax: {
                url: '{{route('staff.staff.target.customer')}}',
                data: function (params) {
                    params.state=$('#state').val()
                    params.district=$('#district').val()
                    return params;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 10) < data.count_filtered
                        }
                    };
                }
            }
        });

        $('#district').select2({
            placeholder:"Select an District",
            ajax: {
                url: '{{route('staff.staff.target.district')}}',
                data: function (params) {
                    params.state=$('#state').val()
                    return params;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 10) < data.count_filtered
                        }
                    };
                }
            }
        }).change(function(){
            $('#customer').val([]).change()
        });
        $("#state").select2({placeholder:"Select an State",}).change(function(){
            $('#district').val([]).change()
        });
        refreshdata();
    })
    function refreshdata(){
        if(resetrefresh){
            return;
        }
        $('#report-detail-area').show()
        $('#report-detail-area-loading').show()
        $('#report-empty').hide()
        $.get('{{request()->fullUrl()}}',{
            "staff":$('#engineer').val(),
            "coordinator":$("#coordinator").val(),
            "year":$("#year").val(),
            "period":$('#period').val(),
            "state":$('#state').val(),
            "district":$('#district').val(),
            "customer":$('#customer').val(),
            "equipmenaccessory":$('#equipmenaccessory').val(),
            "brand":$('#brand').val(),
        },function(res){
            var isEmpty=true;
            $(`.removable`).remove();
            var statuslist={
                "new-order":"New Orders",
                "initial-check":"Initial Check",
                "ta":"Technical Approval",
                "fa":"Finance Approval",
                "billing":"Billing",
                "despatch":"Dispatch",
                "documentation":"Documentation",
                "supply-issues":"Supply Issue",
                "payment-followup":"Payment Follow Up",
                "audit":"Audit"
            };
            
            $.each(res.equipments,function(k,v){
                isEmpty=false;
                $(`<th class="removable">${v.name}</th>`).insertAfter('#equipments')
                $(`<td class="removable" id="equipments-target-${v.id}">-</td>`).insertAfter(`#equipments-target`)
               // $(`<td class="removable" id="equipments-other-${v.id}">-</td>`).insertAfter(`#equipments-other`)
                $(`<td class="removable" id="equipments-commited-risk-${v.id}">-</td>`).insertAfter(`#equipments-commited-risk`)
                $(`<td class="removable" id="equipments-commited-${v.id}">-</td>`).insertAfter(`#equipments-commited`)
                $(`<td class="removable" id="equipments-won-${v.id}">-</td>`).insertAfter(`#equipments-won`)
                $(`<td class="removable" id="equipments-new-order-${v.id}">-</td>`).insertAfter(`#equipments-new-order`)
                $(`<td class="removable" id="equipments-initial-check-${v.id}">-</td>`).insertAfter(`#equipments-initial-check`)
                $(`<td class="removable" id="equipments-ta-${v.id}">-</td>`).insertAfter(`#equipments-ta`)
                $(`<td class="removable" id="equipments-fa-${v.id}">-</td>`).insertAfter(`#equipments-fa`)
                $(`<td class="removable" id="equipments-billing-${v.id}">-</td>`).insertAfter(`#equipments-billing`)
                $(`<td class="removable" id="equipments-despatch-${v.id}">-</td>`).insertAfter(`#equipments-despatch`)
                $(`<td class="removable" id="equipments-documentation-${v.id}">-</td>`).insertAfter(`#equipments-documentation`)
                $(`<td class="removable" id="equipments-supply-issues-${v.id}">-</td>`).insertAfter(`#equipments-supply-issues`)
                $(`<td class="removable" id="equipments-payment-followup-${v.id}">-</td>`).insertAfter(`#equipments-payment-followup`)
                $(`<td class="removable" id="equipments-audit-${v.id}">-</td>`).insertAfter(`#equipments-audit`)
            })

            $.each(res.accessories,function(k,v){
                isEmpty=false;
                $(`<th class="removable">${v.name}</th>`).insertAfter('#accessories')
                $(`<td class="removable" id="accessories-target-${v.id}">-</td>`).insertAfter(`#accessories-target`)
               // $(`<td class="removable" id="accessories-other-${v.id}">-</td>`).insertAfter(`#accessories-other`)
                $(`<td class="removable" id="accessories-commited-risk-${v.id}">-</td>`).insertAfter(`#accessories-commited-risk`)
                $(`<td class="removable" id="accessories-commited-${v.id}">-</td>`).insertAfter(`#accessories-commited`)
                $(`<td class="removable" id="accessories-won-${v.id}">-</td>`).insertAfter(`#accessories-won`)
                $(`<td class="removable" id="accessories-new-order-${v.id}">-</td>`).insertAfter(`#accessories-new-order`)
                $(`<td class="removable" id="accessories-initial-check-${v.id}">-</td>`).insertAfter(`#accessories-initial-check`)
                $(`<td class="removable" id="accessories-ta-${v.id}">-</td>`).insertAfter(`#accessories-ta`)
                $(`<td class="removable" id="accessories-fa-${v.id}">-</td>`).insertAfter(`#accessories-fa`)
                $(`<td class="removable" id="accessories-billing-${v.id}">-</td>`).insertAfter(`#accessories-billing`)
                $(`<td class="removable" id="accessories-despatch-${v.id}">-</td>`).insertAfter(`#accessories-despatch`)
                $(`<td class="removable" id="accessories-documentation-${v.id}">-</td>`).insertAfter(`#accessories-documentation`)
                $(`<td class="removable" id="accessories-supply-issues-${v.id}">-</td>`).insertAfter(`#accessories-supply-issues`)
                $(`<td class="removable" id="accessories-payment-followup-${v.id}">-</td>`).insertAfter(`#accessories-payment-followup`)
                $(`<td class="removable" id="accessories-audit-${v.id}">-</td>`).insertAfter(`#accessories-audit`)
            })
            $.each(res.report,function(k,v){
                var url="#"
                var urlAttr="";
                if($('#engineer').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`staff=${$('#engineer').val()}`)
                }
                if($('#coordinator').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`coordinator=${$('#coordinator').val()}`)
                }
                if($('#year').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`year=${$('#year').val()}`)
                }
                if($('#period').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`period=${$('#period').val()}`)
                }
                if($('#state').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`state=${$('#state').val()}`)
                }
                if($('#district').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`district=${$('#district').val()}`)
                }
                if($('#customer').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`customer=${$('#customer').val()}`)
                }
                if($('#equipmenaccessory').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`equipmenaccessory=${$('#equipmenaccessory').val()}`)
                }
                if($('#brand').val()!=''){
                    urlAttr+=(urlAttr!=""?"&":"")+encodeURI(`brand=${$('#brand').val()}`)
                }
                if(statuslist[k]){
                    url='{{route('staff.staff.target.commission.index')}}?'+urlAttr+encodeURI(`&commission_status=${statuslist[k]}`)
                }else{
                    switch (k) {
                        case "target":
                            url='{{route('staff.staff.target.detailreport')}}?'+urlAttr+encodeURI(`&mode=target`)
                            break;
                        case "commited-risk":
                            url='{{route('staff.list_oppertunity')}}?'+urlAttr+encodeURI(`&close_status=all&mode=commited-risk`)
                            break;
                        case "commited":
                            url='{{route('staff.list_oppertunity')}}?'+urlAttr+encodeURI(`&close_status=all&mode=commited`)
                            break;
                        case "won":
                            url='{{route('staff.list_oppertunity')}}?'+urlAttr+encodeURI(`&close_status=all&mode=won`)
                            break;
                    
                        default:
                            break;
                    }
                }
                
                $(`#total-${k}`).html(v.total?`<a target="_blank" href="${url}">${v.total}</a>`:'-');
                $(`#equipments-${k}`).html(v.total_equipments?`<a target="_blank" href="${url+encodeURI("&product_type=1")}">${v.total_equipments}</a>`:'-');
                $(`#accessories-${k}`).html(v.total_accessories?`<a target="_blank" href="${url+encodeURI("&product_type=3")}">${v.total_accessories}</a>`:'-');
                $.each(v.equipments,function(ik,iv){

                    $(`#equipments-${k}-${iv.id}`).html(iv.total?`<a target="_blank" href="${url+encodeURI("&brand="+iv.id)+encodeURI("&product_type=1")}">${iv.total}</a>`:'-')
                })
                $.each(v.accessories,function(ik,iv){
                    $(`#accessories-${k}-${iv.id}`).html(iv.total?`<a target="_blank" href="${url+encodeURI("&brand="+iv.id)+encodeURI("&product_type=3")}">${iv.total}</a>`:'-')
                })
                
            })
            if(isEmpty){
                $('#report-empty').html("<span>Empty Data </span>").show();
            }else{
                $('#report-empty').hide();
            }
        }).fail(function(){
            $('#report-empty').html("<span> Error </span>").show();
        }).always(function(){
            $('#report-detail-area-loading').hide()
        });
    }

</script>

@endsection