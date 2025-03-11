

@extends('staff/layouts.app')

@section('title', 'Manage Staff Sales Target')

@section('content')

<section class="content-header">
  <h1>
    Manage Staff Sales Target - {{$year}}
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Staff Sales Target - {{$year}}</li>
  </ol>
</section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="row">
              <div class="col-lg-12 margin-tb">
                  
                  <div class="pull-left">
                    <label for="year">Select Year</label>
                      <select name="year" class="form-control" id="year">
                        @for ($i = $minYear; $i < date('Y',strtotime('+5 years')); $i++)
                          <option value="{{$i}}" data-href="{{route('staff.staff.target.index',['year'=>$i])}}" @if($i==$year) selected @endif>{{$i}}</option>                            
                        @endfor
                      </select>
                  </div>
              </div>
            </div>

            <div class="box-body">              
              <table id="staffTargetTable" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr>
                    <th>Sales</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                    <th>Total</th>
                  </tr>
                </thead>

                
                  @foreach($staffs as $stfgrp => $stflst)

                  <tbody>
                    <tr>
                      <th colspan="14">
                        <strong>
                          {{$stfgrp}}
                        </strong>
                      </th>
                    </tr>
                  </tbody> 

                @foreach ($stflst as $staff)
                <tbody id="staff-group-{{$staff->id}}" data-staff="{{$staff->id}}" class="collapse-body">
                  <tr class="staff-main-{{$staff->id}} collapse-parent" data-staff="{{$staff->id}}" >
                      <th> <a  class="staff-{{$staff->id}} collapse-staff" data-staff="{{$staff->id}}" onclick="togglestaff({{$staff->id}})"> {{$staff->name}} </a> </th>
                      <th id="staff-{{$staff->id}}-jan">{{$staff->jan}}</th>
                      <th id="staff-{{$staff->id}}-feb">{{$staff->feb}}</th>
                      <th id="staff-{{$staff->id}}-mar">{{$staff->mar}}</th>
                      <th id="staff-{{$staff->id}}-apr">{{$staff->apr}}</th>
                      <th id="staff-{{$staff->id}}-may">{{$staff->may}}</th>
                      <th id="staff-{{$staff->id}}-jun">{{$staff->jun}}</th>
                      <th id="staff-{{$staff->id}}-jul">{{$staff->jul}}</th>
                      <th id="staff-{{$staff->id}}-aug">{{$staff->aug}}</th>
                      <th id="staff-{{$staff->id}}-sep">{{$staff->sep}}</th>
                      <th id="staff-{{$staff->id}}-oct">{{$staff->oct}}</th>
                      <th id="staff-{{$staff->id}}-nov">{{$staff->nov}}</th>
                      <th id="staff-{{$staff->id}}-dec">{{$staff->dec}}</th>
                      <th id="staff-{{$staff->id}}-total">{{$staff->total}}</th>
                  </tr> 
                </tbody>
                @endforeach

             
                @endforeach 


                  <tfoot>
                    <tr>
                      <td colspan="13"></td>
                    </tr>
                  </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>


@endsection

@section('scripts')

