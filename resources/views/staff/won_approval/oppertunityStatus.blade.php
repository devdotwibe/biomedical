<div class="row">
    <div class="col-md-12">
        <table class="table " style="width:100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Comment</th>
                    <th>Attachement</th>
                    <th>Stage</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($oppertunitystatus as $k=> $item)
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>@if(!empty($item->staff)) {{$item->staff->name}} @else Admin @endif</td>
                        <td>{{$item->approve_comment}}</td>
                        <td>
                            @foreach ($item->attachmens as $atch)
                                <a href="{{$atch->attach_url}}" target="_blank" rel="noopener noreferrer" download="download"><object data="{{$atch->attach_url}}" type="{{$atch->attach_type}}" width="40"></object> download</a>
                            @endforeach
                        </td>
                        <td>{{$item->approve_stage}}</td>
                        <td>{{\Carbon\Carbon::parse($item->created_at)->format("Y-m-d H:i:s")}}</td>
                        <td>
                            @if($item->status=="Y" && $item->approve_status=="Approve") 
                                Approved 
                            @elseif($item->approve_status=="Approve") 
                                Approve closed  by @if(!empty($item->closedStaff)) {{$item->closedStaff->name}} @else Admin @endif @if(!empty($item->closed_at)) on  {{\Carbon\Carbon::parse($item->closed_at)->format("Y-m-d H:i:s")}} @endif
                            @else
                                Reject 
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>