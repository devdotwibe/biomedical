

@extends('staff/layouts.app')

@section('title', 'Manage Staff Sales Commission')

@section('content')

<section class="content-header">
  <h1>
    Manage Staff Sales Commission 
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Staff Sales Commission </li>
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

            @php
            
                $formatnumber = function ($n) {
                    $num="";
                    $prefix="";
                    $x=intval(round(($n*100)));
                    if($x<0){
                        $prefix="-";
                        $x=$x*-1;
                    }

                    $num='.'.sprintf('%02d',$x%100);
                    $x=intval($x/100);
                    if($x>=1000){
                    $num=','.sprintf('%03d',$x%1000).$num;
                    $x=intval($x/1000);

                    if($x>=100){
                        $num=','.sprintf('%02d',$x%100).$num;
                        $x=intval($x/100);
                        if($x>=100){
                            $num=','.sprintf('%02d',$x%100).$num;
                            $x=intval($x/100);
                        }
                    }
                    }
                    return $prefix.$x.$num;
                };
                $arrayfilter = function ($array,$fun) {
                    $newarray=[];
                    foreach ($array as $key=>$value) {
                        if($fun($key,$value)){
                            array_push($newarray,$value);
                        }
                    }
                    return $newarray;
                }
            @endphp
            <div class="box-body">          
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn @if(empty($commission_status)) btn-warning @else btn-success @endif" href="{{route('staff.staff.target.commission.index',['staff'=>$staff,"period"=>$period ,"status"=>$status,"complete"=>$complete])}}">All</a>
                        
                        {{-- @foreach (['New Orders','In Process','Billed/Documentation','Supply issues','Closed Orders','Lost / Cancelled'] as $dts)
                        <a class="btn @if($dts==$commission_status) btn-warning @else btn-success @endif" href="{{route('staff.staff.target.commission.index',['staff'=>$staff,"period"=>$period ,"status"=>$status,"complete"=>$complete,"commission_status"=>$dts])}}">{{$dts}}</a>
                        @endforeach --}}


                        @foreach ($statuslist as $k=> $dts)
                            <a class="btn @if($dts["name"]==$commission_status) btn-warning @elseif($dts["count"]>0) btn-danger @else btn-success @endif" href="{{route('staff.staff.target.commission.index',['staff'=>$staff,"period"=>$period ,"status"=>$status,"complete"=>$complete,"commission_status"=>$dts["name"]])}}">{{$dts["display"]}}  @if($dts["count"]>0) [{{$dts["count"]}}] @endif </a>
                            
                        @endforeach

                        @if(empty($complete))    
                            <a class="btn btn-warning pull-right float-end" target="_blank" href="{{route('staff.staff.target.commission.index',['staff'=>$staff,"period"=>$period ,"status"=>$status,'commission_status'=>$commission_status,"complete"=>"complete"])}}">Completed List</a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive ">
                            <table class="table table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Sort by Engineer</th>
                                        <th>
                                            <select name="engineer" id="engineerFilter" class="form-control">
                                                <option value="{{route('staff.staff.target.commission.index',["complete"=>$complete])}}" >--select--</option>
                                                @foreach ($staffs as $stf)
                                                    <option value="{{route('staff.staff.target.commission.index',['staff'=>$stf->id,'commission_status'=>$commission_status,"complete"=>$complete])}}" @if($staff==$stf->id) selected @endif >{{$stf->name}}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>Sort by Period</th>
                                        <th>
                                            @if($staff>0)
                                                <select name="period" id="dateFilter" class="form-control "  data-placeholder="Select an Period" >
                                                    <option value="{{route('staff.staff.target.commission.index',['staff'=>$staff,'status'=>$status,'commission_status'=>$commission_status,'complete'=>$complete])}}">Select an Period</option>
                                                    <option value="{{route('staff.staff.target.commission.index',['staff'=>$staff,'status'=>$status,'commission_status'=>$commission_status,'complete'=>$complete,"period"=>"1st Quarter"])}}" @if($period=="1st Quarter") selected @endif >1<sup>st</sup> Quarter</option>    
                                                    <option value="{{route('staff.staff.target.commission.index',['staff'=>$staff,'status'=>$status,'commission_status'=>$commission_status,'complete'=>$complete,"period"=>"2nd Quarter"])}}" @if($period=="2nd Quarter") selected @endif >2<sup>nd</sup> Quarter</option>    
                                                    <option value="{{route('staff.staff.target.commission.index',['staff'=>$staff,'status'=>$status,'commission_status'=>$commission_status,'complete'=>$complete,"period"=>"3rd Quarter"])}}" @if($period=="3rd Quarter") selected @endif >3<sup>rd</sup> Quarter</option>    
                                                    <option value="{{route('staff.staff.target.commission.index',['staff'=>$staff,'status'=>$status,'commission_status'=>$commission_status,'complete'=>$complete,"period"=>"4th Quarter"])}}" @if($period=="4th Quarter") selected @endif>4<sup>th</sup> Quarter</option>                                    
                                                </select>
                                            @endif
                                        </th>
                                        <th></th>
                                        <th>Search</th>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            Won Status
                                        </th>
                                        <th colspan="3">
                                            @if($staff>0)
                                            <select name="date" id="statusFilter" class="form-control">
                                                <option value="{{route('staff.staff.target.commission.index',['staff'=>$staff,"period"=>$period,'commission_status'=>$commission_status,"complete"=>$complete])}}" >--select--</option>
                                                @foreach (['Won','Cancel','Lost'] as $dts)
                                                    <option value="{{route('staff.staff.target.commission.index',['staff'=>$staff,"period"=>$period,"status"=>$dts,'commission_status'=>$commission_status,"complete"=>$complete])}}"  @if($status==$dts) selected @endif  >{{$dts}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                @foreach ($data as $item)
                                    @php
                                    
                                        $totalcommission=0;
                                        $totalcommissiondue=0;
                                        $totalcommissionpaid=0;
                                        $totalproductcost=0;   
                                        $nettotalamount=0;
                                        $profitamounttotal=0;
                                    @endphp
                                <tbody >
                                    <tr>
                                        <th>Engineer Name</th>
                                        <td rowspan="{{count($item->oppertunityOppertunityProduct)+1}}">
                                            <div class="order-date"><strong>Order Date </strong> <br> {{empty($item->oppertunityOrder)?"-":$item->oppertunityOrder->order_date}}</div>
                                            <div class="opp-date"><strong>Opp. Date</strong> <br> {{\Carbon\Carbon::parse($item->created_at)->format('Y-m-d')}}</div>
                                            <div class="order-recive-date"><strong>Order Received Date </strong> <br> {{empty($item->oppertunityOrder)?"-":$item->oppertunityOrder->order_recive_date}}</div>
                                        </td>
                                        <td rowspan="{{count($item->oppertunityOppertunityProduct)+1}}">
                                            <div class="roder-number"><strong> Order Number</strong><br>{{empty($item->oppertunityOrder)?"-":$item->oppertunityOrder->order_no}} </div>
                                            <div class="opp-no"><a href="{{url('staff/list_oppertunity_products/'.$item->id)}}" target="_blank" rel="noopener noreferrer"><strong>Oppurtunity</strong> <br>{{$item->op_reference_no}}</a></div>
                                        </td>
                                        <th>Customer Name<br> & Address</th>
                                        <th>Item</th>
                                        <th>Brand</th>
                                        <th>Rate</th>
                                        <th>MSP</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                        <th>Net Amount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                        <th>Difference(Amount*Qty-MSP*Qty)</th>
                                        <th>Deviation(Rate*Qty-MSP*Qty)</th>
                                        <th colspan="2">
                                            Commission
                                            @if(!empty($item->coordinator_id))
                                            <table>
                                                <tr>
                                                    <th>Staff</th>
                                                    <th>Coordinator</th>
                                                </tr>
                                            </table>
                                            @endif
                                        </th>
                                        <th colspan="2">
                                            Commission Due
                                            @if(!empty($item->coordinator_id))
                                            <table>
                                                <tr>
                                                    <th>Staff</th>
                                                    <th>Coordinator</th>
                                                </tr>
                                            </table>
                                            @endif
                                        </th>
                                        <th colspan="2">
                                            Commission Paid
                                            @if(!empty($item->coordinator_id))
                                            <table>
                                                <tr>
                                                    <th>Staff</th>
                                                    <th>Coordinator</th>
                                                </tr>
                                            </table>
                                            @endif
                                        </th>
                                        <th>Commission Paid Date</th>
                                    </tr>
                                    @foreach ($item->oppertunityOppertunityProduct as $k=> $row)
                                        <tr>
                                            @if($k==0)
                                            <td rowspan="{{count($item->oppertunityOppertunityProduct)}}">{{empty($item->staff)?"":$item->staff->name}}</td>                                
                                            <td rowspan="{{count($item->oppertunityOppertunityProduct)}}">{{empty($item->customer)?"":$item->customer->business_name}}</td>
                                            @endif
                                            <td>{{empty($row->oppertunityProduct)?"-":$row->oppertunityProduct->name}}</td>
                                            <td>{{empty($row->oppertunityProduct)?"-":$row->oppertunityProduct->brand_name}}</td>
                                            <td>
                                                @php

                                                $protypecat=!empty($row->oppertunityProduct)&&!empty($row->oppertunityProduct->category_type_id)?(\App\Category_type::where('id',$row->oppertunityProduct->category_type_id)->orderBy('id','DESC')->first()):null;


                                                
                                                $msp=\App\Msp::where('product_id',$row->product_id)->orderBy('id','DESC')->first();
                                                $total=intval($row->quantity)*intval($row->sale_amount);
                                                $netamount=$total;
                                                $nettotalamount+=$netamount;
                                                $deviation=0;
                                                $profitamount=$netamount;
                                                if(!empty($row->oppertunityProduct)&&$row->oppertunityProduct->tax_percentage>0&&$row->approve_status=="N"){
                                                    $total+=($total*$row->oppertunityProduct->tax_percentage/100);
                                                    // $deviation=(intval($row->oppertunityProduct->unit_price)>0?intval($row->oppertunityProduct->unit_price):0.00)*intval($row->quantity);
                                                }elseif($row->approve_status=="Y"&&$row->tax_percentage>0){
                                                    $total+=($total*$row->tax_percentage/100);
                                                    // $deviation=($row->unit_price>0?$row->unit_price:0.00)*$row->quantity;
                                                }
                                                $commission=0;
                                                $coordinatorCommission=0;
                                                if(!empty($msp)&&$row->approve_status=="N"){
                                                    $deviation+=(($msp->total_cost*$row->quantity)-($msp->pro_msp*$row->quantity));
                                                    // if($msp->incentive>0&&$msp->pro_msp>0){
                                                    //     $commission=$msp->incentive*($msp->pro_msp-$msp->total_cost)/100;
                                                    // }
                                                    $profitamount=($netamount -($msp->pro_msp*$row->quantity));
                                                    if(!empty($protypecat)&&$protypecat->staff_commision>0&&$msp->pro_msp>0){
                                                        
                                                        $commission=$protypecat->staff_commision*($netamount -($msp->pro_msp*$row->quantity))/100;
                                                        // $commission=$msp->incentive*($msp->pro_msp-$msp->total_cost)/100;
                                                    }
                                                    if(!empty($item->coordinator_id)&&!empty($protypecat)&&$protypecat->coordinator_commision>0&&$msp->pro_msp>0){
                                                        $coordinatorCommission=$protypecat->coordinator_commision*($netamount -($msp->pro_msp*$row->quantity))/100;
                                                        // $coordinatorCommission=$protypecat->coordinator_commision*($msp->pro_msp-$msp->total_cost)/100;
                                                    }
                                                }elseif($row->approve_status=="Y"&&$row->pro_msp>0){
                                                    $deviation+=(($row->total_cost*$row->quantity)-($row->pro_msp*$row->quantity));
                                                    $profitamount=($netamount -($row->pro_msp*$row->quantity));
                                                    if($row->incentive>0&&$row->pro_msp>0){
                                                        $commission=$row->incentive*($netamount -($row->pro_msp*$row->quantity))/100;
                                                    }
                                                    if(!empty($item->coordinator_id)&&$row->coordinator_incentive>0&&$row->pro_msp>0){
                                                        $coordinatorCommission=$row->coordinator_incentive*($netamount -($row->pro_msp*$row->quantity))/100;
                                                    }
                                                }
                                                $profitamounttotal+=$profitamount;
                                                $totalproductcost+=$total;
                                                $totalcommission+=$commission+$coordinatorCommission;
                                                if($row->approve_status=="Y"){
                                                    $totalcommissiondue+=$row->commission;
                                                    $totalcommissiondue+=$row->commission;
                                                }
                                                if($row->paid_status=="Y"){
                                                    $totalcommissionpaid+=$row->commission;
                                                    $totalcommissionpaid+=$row->commission;
                                                }
                                                @endphp
                                                @if($row->approve_status=="Y")
                                                {{$row->total_cost}}
                                                @else
                                                {{empty($msp)?"0.00":$msp->total_cost}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($row->approve_status=="Y")
                                                {{$row->pro_msp}}
                                                @else
                                                {{empty($msp)?"":$msp->pro_msp}} 
                                                @endif
                                            </td>
                                            <td>{{$row->quantity}}</td>
                                            <td>{{$row->sale_amount}}</td>
                                            <td>{{$formatnumber($netamount)}}</td>
                                            <td>

                                                @if($row->approve_status=="Y")
                                                {{$row->tax_percentage}}
                                                @else
                                                {{empty($row->oppertunityProduct)?"":$row->oppertunityProduct->tax_percentage }}
                                                @endif
                                            </td>
                                            <td>{{$formatnumber($total)}}</td>
                                            <td>{{$formatnumber($profitamount)}}</td>
                                            <td>{{$formatnumber($deviation)}}</td>
                                            
                                            <td>{{ $formatnumber( $commission)}}</td>
                                            <td>@if(!empty($item->coordinator_id)){{ $formatnumber( $coordinatorCommission)}}@endif</td>
                                            
                                            <td>@if($row->approve_status=="Y") {{ $formatnumber($row->commission) }} @endif</td>
                                            <td>@if(!empty($item->coordinator_id)&&$row->approve_status=="Y") {{ $formatnumber($row->coordinator_commission) }} @endif</td>
                                            
                                            <td>@if($row->paid_status=="Y") {{ $formatnumber($row->commission) }} @endif</td>
                                            <td>@if(!empty($item->coordinator_id)&&$row->paid_status=="Y") {{ $formatnumber($row->coordinator_commission) }} @endif</td>

                                            <td>@if($row->paid_status=="Y") {{ $row->paid_at }} @endif</td>
                                        </tr> 
                                    @endforeach  
                                    @if(count($item->oppertunityOppertunityProduct)>0)
                                    <tr>
                                        <th>
                                            {{-- <button class="badge btn-info btn-sm" onclick="opurtunityattachement({{$item->id}},'{{route('staff.staff.target.commission.attaches',$item->id)}}',this)"> Attach <i class="fa fa-paperclip" aria-hidden="true"></i> </button> --}}

                                            <button class="badge btn-success btn-sm" onclick="changecommissionstatus('{{route('staff.oppertunity.approve.edit',['oppertunity'=>$item->id])}}','{{$item->commission_status}}',this)" >Change Status</button>
                                        </th>
                                        <th colspan="2">
                                            {{-- <select name="commission_status" id="commission-status-{{$item->id}}" class="form-control">
                                                <!-- <option value="" >--select--</option> -->
                                                @foreach (['New Orders','In Process','Billed/Documentation','Supply issues','Closed Orders','Lost / Cancelled'] as $dts)
                                                    <option value="{{$dts}}" @if($dts==$item->commission_status) selected @endif >{{$dts}}</option>
                                                @endforeach
                                            </select> --}}

                                            {{-- <label for="complete-check-item-{{$item->id}}"><input type="checkbox" @if($item->complete_status=="Y") checked @endif value="{{$item->id}}" onchange="completeStatusChange({{$item->id}},this.checked)"> Completed</label> --}}
                                        </th>
                                        <th>
                                            {{-- <button class="badge btn-success btn-sm" onclick="changecommissionstatus('{{route('staff.staff.target.commission.status',['staff'=>$staff,'oppertunity'=>$item->id])}}','#commission-status-{{$item->id}}',this)" >Save Status</button> --}}
                                            <button class="badge btn-success btn-sm" onclick="showoppertunitystatus('{{route('staff.oppertunity.approve.history',['oppertunity'=>$item->id])}}',this)" > Status History </button>                                            
                                            
                                        </th>
                                        <th>
                                            
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>{{$formatnumber($nettotalamount)}}</th>
                                        <th></th>
                                        <th>{{$formatnumber($totalproductcost)}}</th>
                                        <th>{{$formatnumber($profitamounttotal)}}</th>
                                        <th></th>
                                        <th  colspan="2">{{ $formatnumber($totalcommission)}}</th>
                                        <th  colspan="2">{{ $formatnumber($totalcommissiondue)}}</th>
                                        <th  colspan="2">{{ $formatnumber($totalcommissionpaid)}}</th>
                                        <th></th>
                                    </tr>
                                    @if($staff>0)
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="2">@if(count($arrayfilter($item->oppertunityOppertunityProduct,function($k,$v){
                                            return ($v->approve_status=="N");
                                        }))>0) <button class="btn btn-success btn-block" onclick="addcommission('{{route('staff.staff.target.commission.view',['staff'=>$staff,'id'=>$item->id])}}',this)" >Approve</button> @endif</th>
                                        <th  colspan="2">@if(count($arrayfilter($item->oppertunityOppertunityProduct,function($k,$v){
                                            return ($v->approve_status=="Y"&&$v->paid_status=="N");
                                        }))>0) <button class="btn btn-danger btn-block" onclick="addcommissionpay('{{route('staff.staff.target.commission.payview',['staff'=>$staff,'id'=>$item->id])}}',this)" >Approve</button> @endif</th>
                                        <th  colspan="2"></th>
                                        <th></th>
                                    </tr>
                                    @endif
                                @endif     
                                </tbody>

                                @endforeach
                                
                            </table>
                        </div>
                    </div>
                </div>
                
                @if($staff>0)
                {{ $data->appends(["sort"=>$sort,"order"=>$order,'staff'=>$staff,"status"=>$status,"period"=>$period,'commission_status'=>$commission_status,"complete"=>$complete])->links() }}
                @else
                {{ $data->appends(["sort"=>$sort,"order"=>$order,'complete'=>$complete])->links() }}
                @endif
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


<div class="modal fade" id="modalOppertunityAttachements">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div>
                <form action="{{route('staff.staff.target.commission.attacheadd')}}" method="post" id="uploadattachement-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="uploadattachement" class="form-control btn "> + Upload Attachment </label>
                            <meter name="uploadattachement-meter" id="uploadattachement-meter" class="text-center col-12 m-2" value="0" min="0" max="100" style="display: none">0%</meter>
                            <input type="file" name="uploadattachement" id="uploadattachement" style="display: none">
                        </div>
                    </div>
                    <input type="hidden" name="oppertunity_id" id="uploadattachement-oppertunity-id" value="0">
                </form>
            </div>
            <div id="oppertunityAttachementsArea" class="row">

            </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modalApproveOpportunityShow">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3>Opportunity</h3>
            <div id="approveOpportunityShowArea">
                
            </div>
        </div>
      </div>
    </div>
  </div>
  

  <div class="modal fade" id="modalApproveOpportunity">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3>Update Status</h3>
            <div id="approveOpportunityArea">
                
            </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@if($staff>0)

<div class="modal fade" id="modalApproveCommisiion">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        {{-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div> --}}
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div id="approveCommissionArea">

            </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif

@if($staff>0)

<div class="modal fade" id="modalPaidApproveCommisiion">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        {{-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div> --}}
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div id="paidApproveCommisiionArea">

            </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
@endif
<script type="text/javascript">
    var attache_id=0;
    function showoppertunitystatus(url,button){
        $(button).html("  Status History  <i class='fa fa-spinner fa-spin'></i> ")
        $.get(url,function(res){
            if(res){
                $('#approveOpportunityShowArea').html(res)
                $('#modalApproveOpportunityShow').modal('show')
            }
        }).always(function(){
            $(button).html(`  Status History `)
        });
    }
    function opurtunityattachement(id,url,button){
        $(button).html(" Attach <i class='fa fa-spinner fa-spin'></i> ")
        $.get(url,function(res){
            if(res){
                $('#oppertunityAttachementsArea').html("")
                $('#uploadattachement-oppertunity-id').val(id)
                $('#modalOppertunityAttachements').modal('show')
                $.each(res,function(k,v){
                    $('#oppertunityAttachementsArea').append(`
                        <div class="col-md-3 text-center" id="attache-id-${attache_id}" >
                            <button type="button" class="badge btn-danger close" aria-label="Remove" onclick="removeAttachement(${attache_id},${v.id})"> <span aria-hidden="true">&times;</span> </button>
                            <img class="img img-thumbnail" src="${v.imgurl}" />
                        </div>
                    `);
                    attache_id++;
                })
            }
        }).always(function(){
            $(button).html(` Attach <i class="fa fa-paperclip" aria-hidden="true"></i> `)
        })
    }
    @if($staff>0)
    function addcommission(url,button){
        $(button).html("Approve <i class='fa fa-spinner fa-spin'></i>")
        $.get(url,function(res){
            if(res.item){
                $('#approveCommissionArea').html(res.view)
                $('#modalApproveCommisiion').modal('show')
            }
        }).always(function(){
            $(button).html("Approve")
        })
    }

    function addcommissionpay(url,button){
        $(button).html("Approve <i class='fa fa-spinner fa-spin'></i>")
        $.get(url,function(res){
            if(res.item){
                $('#paidApproveCommisiionArea').html(res.view)
                $('#modalPaidApproveCommisiion').modal('show')
            }
        }).always(function(){
            $(button).html("Approve")
        })
    }


    $(document).on('change keyup','.commission_approve',function(){
        var total=0.0;
        $('.commission_approve').each(function(){
            if($(this).val()>0){
                total+=parseFloat($(this).val())
            }                
        });
        $('#commission-approved-amount').text(total.toFixed(2))

    })
    $(document).on('change keyup','.coordinator_commission_approve',function(){
        var total=0.0;
        $('.coordinator_commission_approve').each(function(){
            if($(this).val()>0){
                total+=parseFloat($(this).val())
            }                
        });
        $('#coordinator-commission-approved-amount').text(total.toFixed(2))

    })
    @endif

    function changecommissionstatus(url,cval,button){


        $(button).html(" Change Status  <i class='fa fa-spinner fa-spin'></i> ")
        $.get(url,function(res){
            if(res){
                $('#approveOpportunityArea').html(res)
                $('#modalApproveOpportunity').modal('show')
            }
        }).always(function(){
            $(button).html(` Change Status `)
        });
/*
        var availableStatus=['New Orders',"Initial Check",'Technical Approval','Finance Approval','Billing','Dispatch','Documentation','Supply Issue','Payment Follow Up'];
        var idx=$.inArray(cval,availableStatus);
        if(idx!=-1&&availableStatus.length>(idx+1)){
            cval=availableStatus[idx+1];
        }

        $('#approveOpportunityForm').attr("action",url);
        // if($(`input[name="approve_stage"][value="${cval}"].approve_stage`).length>0){
        //     $(`input[name="approve_stage"].approve_stage`).val(cval)
        // }else{
        //     $(`input[name="approve_stage"].approve_stage`).val("")
        // }
        $(`input[name="approve_stage"].approve_stage`).prop('checked', false);
        $(`input[name="approve_stage"][value="${cval}"].approve_stage`).prop('checked', true);
        $('#approve_comment').val("")
        $('#approve_status').val("")
        $("small.error").html("")
        $("#approve_attachment_preview").html()
        $('#modalApproveOpportunity').modal('show')

*/
        // $(button).html("Change Status <i class='fa fa-spinner fa-spin'></i>")
        // $.get(url,function(res){
        //     window.location.href="{!! request()->fullUrl() !!}";
        // }).always(function(){
        //     $(button).html("Change Status")
        // })
    }

    
    function completeStatusChange(id,status){
        $.get("{{route('staff.staff.target.commission.completestatus')}}",{id:id,status:(status?"Y":"N")},function(res){
            @if(!empty($complete))     
            if(res.complete_status&&res.complete_status=="N"){
                window.location.href="{{request()->fullUrl()}}";
            }
            @endif
        });
    }
    
    $(function(){
        $('#engineerFilter').change(function(){
            if($(this).val()!=""){
                window.location.href=$(this).val();
            }
        })
        $('#dateFilter').change(function(){
            if($(this).val()!=""){
                window.location.href=$(this).val();
            }
        })
        $('#statusFilter').change(function(){
            if($(this).val()!=""){
                window.location.href=$(this).val();
            }
        })

        $(document).on('change','#approve_status',function(){
            if($('#approve_status').val()=="Approve"){                
                $('input[name="approve_stage"].approve_stage').each(function(){
                    if(currentOppertunityStatus&&currentOppertunityStatus[$(this).val()]&&currentOppertunityStatus[$(this).val()]["approve_status"]=="Approve"&&currentOppertunityStatus[$(this).val()]["status"]=="Y")
                    {
                        $(this).prop("disabled",true).prop("checked",false);
                    }else{
                        if($(this).data('ban')){
                            $(this).prop("disabled",true).prop("checked",false);
                        }else{
                            $(this).prop("disabled",false);
                        }
                    }
                })
            }else{
                $('input[name="approve_stage"].approve_stage').each(function(){
                    if($(this).data('ban')){
                        $(this).prop("disabled",true).prop("checked",false);
                    }else{
                        $(this).prop("disabled",false);
                    }
                })
            }
        })
        $(document).on("submit",'#approveOpportunityForm',function(e){
            $('small.error').html("")
            e.preventDefault()
            $('#approveOpportunityFormSubmitButton').prop("disabled",true);
            $('#approveOpportunityFormSubmitButton').html(" Submit  <i class='fa fa-spinner fa-spin'></i> ")
            $.post($(this).attr("action"),$(this).serialize(),function(res){
                window.location.href="{!! request()->fullUrl() !!}";
            },'json').fail(function(xhr){
                if(xhr.responseJSON&&xhr.responseJSON.errors){
                    $.each(xhr.responseJSON.errors,function(k,v){
                        $(`#erorr-${k}-message`).html(v[0])
                    });
                }
            }).always(function(){
                $('#approveOpportunityFormSubmitButton').prop("disabled",false);
                $('#approveOpportunityFormSubmitButton').html("Submit")
            })
            return false;
        })
        $(document).on("change","#approve_attachment",function(e){
            var files=e.target.files;
            if(files.length>0){
                var formData = new FormData();              
                formData.append('upload_file',files[0]);  
                $('#approveOpportunityFormSubmitButton').prop("disabled",true);
                $("#approve_attachment-meter").show()
                $.ajax({
                    url : "{{route('staff.oppertunity.approve.attachement')}}",
                    type : 'POST',
                    data : formData,
                    dataType: "json",
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload) {
                            myXhr.upload.addEventListener('progress', function(event){
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                var progress=0.0;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(position / total * 100);
                                    progress=percent/100;
                                }
                                $("#approve_attachment-meter").text(percent + "%");
                                $("#approve_attachment-meter").val(progress);
                            }, false);
                        }
                        return myXhr;
                    },
                    complete:function(){
                        $("#approve_attachment-meter").hide()
                $('#approveOpportunityFormSubmitButton').prop("disabled",false);
                    },
                    error:function(xhr, status, error){
                        if(xhr.responseJSON&&xhr.responseJSON.errors){
                            $.each(xhr.responseJSON.errors,function(k,v){
                                $(`#erorr-${k}-message`).html(v[0])
                            });
                        }
                    },
                    success : function(data) {
                       if(data.name){
                        $('#approve_attachment_preview').append(`
                            <div class="col-md-4">
                                <input type="hidden" name="file_name[]" value="${data.name}"/>
                                <input type="hidden" name="display_name[]" value="${data.file_name}"/>
                                <input type="hidden" name="file_type[]" value="${data.mime_type}"/>
                                <input type="hidden" name="file_path[]" value="${data.path}"/>
                                <input type="hidden" name="file_url[]" value="${data.url}"/>
                                <object data="${data.url}" type="${data.mime_type}" width="100%"></object>
                            </div>
                        `);
                       }
                    }
                });
                
            }
        })
    })
</script>
@endsection
