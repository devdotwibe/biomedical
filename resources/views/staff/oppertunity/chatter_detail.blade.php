<div class="row">
<div class="col-md-10"  >
  <div class="clearfix"></div>
  <form name="contactformedit" id="contactformedit" method="post" action="{{url('staff/chattersave')}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group col-md-10">
       
    </div>
    
    <div class="form-group col-md-10">
      <label>Contact*</label>

      <select name="contact[]" id="contact" class="form-control"  multiple="multiple" >
       <!--  <option value="">Select Contact</option> -->
        @foreach($contact as $ct)
          <option value="{{$ct->id}}">{{$ct->name}}</option>
        @endforeach
      </select>
        <a id="contact_link" href="{{url('staff/customer/'.$uid)}}" target='_blank'>Add contact</a>  
        <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
    </div>

     <div class="form-group col-md-10">
      <label>Comments</label>
        <div class="form-group col-md-3"><label class="form-check-label">Email 
            <input type="checkbox" name="email_status" id="email_status" class="form-check-input" value="Y">    </label> </div>
        <div class="form-group col-md-3">    <label class="form-check-label">Call 
            <input type="checkbox" name="call_status" id="call_status" class="form-check-input">    </label> </div>
        <div class="form-group col-md-3">    <label class="form-check-label">Visit 
            <input type="checkbox" name="visit_status" id="visit_status" class="form-check-input">    </label> </div>
    </div>

    <div class="form-group col-md-10">
      <label>Description*</label>
      <textarea name="comment" placeholder="Add Comment" id="comment" rows="3" class="form-control ays-ignore" required="" ></textarea>
       <span class="error_message" id="chat_comment_message" style="display: none">Field is required</span>
    </div>

    <div class="form-group  col-md-6">
      <label>Deal Stage*</label>
      @php $deal_stage = array('Lead Qualified/Key Contact Identified',
                               'Customer needs analysis',
                               'Clinical and technical presentation/Demo',
                               'CPQ(Configure,Price,Quote)',
                               'Customer Evaluation',
                               'Final Negotiation',
                               'Closed-Lost',
                               'Closed-Cancel',
                               'Closed Won - Implement'
                               );
      @endphp
      <select name="deal_stage" id="deal_stage" class="form-control">
        <option value="">-- Select Deal stage --</option>
        @for($i=0; $i< count($deal_stage);$i++)
           <option value='{{$i}}' @if(old('deal_stage',$opp->deal_stage)==$i){{'selected'}} @endif>{{$deal_stage[$i]}}</option>
        @endfor
      </select>
    </div>

    <div class="form-group col-md-6">
      <label for="name">Es.Order Date*</label>
      <input type="text" id="order_date" name="order_date" value="{{ old('order_date',$opp->es_order_date)}}" class="form-control" placeholder="Es.Order Date">
      <span class="error_message" id="order_date_message" style="display: none">Field is required</span>
    </div>

    

    <div class="form-group col-md-6">
      <label for="name">Es.Sales Date*</label>
      <input type="text" id="sales_date" name="sales_date" value="{{ old('sales_date',$opp->es_sales_date)}}" class="form-control" placeholder="Es.Sales Date">
      <span class="error_message" id="sales_date_message" style="display: none">Field is required</span>
    </div>
                
    <div class="form-group  col-md-6">
     @php $order_forcast =  array('Unqualified',
                              'Not addressable',
                              'Open',
                              'Upside',
                              'Committed w/risk',
                              'Committed'
                        );
      @endphp
        <label>Order Forcast Category*</label>
        <select name="order_forcast" id="order_forcast" class="form-control">
          <option value="">-- Select Deal stage --</option>
          @for($i=0;$i< count($order_forcast);$i++)
             <option value='{{$i}}' @if(old('order_forcast',$opp->order_forcast_category)==$i){{'selected'}} @endif>{{$order_forcast[$i]}}</option>
          @endfor
        </select>
    </div>

    <div class="form-group  col-md-6">
     @php $support =  array('Demo',
                            'Application/ clinical support',
                            'Direct company support',
                            'Senior Engineer Support',
                            'Price deviation'
                        );
      @endphp
        <label>Support</label>
        <select name="support" id="support" class="form-control">
          <option value="">-- Select Support --</option>
          @for($i=0;$i< count($support);$i++)
             <option value='{{$i}}' @if(old('support',$opp->support)==$i){{'selected'}} @endif>{{$support[$i]}}</option>
          @endfor
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="image_name">Image</label>
        <input type="file" id="image_name1" name="image_name1" accept=".jpg,.jpeg,.png,.pdf"/>
     

        <p class="help-block">(Allowed Type: jpg,jpeg,png,pdf )</p>
          <span class="error_message" id="image_name1_message" style="display: none">Field is required</span>
      </div>

    <div class="form-group col-md-10">
      <input type="hidden" name="op_id" value="{{$id}}">   
      <input type="submit" class="btn btn-info mtop10 pull-left" >          
    </div>
  </form>

  <table id="chattertable" class="table table-bordered table-striped data-">
    <thead>
      <th>Id</th>
      <th>Contact</th>
      <th>Comment</th>
      <th>Date</th>
      <th>Status</th>
    </thead>
    <tbody>
      @if(sizeof($chatter)>0)
        @php $i = 1; @endphp
        @foreach($chatter as $chat)
        <tr>
          <td>{{$i++}}</td>
          <td>
            @php 
               $chat->contact_person_id   = explode(",",$chat->contact_person_id);
            @endphp
            @foreach($chat->contact_person_id as $ids)
               @php $cn  = App\Contact_person::where('id',$ids)->first(); @endphp
               @if($cn)
               @php echo $cn['name'].','; @endphp
               @endif
               
            @endforeach 
            
          </td>
          <td>{{$chat->comment}}</td>
          <td>{{date('Y-m-d',strtotime($chat->created_at))}}</td>
          <td>
            E-mail : {{$chat->email_status}} <br>
            Call : {{$chat->call_status}} <br>
            Visit : {{$chat->visit_status}}
          </td>
        </tr>
        @endforeach
      @else
      <tr>
        <td>No record found</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script>
$(document).ready(function() {
    $('#contact').multiselect();
   
});
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

        
    $('#order_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         
         
            minDate: 0  
            
    });
    $('#sales_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           
         
            minDate: 0  
            
    });
</script>



