
@php
$totalcommissiondata=0;
$totalcoordinatorcommissiondata=0;

$formatnumber = function ($n) {
    $num="";
    $x=intval(round(($n*100)));
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
    return $x.$num;
};
@endphp
<form action="{{route('staff.staff.target.commission.add',$staff)}}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <table class="table" style="width:100%">  
                <thead>
                    <tr>
                        <th >Items</th>
                        <th >Staff Commission</th>
                        <th >Staff Commission Adust</th>
                        @if(!empty($item->coordinator_id))
                        <th >Coordinator Commission</th>
                        <th >Coordinator Commission Adust</th>
                        @endif
                    </tr>   
                </thead>
                <tbody>             
                    @foreach ($item->oppertunityOppertunityProduct as $k=> $row)
                    @if($row->approve_status=="N")
                    @php
                        $protypecat=!empty($row->oppertunityProduct)&&!empty($row->oppertunityProduct->category_type_id)?(\App\Category_type::where('id',$row->oppertunityProduct->category_type_id)->orderBy('id','DESC')->first()):null;
                        $msp=\App\Msp::where('product_id',$row->product_id)->orderBy('id','DESC')->first();

                        $netamount=intval($row->quantity)*intval($row->sale_amount);
                        
                        $commission=0;
                        $coordinatorCommission=0;
                        if(!empty($protypecat)&&!empty($msp)){
                            if($msp->pro_msp>0){
                                if($protypecat->staff_commision>0){
                                    $commission=$protypecat->staff_commision*($netamount -($msp->pro_msp*$row->quantity))/100;
                                }else{
                                    $commission=0.5*($netamount -($msp->pro_msp*$row->quantity))/100;
                                }
                            }
                            if(!empty($item->coordinator_id)&&$msp->pro_msp>0){
                                if($protypecat->coordinator_commision>0){
                                    $coordinatorCommission=$protypecat->coordinator_commision*($netamount -($msp->pro_msp*$row->quantity))/100;
                                }
                                else{
                                    $coordinatorCommission=0.5*($netamount -($msp->pro_msp*$row->quantity))/100;
                                }
                            }
                        }
                        $totalcommissiondata+=$commission;
                        $totalcoordinatorcommissiondata+=$coordinatorCommission;
                        

                    @endphp
                    
                    <tr>
                        <td>{{empty($row->oppertunityProduct)?"-":$row->oppertunityProduct->name}}</td>
                        <td>{{ $formatnumber( $commission)}} [{{($protypecat->staff_commision??0)>0?sprintf("%0.2f",$protypecat->staff_commision,2):"0.50"}}%]</td>
                        <td><input type="text" class="form-control commission_approve" name="commission_{{$row->id}}" id="commission_approve_{{$row->id}}"  value="{{round($commission,2)}}" > </td>
                        @if(!empty($item->coordinator_id))
                        <td>{{ $formatnumber( $coordinatorCommission)}} [{{($protypecat->coordinator_commision??0)>0?sprintf("%0.2f",$protypecat->coordinator_commision,2):"0.50"}}%]</td>
                        <td><input type="text" class="form-control coordinator_commission_approve" name="coordinator_commission_{{$row->id}}" id="coordinator_commission_approve_{{$row->id}}"  value="{{round($coordinatorCommission,2)}}" > </td>
                        @endif
                    </tr>
                    @endif

                    @endforeach
                </tbody>    
                <tfoot>
                    <td></td>
                    <th>{{ $formatnumber($totalcommissiondata) }}</th>
                    <th id="commission-approved-amount">{{round($totalcommissiondata,2)}}</th>
                    @if(!empty($item->coordinator_id))
                    <th>{{ $formatnumber($totalcoordinatorcommissiondata) }}</th>
                    <th id="coordinator-commission-approved-amount">{{round($totalcoordinatorcommissiondata,2)}}</th>
                    @endif
                </tfoot>
            </table>
        </div>
        <div class="col-md-12">
            <button class="btn btn-success btn-sm" type="submit">Approve</button>
        </div>
    </div>
  </form>