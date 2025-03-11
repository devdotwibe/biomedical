
@php
$totalcommissionpaiddata=0;

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
<form action="{{route('staff.staff.target.commission.approve',$staff)}}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <table class="table" style="width:100%">  
                <thead> 
                    <tr>
                        <th >Items</th>
                        <th >Staff Commission Adusted</th>
                        @if(!empty($item->coordinator_id))
                        <th >Coordinator Commission Adusted</th>
                        @endif
                        <th >Paid Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->oppertunityOppertunityProduct as $k=> $row)
                    @if($row->approve_status=="Y" &&$row->paid_status=="N")
                    @php
                        
                        $totalcommissionpaiddata+=$row->commission+$row->coordinator_commission;

                    @endphp
                    
                    <tr>
                        <td>{{empty($row->oppertunityProduct)?"-":$row->oppertunityProduct->name}}</td>
                        <td>{{$row->commission}}</td>
                        @if(!empty($item->coordinator_id))
                        <td>{{$row->coordinator_commission}}</td>
                        @endif
                        <td>
                            <select class="form-control commission_paid" name="commission_{{$row->id}}" id="commission_paid_{{$row->id}}" >
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select> 
                        </td>
                    </tr>
                    @endif

                    @endforeach
                </tbody>    
                <tfoot>
                    <td></td>
                    <th>{{ $formatnumber($totalcommissionpaiddata) }}</th>
                    <td ></td>
                </tfoot>
            </table>
        </div>
        <div class="col-md-12">
            <button class="btn btn-warning btn-sm" type="submit">Approve</button>
        </div>
    </div>
  </form>