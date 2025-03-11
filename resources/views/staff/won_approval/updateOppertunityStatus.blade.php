
@php
$staff_id = session('STAFF_ID');

$adminA=["33","36",'37',"31"];

$adminB=["39","30","32"];
@endphp
<form action="{{route('staff.oppertunity.approve.status',['oppertunity'=>$item->id])}}" method="post" id="approveOpportunityForm">
    @csrf
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="approve_status">Select Status</label>
            <select name="approve_status" id="approve_status" class="form-select">
                <option value="">--Please Select--</option>
                <option value="Approve">Approve</option>
                <option value="Reject">Reject</option>
            </select>
            <small class="error text-danger" id="erorr-approve_status-message"></small>
        </div>
        <div class="form-group">
            <label for="approve_stage">Select your option</label>
            <div class="form-check">
                <input class="form-check-input approve_stage" type="radio" name="approve_stage" id="approve_stage_1" value="Initial Check" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders") checked @endif>
                <label class="form-check-label" for="approve_stage_1">
                    Initial Check   @if(!empty($oppertunitystatus["Initial Check"])) 
                                                    @if($oppertunitystatus["Initial Check"]->approve_status=="Approve") 
                                                        @if($oppertunitystatus["Initial Check"]->status=="Y")
                                                            <strong class="text-success">Approved</strong>
                                                        @else
                                                            <strong class="text-warning">Approve Closed</strong>
                                                        @endif
                                                    @else
                                                        <strong class="text-danger">Rejected</strong>
                                                    @endif
                                                @endif
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders") approve_disabled @endif " type="radio" name="approve_stage" id="approve_stage_2" value="Technical Approval" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Initial Check") checked @endif>
                <label class="form-check-label" for="approve_stage_2">
                    Technical Approval   @if(!empty($oppertunitystatus["Technical Approval"])) 
                                            @if($oppertunitystatus["Technical Approval"]->approve_status=="Approve") 
                                                @if($oppertunitystatus["Technical Approval"]->status=="Y")
                                                    <strong class="text-success">Approved</strong>
                                                @else
                                                    <strong class="text-warning">Approve Closed</strong>
                                                @endif
                                            @else
                                                <strong class="text-danger">Rejected</strong>
                                            @endif
                                        @endif
                </label>
            </div>
            @if(in_array($staff_id,$adminB))
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check") approve_disabled @endif  " type="radio" name="approve_stage" id="approve_stage_3" value="Finance Approval" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Technical Approval") checked @endif>
                <label class="form-check-label" for="approve_stage_3">
                    Finance Approval   @if(!empty($oppertunitystatus["Finance Approval"])) 
                                            @if($oppertunitystatus["Finance Approval"]->approve_status=="Approve") 
                                                @if($oppertunitystatus["Finance Approval"]->status=="Y")
                                                    <strong class="text-success">Approved</strong>
                                                @else
                                                    <strong class="text-warning">Approve Closed</strong>
                                                @endif
                                            @else
                                                <strong class="text-danger">Rejected</strong>
                                            @endif
                                        @endif
                </label>
            </div>
            @endif
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") approve_disabled @endif " type="radio" name="approve_stage" id="approve_stage_4" value="Billing" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Finance Approval") checked @endif>
                <label class="form-check-label" for="approve_stage_4">
                    Billing     @if(!empty($oppertunitystatus["Billing"])) 
                                    @if($oppertunitystatus["Billing"]->approve_status=="Approve") 
                                        @if($oppertunitystatus["Billing"]->status=="Y")
                                            <strong class="text-success">Approved</strong>
                                        @else
                                            <strong class="text-warning">Approve Closed</strong>
                                        @endif
                                    @else
                                        <strong class="text-danger">Rejected</strong>
                                    @endif
                                @endif
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") approve_disabled @endif " type="radio" name="approve_stage" id="approve_stage_5" value="Dispatch" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Billing") checked @endif>
                <label class="form-check-label" for="approve_stage_5">
                    Dispatch   @if(!empty($oppertunitystatus["Dispatch"])) 
                                    @if($oppertunitystatus["Dispatch"]->approve_status=="Approve") 
                                        @if($oppertunitystatus["Dispatch"]->status=="Y")
                                            <strong class="text-success">Approved</strong>
                                        @else
                                            <strong class="text-warning">Approve Closed</strong>
                                        @endif
                                    @else
                                        <strong class="text-danger">Rejected</strong>
                                    @endif
                                @endif
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") approve_disabled @endif " type="radio" name="approve_stage" id="approve_stage_6" value="Documentation" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Dispatch") checked @endif>
                <label class="form-check-label" for="approve_stage_6">
                    Documentation   @if(!empty($oppertunitystatus["Documentation"])) 
                                        @if($oppertunitystatus["Documentation"]->approve_status=="Approve") 
                                            @if($oppertunitystatus["Documentation"]->status=="Y")
                                                <strong class="text-success">Approved</strong>
                                            @else
                                                <strong class="text-warning">Approve Closed</strong>
                                            @endif
                                        @else
                                            <strong class="text-danger">Rejected</strong>
                                        @endif
                                    @endif
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") approve_disabled @endif " type="radio" name="approve_stage" id="approve_stage_7" value="Supply Issue" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Documentation") checked @endif>
                <label class="form-check-label" for="approve_stage_7">
                    Supply Issue    @if(!empty($oppertunitystatus["Supply Issue"])) 
                                        @if($oppertunitystatus["Supply Issue"]->approve_status=="Approve") 
                                            @if($oppertunitystatus["Supply Issue"]->status=="Y")
                                                <strong class="text-success">Approved</strong>
                                            @else
                                                <strong class="text-warning">Approve Closed</strong>
                                            @endif
                                        @else
                                            <strong class="text-danger">Rejected</strong>
                                        @endif
                                    @endif
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") approve_disabled @endif " type="radio" name="approve_stage" id="approve_stage_9" value="Payment Follow Up" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Supply Issue") checked @endif>
                <label class="form-check-label" for="approve_stage_9">
                    Payment Follow Up   @if(!empty($oppertunitystatus["Payment Follow Up"])) 
                                            @if($oppertunitystatus["Payment Follow Up"]->approve_status=="Approve") 
                                                @if($oppertunitystatus["Payment Follow Up"]->status=="Y")
                                                    <strong class="text-success">Approved</strong>
                                                @else
                                                    <strong class="text-warning">Approve Closed</strong>
                                                @endif
                                            @else
                                                <strong class="text-danger">Rejected</strong>
                                            @endif
                                        @endif
                </label>
            </div>

            @if(in_array($staff_id,$adminB))
            <div class="form-check">
                <input class="form-check-input approve_stage @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") approve_disabled @endif " type="radio" name="approve_stage" id="approve_stage_3" value="Audit" @if(empty($lastapprove)||$lastapprove->approve_stage=="New Orders"||$lastapprove->approve_stage=="Initial Check"||$lastapprove->approve_stage=="Technical Approval") data-ban="true" disabled @elseif(!empty($lastapprove)&&$lastapprove->approve_stage=="Payment Follow Up") checked @endif>
                <label class="form-check-label" for="approve_stage_3">
                    Audit   @if(!empty($oppertunitystatus["Audit"])) 
                                            @if($oppertunitystatus["Audit"]->approve_status=="Approve") 
                                                @if($oppertunitystatus["Audit"]->status=="Y")
                                                    <strong class="text-success">Approved</strong>
                                                @else
                                                    <strong class="text-warning">Approve Closed</strong>
                                                @endif
                                            @else
                                                <strong class="text-danger">Rejected</strong>
                                            @endif
                                        @endif
                </label>
            </div>
            @endif
            <small class="error text-danger" id="erorr-approve_stage-message"></small>
        </div>
        <div class="form-group">
            <textarea name="approve_comment" id="approve_comment" class="form-control" placeholder="  Comment "></textarea>
            <small class="error text-danger" id="erorr-approve_comment-message"></small>
        </div>
        <div class="form-group">
            <button class="btn btn-primary pull-right" type="submit" id="approveOpportunityFormSubmitButton">Submit</button>
            <div class="row">
                <div class="col-md-6">
                    <label class="btn btn-outline-primary form-control" for="approve_attachment"> Add Attachment <i class="fa fa-plus fa-lg"></i></label>
                    <meter name="approve_attachment-meter" id="approve_attachment-meter" class="text-center col-12 m-2" value="0" min="0" max="1" style="display: none">0%</meter>
                    <input type="file" id="approve_attachment" style="display: none" accept=".jpg,.jpeg,.png,.pdf" >
                    <small class="error text-danger" id="erorr-approve_attachment-message"></small>
                </div>
            </div>
            
            <small class="">* The Attachment must be a file of type: jpg, jpeg, png, pdf</small><br>
            <small class="">* The Attachment must not be greater than 5 MB.</small>
            <div class="row" id="approve_attachment_preview">

            </div>
        </div>
    </div>
</div>
</form>
<script>
    var currentOppertunityStatus=@json($oppertunitystatus);
</script>