<div class="modal fade" id="modalAddBrand">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Brand</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('staff.staff.target.store')}}" class="form" method="post" id="add-brand-form">
          @csrf
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="brand">Brand *</label>
                  <select class="form-control" name="brand" id="brand" onchange="ChangeModality(this)"> 
                    <option value="">--select--</option>
                    @foreach ($brands as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                  </select>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="modality">Modality *</label>
                  <select class="form-control" name="modality" id="modality">
                    <option value="">--select--</option>
                    @foreach ($modality as $item)
                        <option value="{{$item->id}}" id="mod_{{ $item->id }}" style="display:none;" class="mod_class">{{$item->name}}</option>
                    @endforeach
                  </select>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="brand_target">Target *</label>
                <input type="number" class="form-control" name="brand_target" id="brand_target" value="">
              </div>
            </div>
          </div>
          <input type="hidden" name="year" value="{{$year}}">
          <input type="hidden" id="target-start" name="start_month" value="">
          <input type="hidden" id="target-end" name="end_month" value="">
          <input type="hidden" id="target-staff-id" name="staff_id" value="">
          <input type="hidden" id="target-type" name="target_type" value="">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="add-brand-submit-button" >+Add</button>
              </div>
            </div>
          </div>


        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalAddBrandMonthTarget">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Brand Month Target</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('staff.staff.target.addmonth')}}" class="form" method="post" id="add-brand-month-target-form">
          @csrf
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="brand_target_month">Month *</label>
                  <select class="form-control" name="target_month" id="brand_target_month">
                      <option value="">--select--</option>
                      <option value="1" >Jan</option>
                      <option value="2" >Feb</option>
                      <option value="3" >Mar</option>
                      <option value="4" >Apr</option>
                      <option value="5" >May</option>
                      <option value="6" >Jun</option>
                      <option value="7" >Jul</option>
                      <option value="8" >Aug</option>
                      <option value="9" >Sep</option>
                      <option value="10"  >Oct</option>
                      <option value="11"  >Nov</option>
                      <option value="12"  >Dec</option>
                  </select>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="brand_target_month_value">Target *</label>
                <input type="number" class="form-control" name="brand_target" id="brand_target_month_value" value="">
              </div>
            </div>
          </div>
          <input type="hidden" name="year" value="{{$year}}">
          <input type="hidden" id="brand-target-staff-id" name="staff_id" value="">
          <input type="hidden" id="brand-target-type" name="target_type" value="">
          <input type="hidden" id="brand-target-id" name="brand" value="">
          <input type="hidden" id="brand-target-button" name="target_button" value="add">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                &nbsp;&nbsp;<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                &nbsp;&nbsp;<button type="submit" class="btn btn-warning" name="less" value="less" id="less-brand-month-target-submit-button" >+Less</button>
                &nbsp;&nbsp;<button type="submit" class="btn btn-primary" name="add" value="add" id="add-brand-month-target-submit-button" >+Add</button>
              </div>
            </div>
          </div>


        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalAddMonthTargetCommission">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update Commission</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('staff.staff.target.addmonthcommission')}}" class="form" method="post" id="add-commission-month-target-form">
          @csrf
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="commision_target_month_value">Commission *</label>
                <input type="number" class="form-control" name="target_amount" id="commision_target_month_value" value="">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="commision_target_month_reason">Reason</label>
                <textarea class="form-control" name="reason" id="commision_target_month_reason" ></textarea>
              </div>
            </div>
          </div>
          <input type="hidden" name="year" value="{{$year}}">
          <input type="hidden" id="commision-target-staff-id" name="staff_id" value="">
          <input type="hidden" id="commision-target-month" name="target_month" value="">
          <input type="hidden" id="commision-target-type" name="target_type" value="">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="add-commision-month-target-submit-button" >+Add</button>
              </div>
            </div>
          </div>


        </form>


        <div class="row">
          <div class="col-12" id="commission-amount-history">

          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>



<div class="modal fade" id="modalAddMonthTargetPaid">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Paid Amount</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('staff.staff.target.addmonthpaid')}}" class="form" method="post" id="add-paid-month-target-form">
          @csrf
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="paid_target_month_value">Paid Amount *</label>
                <input type="number" class="form-control" name="target_amount" id="paid_target_month_value" value="">
              </div>
            </div>
          </div>
          <input type="hidden" name="year" value="{{$year}}">
          <input type="hidden" id="paid-target-staff-id" name="staff_id" value="">
          <input type="hidden" id="paid-target-month" name="target_month" value="">
          <input type="hidden" id="paid-target-type" name="target_type" value="">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="add-paid-month-target-submit-button" >+Add</button>
              </div>
            </div>
          </div>


        </form>
        <div class="row">
          <div class="col-12" id="paid-amount-history">

          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modalDeleteTarget">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirm Delete</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this Brand?</p>
      </div>
      <form action="{{route('staff.staff.target.removeBrand')}}" class="form" method="post" id="remove-brand-target-form">
        @csrf
        <div class="modal-footer">        
          <input type="hidden" name="year" value="{{$year}}">
          <input type="hidden" id="remove-brand-target-staff-id" name="staff_id" value="">
          <input type="hidden" id="remove-brand-target-type" name="target_type" value="">
          <input type="hidden" id="remove-brand-target-id" name="brand" value="">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="remove-brand-target-form-button" >Delete</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
// function approvedstatus(url,status){
//   $.post(url,{approve:(status?"Y":"N")},function(res) {
//     if(res.success){
//       popup_notifyMe('success',res.success)
//     }
//     $('#staffTargetTable').DataTable().ajax.reload();
//   },"json")
// }

  function ChangeModality(e)
    {
      var value = $(e).val();

        $.post('{{route("staff.modality_change")}}',{

          value:value,
            
        },function(res){

            $('.mod_class').hide();

            $.each(res.mod,function(i,v)
            {
                $('#mod_'+v).show();
            })
            
        },'json');
      
    }

  
  function addbrand(staff_id,type){
    $('#target-staff-id').val(staff_id);
    $('#target-type').val(type);
    $('#target-start').val($(`#${type}_${staff_id}_start`).val());
    $('#target-end').val($(`#${type}_${staff_id}_end`).val());
    $('#brand').val("");
    $('#brand_target').val("");
    $('#modalAddBrand').modal('show');
  }

  function addbrandmonth(staff_id,type,brand){
    $('#brand_target_month_value').val("")
    $('#brand_target_month').val("")
    $('#brand-target-staff-id').val(staff_id);
    $('#brand-target-type').val(type);
    $('#brand-target-id').val(brand);
    $('#modalAddBrandMonthTarget').modal('show');
  }

  function addmonthcommission(staff_id,type,month,amount){

    return false;//commented 

    $('#commision_target_month_value').val(amount)
    $('#commision_target_month_reason').val("")
    $('#commision-target-staff-id').val(staff_id);
    $('#commision-target-type').val(type);
    $('#commision-target-month').val(month);
    $('#modalAddMonthTargetCommission').modal('show');
    $('#commission-amount-history').html("<span id='commission-amount-history-loader'>Data Loading... <i class='fa fa-spinner fa-spin'></i></span>");
    $.get("{{route('staff.staff.target.getmonthcommission')}}",{
      year:{{$year}},
      staff_id:staff_id,
      target_type:type,
      target_month:month
    },function(res){
      if(res&&res.length>0){
        $('#commission-amount-history').append(`
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Sl.No</th>
              <th>Amount</th>
              <th>Difference</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody id="commission-amount-history-datas">
          </tbody>
        </table>
        `);
        $.each(res,function(k,v){
          $('#commission-amount-history-datas').append(`
            <tr>
              <td>${k+1}</td>
              <td>${v.commission_amount}</td>
              <td>${v.target_amount}</td>
              <td>${v.created_at}</td>  
            </tr>
          `);
        })
      }
    }).always(function(){
      $('span#commission-amount-history-loader').remove();
    })
  }

  function addmonthpaidtarget(staff_id,type,month){
    $('#paid_target_month_value').val("")
    $('#paid-target-staff-id').val(staff_id);
    $('#paid-target-type').val(type);
    $('#paid-target-month').val(month);
    $('#modalAddMonthTargetPaid').modal('show');
    $('#paid-amount-history').html("<span id='paid-amount-history-loader'>Data Loading... <i class='fa fa-spinner fa-spin'></i></span>");
    $.get("{{route('staff.staff.target.getmonthpaid')}}",{
      year:{{$year}},
      staff_id:staff_id,
      target_type:type,
      target_month:month
    },function(res){
      if(res&&res.length>0){
        $('#paid-amount-history').append(`
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Sl.No</th>
              <th>Amount</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody id="paid-amount-history-datas">
          </tbody>
        </table>
        `);
        $.each(res,function(k,v){
          $('#paid-amount-history-datas').append(`
            <tr>
              <td>${k+1}</td>
              <td>${v.paid_amount}</td>
              <td>${v.created_at}</td>  
            </tr>
          `);
        })
      }
    }).always(function(){
      $('span#paid-amount-history-loader').remove();
    })
  }

  function removebrandtarget(staff_id,type,brand){
    $('#remove-brand-target-staff-id').val(staff_id);
    $('#remove-brand-target-type').val(type);
    $('#remove-brand-target-id').val(brand);
    $('#modalDeleteTarget').modal('show');
  }
  function targetPeriod(staff_id,type){
    if($(`#${type}_${staff_id}_end`).val()!=""&&$(`#${type}_${staff_id}_start`).val()!=""){
      if($(`.${type}_${staff_id}_brand_target`).length>0){
        $(`#${type}_${staff_id}_period`).show()
      }else{
        $(`#${type}_${staff_id}_period`).hide()
      }
      $(`#${type}-add-brand-button-${staff_id}`).show()
    }else{
      $(`#${type}_${staff_id}_period`).hide()
      $(`#${type}-add-brand-button-${staff_id}`).hide()
    }


  }
  function togglestaff(staff_id) {
    $('#staff-group-'+staff_id).toggleClass("row-open");
    if($('#staff-group-'+staff_id).hasClass('row-open')){
      $('#staff-group-'+staff_id).append(`
          <tr id="staff-data-loader-${staff_id}">
            <td colspan="13">Data Loading... <i class='fa fa-spinner fa-spin'></i></td>
          </tr>
      `);
      $.get("{{route('staff.staff.target.data')}}",{id:staff_id,year:{{$year}}},function(data){
        if(data.success){
          var staffname=$(`.staff-main-${staff_id} th a.staff-${staff_id}`).html();
          $('#staff-group-'+staff_id).append(`
                <tr class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <td colspan="14"><a  class="staff-${staff_id} collapse-staff" data-staff="${staff_id}" onclick="togglestaff(${staff_id})"> ${staffname} </a> </td>
                  <td colspan="${data.equipments.brands.length+3}" class="text-right"><button class="btn btn-primary" ${data.equipments.brands.length>0?"":'style="display:none"'} id="equipments-add-brand-button-${staff_id}" onclick="addbrand(${staff_id},'equipments')">+add brand</button></td>
                </tr>
                <tr class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <td colspan="14"></td>
                  <td colspan="${data.equipments.brands.length+3}" class="text-center"><h3>Equipments</h3></td>
                </tr>
                <tr id="staff-g-${staff_id}-1" class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <td colspan="14"></td>
                </tr>
                <tr id="staff-g-${staff_id}-2" class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <th> <span class="badge btn-block btn-primary">Target</span> </th>
                  <th>${data.equipments.jan}</th>
                  <th>${data.equipments.feb}</th>
                  <th>${data.equipments.mar}</th>
                  <th>${data.equipments.apr}</th>
                  <th>${data.equipments.may}</th>
                  <th>${data.equipments.jun}</th>
                  <th>${data.equipments.jul}</th>
                  <th>${data.equipments.aug}</th>
                  <th>${data.equipments.sep}</th>
                  <th>${data.equipments.oct}</th>
                  <th>${data.equipments.nov}</th>
                  <th>${data.equipments.dec}</th>
                  <td>${data.equipments.total}</td>
                </tr>
                <tr id="staff-g-${staff_id}-3" class="staff-g-${staff_id} collapse-child" data.equipments-staff="${staff_id}" style="display:none">
                  <th><span class="badge btn-block btn-warning">Achived</span> </th>
                  <th>${data.equipments.achived.jan}</th>
                  <th>${data.equipments.achived.feb}</th>
                  <th>${data.equipments.achived.mar}</th>
                  <th>${data.equipments.achived.apr}</th>
                  <th>${data.equipments.achived.may}</th>
                  <th>${data.equipments.achived.jun}</th>
                  <th>${data.equipments.achived.jul}</th>
                  <th>${data.equipments.achived.aug}</th>
                  <th>${data.equipments.achived.sep}</th>
                  <th>${data.equipments.achived.oct}</th>
                  <th>${data.equipments.achived.nov}</th>
                  <th>${data.equipments.achived.dec}</th>
                  <td>${data.equipments.achived.total}</td>
                </tr>
                <tr id="staff-g-${staff_id}-4" class="staff-g-${staff_id} collapse-child" data.equipments-staff="${staff_id}" style="display:none">
                  <th><span class="badge btn-block  btn-danger">Commission</span> </th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','01',${data.equipments.comission.jan})">${data.equipments.comission.jan}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','02',${data.equipments.comission.feb})">${data.equipments.comission.feb}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','03',${data.equipments.comission.mar})">${data.equipments.comission.mar}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','04',${data.equipments.comission.apr})">${data.equipments.comission.apr}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','05',${data.equipments.comission.may})">${data.equipments.comission.may}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','06',${data.equipments.comission.jun})">${data.equipments.comission.jun}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','07',${data.equipments.comission.jul})">${data.equipments.comission.jul}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','08',${data.equipments.comission.aug})">${data.equipments.comission.aug}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','09',${data.equipments.comission.sep})">${data.equipments.comission.sep}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','10',${data.equipments.comission.oct})">${data.equipments.comission.oct}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','11',${data.equipments.comission.nov})">${data.equipments.comission.nov}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'equipments','12',${data.equipments.comission.dec})">${data.equipments.comission.dec}</a></th>
                  <td>${data.equipments.comission.total}</td>
                </tr>
                <tr  id="staff-g-${staff_id}-5" class="staff-g-${staff_id} collapse-child" data.equipments-staff="${staff_id}" style="display:none">
                  <th><span class="badge btn-block btn-info">Paid</span> </th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','01')">${data.equipments.paid.jan}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','02')">${data.equipments.paid.feb}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','03')">${data.equipments.paid.mar}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','04')">${data.equipments.paid.apr}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','05')">${data.equipments.paid.may}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','06')">${data.equipments.paid.jun}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','07')">${data.equipments.paid.jul}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','08')">${data.equipments.paid.aug}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','09')">${data.equipments.paid.sep}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','10')">${data.equipments.paid.oct}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','11')">${data.equipments.paid.nov}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'equipments','12')">${data.equipments.paid.dec}</a></th>
                  <td>${data.equipments.paid.total}</td>
                </tr>

                <tr class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <td colspan="14"></td>
                  <td colspan="${data.accessories.brands.length+3}" class="text-right"><button class="btn btn-primary" ${data.accessories.brands.length>0?"":'style="display:none"'} id="accessories-add-brand-button-${staff_id}" onclick="addbrand(${staff_id},'accessories')">+add brand</button></td>
                </tr>
                <tr class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <td colspan="14"></td>
                  <td colspan="${data.accessories.brands.length+3}" class="text-center"><h3>Accessories</h3></td>
                </tr>
                <tr id="staff-g-${staff_id}-6" class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <td colspan="14"></td>
                </tr>
                <tr id="staff-g-${staff_id}-7" class="staff-g-${staff_id} collapse-child" data-staff="${staff_id}" style="display:none">
                  <th> <span class="badge btn-block btn-primary">Target</span> </th>
                  <th>${data.accessories.jan}</th>
                  <th>${data.accessories.feb}</th>
                  <th>${data.accessories.mar}</th>
                  <th>${data.accessories.apr}</th>
                  <th>${data.accessories.may}</th>
                  <th>${data.accessories.jun}</th>
                  <th>${data.accessories.jul}</th>
                  <th>${data.accessories.aug}</th>
                  <th>${data.accessories.sep}</th>
                  <th>${data.accessories.oct}</th>
                  <th>${data.accessories.nov}</th>
                  <th>${data.accessories.dec}</th>
                  <td>${data.accessories.total}</td>
                </tr>
                <tr id="staff-g-${staff_id}-8" class="staff-g-${staff_id} collapse-child" data.accessories-staff="${staff_id}" style="display:none">
                  <th><span class="badge btn-block btn-warning">Achived</span> </th>
                  <th>${data.accessories.achived.jan}</th>
                  <th>${data.accessories.achived.feb}</th>
                  <th>${data.accessories.achived.mar}</th>
                  <th>${data.accessories.achived.apr}</th>
                  <th>${data.accessories.achived.may}</th>
                  <th>${data.accessories.achived.jun}</th>
                  <th>${data.accessories.achived.jul}</th>
                  <th>${data.accessories.achived.aug}</th>
                  <th>${data.accessories.achived.sep}</th>
                  <th>${data.accessories.achived.oct}</th>
                  <th>${data.accessories.achived.nov}</th>
                  <th>${data.accessories.achived.dec}</th>
                  <td>${data.accessories.achived.total}</td>
                </tr>
                <tr id="staff-g-${staff_id}-9" class="staff-g-${staff_id} collapse-child" data.accessories-staff="${staff_id}" style="display:none">
                  <th><span class="badge btn-block  btn-danger">Commission</span> </th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','01',${data.accessories.comission.jan})">${data.accessories.comission.jan}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','02',${data.accessories.comission.feb})">${data.accessories.comission.feb}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','03',${data.accessories.comission.mar})">${data.accessories.comission.mar}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','04',${data.accessories.comission.apr})">${data.accessories.comission.apr}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','05',${data.accessories.comission.may})">${data.accessories.comission.may}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','06',${data.accessories.comission.jun})">${data.accessories.comission.jun}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','07',${data.accessories.comission.jul})">${data.accessories.comission.jul}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','08',${data.accessories.comission.aug})">${data.accessories.comission.aug}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','09',${data.accessories.comission.sep})">${data.accessories.comission.sep}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','10',${data.accessories.comission.oct})">${data.accessories.comission.oct}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','11',${data.accessories.comission.nov})">${data.accessories.comission.nov}</a></th>
                  <th><a onclick="addmonthcommission(${staff_id},'accessories','12',${data.accessories.comission.dec})">${data.accessories.comission.dec}</a></th>
                  <td>${data.accessories.comission.total}</td>
                </tr>
                <tr  id="staff-g-${staff_id}-10" class="staff-g-${staff_id} collapse-child" data.accessories-staff="${staff_id}" style="display:none">
                  <th><span class="badge btn-block btn-info">Paid</span> </th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','01')">${data.accessories.paid.jan}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','02')">${data.accessories.paid.feb}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','03')">${data.accessories.paid.mar}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','04')">${data.accessories.paid.apr}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','05')">${data.accessories.paid.may}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','06')">${data.accessories.paid.jun}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','07')">${data.accessories.paid.jul}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','08')">${data.accessories.paid.aug}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','09')">${data.accessories.paid.sep}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','10')">${data.accessories.paid.oct}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','11')">${data.accessories.paid.nov}</a></th>
                  <th><a onclick="addmonthpaidtarget(${staff_id},'accessories','12')">${data.accessories.paid.dec}</a></th>
                  <td>${data.accessories.paid.total}</td>
                </tr>`)

            $.each(data.equipments.brands,function(k,v){
                $('#staff-g-'+staff_id+'-1').append(` <td  class="text-center">${v.name} <a onclick="removebrandtarget(${staff_id},'equipments',${v.id})" class="fa fa-times text-danger" data-toggle="tooltip" data-placement="top" title="Delete Brand"><a><input type="hidden" name="equipments_${staff_id}_brand[]" value="${v.id}" class="equipments_${staff_id}_brand_target"></td>`)
                $('#staff-g-'+staff_id+'-2').append(` <td  class="text-center"><a onclick="addbrandmonth(${staff_id},'equipments',${v.id})">${v.value}</a></td>`)
                $('#staff-g-'+staff_id+'-3').append(` <td  class="text-center">${v.achived.value}</td>`)
                $('#staff-g-'+staff_id+'-4').append(` <td  class="text-center"><!-- ${v.comission.value} --></td>`)
                $('#staff-g-'+staff_id+'-5').append(` <td  class="text-center"><!-- ${v.paid.value} --></td>`)
            });
            $('#staff-g-'+staff_id+'-1').append(`
                <td  class="text-center">Start</td>
                <td  class="text-center">End</td>
            `);
            $('#staff-g-'+staff_id+'-2').append(`
                  <td  class="text-center">
                    <select class="form-control" name="equipments_${staff_id}_start" id="equipments_${staff_id}_start" onchange="targetPeriod(${staff_id},'equipments')">
                      <option value="">--select--</option>
                      <option value="1" ${data.equipments.start==1?"selected":""} >Jan</option>
                      <option value="2" ${data.equipments.start==2?"selected":""} >Feb</option>
                      <option value="3" ${data.equipments.start==3?"selected":""} >Mar</option>
                      <option value="4" ${data.equipments.start==4?"selected":""} >Apr</option>
                      <option value="5" ${data.equipments.start==5?"selected":""} >May</option>
                      <option value="6" ${data.equipments.start==6?"selected":""} >Jun</option>
                      <option value="7" ${data.equipments.start==7?"selected":""} >Jul</option>
                      <option value="8" ${data.equipments.start==8?"selected":""} >Aug</option>
                      <option value="9" ${data.equipments.start==9?"selected":""} >Sep</option>
                      <option value="10" ${data.equipments.start==10?"selected":""} >Oct</option>
                      <option value="11" ${data.equipments.start==11?"selected":""} >Nov</option>
                      <option value="12" ${data.equipments.start==12?"selected":""} >Dec</option>
                    </select>
                  </td>
                  <td  class="text-center">
                    <select class="form-control" name="equipments_${staff_id}_end"  id="equipments_${staff_id}_end" onchange="targetPeriod(${staff_id},'equipments')">
                      <option value="">--select--</option>
                      <option value="1" ${data.equipments.end==1?"selected":""} >Jan</option>
                      <option value="2" ${data.equipments.end==2?"selected":""} >Feb</option>
                      <option value="3" ${data.equipments.end==3?"selected":""} >Mar</option>
                      <option value="4" ${data.equipments.end==4?"selected":""} >Apr</option>
                      <option value="5" ${data.equipments.end==5?"selected":""} >May</option>
                      <option value="6" ${data.equipments.end==6?"selected":""} >Jun</option>
                      <option value="7" ${data.equipments.end==7?"selected":""} >Jul</option>
                      <option value="8" ${data.equipments.end==8?"selected":""} >Aug</option>
                      <option value="9" ${data.equipments.end==9?"selected":""} >Sep</option>
                      <option value="10" ${data.equipments.end==10?"selected":""} >Oct</option>
                      <option value="11" ${data.equipments.end==11?"selected":""} >Nov</option>
                      <option value="12" ${data.equipments.end==12?"selected":""} >Dec</option>
                    </select>
                  </td>
            `);
            $('#staff-g-'+staff_id+'-3').append(`
                <td colspan="2"><button class="btn btn-danger btn-block"  id="equipments_${staff_id}_period" style="display:none"  onclick="updatedate(${staff_id},'equipments')">Save</button></td>
            `);
            $('#staff-g-'+staff_id+'-4').append(`
                <td colspan="2"></td>
            `);
            $('#staff-g-'+staff_id+'-5').append(`
                <td colspan="2"></td>
            `);
            $.each(data.accessories.brands,function(k,v){
                $('#staff-g-'+staff_id+'-6').append(` <td  class="text-center">${v.name} <a class="fa fa-times text-danger" onclick="removebrandtarget(${staff_id},'accessories',${v.id})"  data-toggle="tooltip" data-placement="top" title="Delete Brand"></a><input type="hidden" name="accessories_${staff_id}_brand[]" value="${v.id}" class="accessories_${staff_id}_brand_target"></td>`)
                $('#staff-g-'+staff_id+'-7').append(` <td  class="text-center"><a onclick="addbrandmonth(${staff_id},'accessories',${v.id})"> ${v.value}</a></td>`)
                $('#staff-g-'+staff_id+'-8').append(` <td  class="text-center">${v.achived.value}</td>`)
                $('#staff-g-'+staff_id+'-9').append(` <td  class="text-center"><!-- ${v.comission.value} --></td>`)
                $('#staff-g-'+staff_id+'-10').append(` <td  class="text-center"><!-- ${v.paid.value} --></td>`)
            });
            $('#staff-g-'+staff_id+'-6').append(`
                <td  class="text-center">Start</td>
                <td  class="text-center">End</td>
            `);
            $('#staff-g-'+staff_id+'-7').append(`
                  <td  class="text-center">
                    <select class="form-control" name="accessories_${staff_id}_start" id="accessories_${staff_id}_start" onchange="targetPeriod(${staff_id},'accessories')">
                      <option value="">--select--</option>
                      <option value="1" ${data.accessories.start==1?"selected":""} >Jan</option>
                      <option value="2" ${data.accessories.start==2?"selected":""} >Feb</option>
                      <option value="3" ${data.accessories.start==3?"selected":""} >Mar</option>
                      <option value="4" ${data.accessories.start==4?"selected":""} >Apr</option>
                      <option value="5" ${data.accessories.start==5?"selected":""} >May</option>
                      <option value="6" ${data.accessories.start==6?"selected":""} >Jun</option>
                      <option value="7" ${data.accessories.start==7?"selected":""} >Jul</option>
                      <option value="8" ${data.accessories.start==8?"selected":""} >Aug</option>
                      <option value="9" ${data.accessories.start==9?"selected":""} >Sep</option>
                      <option value="10" ${data.accessories.start==10?"selected":""} >Oct</option>
                      <option value="11" ${data.accessories.start==11?"selected":""} >Nov</option>
                      <option value="12" ${data.accessories.start==12?"selected":""} >Dec</option>
                    </select>
                  </td>
                  <td  class="text-center">
                    <select class="form-control" name="accessories_${staff_id}_end" id="accessories_${staff_id}_end" onchange="targetPeriod(${staff_id},'accessories')">
                      <option value="">--select--</option>
                      <option value="1" ${data.accessories.end==1?"selected":""} >Jan</option>
                      <option value="2" ${data.accessories.end==2?"selected":""} >Feb</option>
                      <option value="3" ${data.accessories.end==3?"selected":""} >Mar</option>
                      <option value="4" ${data.accessories.end==4?"selected":""} >Apr</option>
                      <option value="5" ${data.accessories.end==5?"selected":""} >May</option>
                      <option value="6" ${data.accessories.end==6?"selected":""} >Jun</option>
                      <option value="7" ${data.accessories.end==7?"selected":""} >Jul</option>
                      <option value="8" ${data.accessories.end==8?"selected":""} >Aug</option>
                      <option value="9" ${data.accessories.end==9?"selected":""} >Sep</option>
                      <option value="10" ${data.accessories.end==10?"selected":""} >Oct</option>
                      <option value="11" ${data.accessories.end==11?"selected":""} >Nov</option>
                      <option value="12" ${data.accessories.end==12?"selected":""} >Dec</option>
                    </select>
                  </td>
            `);
            $('#staff-g-'+staff_id+'-8').append(`
            <td colspan="2"><button class="btn btn-danger btn-block"  id="accessories_${staff_id}_period" style="display:none" onclick="updatedate(${staff_id},'accessories')">Save</button></td>
            `);
            $('#staff-g-'+staff_id+'-9').append(`
                <td colspan="2"></td>
            `);
            $('#staff-g-'+staff_id+'-10').append(`
                <td colspan="2"></td>
            `);
          $('.staff-g-'+staff_id).fadeIn("slow",'swing',function(){
            $('#staff-data-loader-'+staff_id).remove()
          });
          $('.staff-main-'+staff_id).fadeOut('slow');
        }else{
          $('#staff-data-loader-'+staff_id).remove()
          popup_notifyMe('error',"Staff Data loading failed");
          $('#staff-group-'+staff_id).removeClass('row-open')
        }
        
      }).fail(function(){
        $('#staff-data-loader-'+staff_id).remove()
        popup_notifyMe('error',"Staff Data loading failed");
          $('#staff-group-'+staff_id).removeClass('row-open')
      });

    }else{
      $('.staff-g-'+staff_id).fadeOut("slow",'swing',function(){
        $('.staff-g-'+staff_id).remove()
      });
      $('.staff-main-'+staff_id).fadeIn('slow');
    }
  }
  function updatedate(staff_id,type){
    $(`#${type}_${staff_id}_period`).html(" Save <i class='fa fa-spinner fa-spin'></i>")
    $.post("{{route('staff.staff.target.updatePeriod')}}",{
      year:{{$year}},
      staff_id:staff_id,
      target_type:type,
      start_month:$(`#${type}_${staff_id}_start`).val(),
      end_month:$(`#${type}_${staff_id}_end`).val()
    },function(responce){
        if(responce.success){
          popup_notifyMe('success',responce.success);
          $.each(responce.target,function(k,v){
            $(`#staff-${responce.staff_id}-${k}`).text(v);
          });
          togglestaff(responce.staff_id)
        }
        if(responce.error){
          $.each(responce.error,function(k,v){
            popup_notifyMe('error',v);
          })
        }
    }).always(function(){
      $(`#${type}_${staff_id}_period`).html(" Save ")
    })
  }
  $(function(){
    $('#year').change(function(e){
      e.preventDefault()
      window.location.href=$('#year option:selected').data("href");
      return false;
    })
    $('#add-brand-form').submit(function(e){
      e.preventDefault();
      $('#add-brand-submit-button').html(" +Add <i class='fa fa-spinner fa-spin'></i>")
      $.post($('#add-brand-form').attr("action"),$('#add-brand-form').serialize(),function(responce){
        if(responce.success){
          popup_notifyMe('success',responce.success);
          $.each(responce.target,function(k,v){
            $(`#staff-${responce.staff_id}-${k}`).text(v);
          });
          togglestaff(responce.staff_id)
          $('#modalAddBrand').modal('hide');
        }
        if(responce.error){
          $.each(responce.error,function(k,v){
            popup_notifyMe('error',v);
          })
        }
      }).always(function(){
        $('#add-brand-submit-button').html("+Add")
      })
      return false;
    })

    $('#add-brand-month-target-submit-button').click(function(){
      $('#brand-target-button').val('add')
    })
    $('#less-brand-month-target-submit-button').click(function(){
      $('#brand-target-button').val('less')
    })

    $('#add-brand-month-target-form').submit(function(e){
      e.preventDefault();
      $('#add-brand-month-target-submit-button').html(" +Add <i class='fa fa-spinner fa-spin'></i>")
      $('#less-brand-month-target-submit-button').html(" +Less <i class='fa fa-spinner fa-spin'></i>")
      $.post($('#add-brand-month-target-form').attr("action"),$('#add-brand-month-target-form').serialize(),function(responce){
        if(responce.success){
          popup_notifyMe('success',responce.success);
          $.each(responce.target,function(k,v){
            $(`#staff-${responce.staff_id}-${k}`).text(v);
          });
          togglestaff(responce.staff_id)
          $('#modalAddBrandMonthTarget').modal('hide');
        }
        if(responce.error){
          $.each(responce.error,function(k,v){
            popup_notifyMe('error',v);
          })
        }
      }).always(function(){
        $('#add-brand-month-target-submit-button').html("+Add")
        $('#less-brand-month-target-submit-button').html("+Less")
      })
      return false;
    })



    $('#remove-brand-target-form').submit(function(e){
      e.preventDefault();
      $('#remove-brand-target-form-button').html(" Delete <i class='fa fa-spinner fa-spin'></i>")
      $.post($('#remove-brand-target-form').attr("action"),$('#remove-brand-target-form').serialize(),function(responce){
        if(responce.success){
          popup_notifyMe('success',responce.success);
          $.each(responce.target,function(k,v){
            $(`#staff-${responce.staff_id}-${k}`).text(v);
          });
          togglestaff(responce.staff_id)
          $('#modalDeleteTarget').modal('hide');
        }
        if(responce.error){
          $.each(responce.error,function(k,v){
            popup_notifyMe('error',v);
          })
        }
      }).always(function(){
        $('#remove-brand-target-form-button').html(" Delete ")
      })
      return false;
    })



    $('#add-commission-month-target-form').submit(function(e){
      e.preventDefault();
      $('#add-commision-month-target-submit-button').html(" +Add <i class='fa fa-spinner fa-spin'></i>")
      $.post($('#add-commission-month-target-form').attr("action"),$('#add-commission-month-target-form').serialize(),function(responce){
        if(responce.success){
          popup_notifyMe('success',responce.success);
          $.each(responce.target,function(k,v){
            $(`#staff-${responce.staff_id}-${k}`).text(v);
          });
          togglestaff(responce.staff_id)
          $('#modalAddMonthTargetCommission').modal('hide');
        }
        if(responce.error){
          $.each(responce.error,function(k,v){
            popup_notifyMe('error',v);
          })
        }
      }).always(function(){
        $('#add-commision-month-target-submit-button').html("+Add")
      })
      return false;
    })




    $('#add-paid-month-target-form').submit(function(e){
      e.preventDefault();
      $('#add-paid-month-target-submit-button').html(" +Add <i class='fa fa-spinner fa-spin'></i>")
      $.post($('#add-paid-month-target-form').attr("action"),$('#add-paid-month-target-form').serialize(),function(responce){
        if(responce.success){
          popup_notifyMe('success',responce.success);
          $.each(responce.target,function(k,v){
            $(`#staff-${responce.staff_id}-${k}`).text(v);
          });
          togglestaff(responce.staff_id)
          $('#modalAddMonthTargetPaid').modal('hide');
        }
        if(responce.error){
          $.each(responce.error,function(k,v){
            popup_notifyMe('error',v);
          })
        }
      }).always(function(){
        $('#add-paid-month-target-submit-button').html("+Add")
      })
      return false;
    })


  //   $('.collapse-staff').click(function(){
  //     var staff_id=$(this).data("staff");
  //     $('.staff-g-'+staff_id).fadeToggle("slow")
  //   })
  })
</script>
@endsection
