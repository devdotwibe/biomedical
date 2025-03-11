<div class="row">
<div class="col-md-10"  >
  <div class="clearfix"></div>
  <form name="contactformedit" id="contactformedit" method="post" action="{{url('staff/send_mail')}}">
    {{ csrf_field() }}
    <div class="form-group col-md-10">
      <a id="contact_link" href="{{url('staff/customer/'.$user_id)}}" target='_blank'>Add contact</a>     
    </div>
    
    <div class="form-group col-md-10">
      <label>Contact*</label>
      <select name="contact[]" id="contact" class="form-control"  multiple="multiple" >
       <!--  <option value="">Select Contact</option> -->
        @foreach($contact as $ct)
          <option value="{{$ct->id}}">{{$ct->name}}</option>
        @endforeach
      </select>
      <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
    </div>

    <div class="form-group col-md-10">
      <label>Subject*</label>
        <input type="text" id="subject" name="subject" class="form-control" required="">
      <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
    </div>
    
    <div class="form-group col-md-10">
      <label>Description</label>
      <textarea name="comment" placeholder="Add Comment" id="comment" rows="3" class="form-control ays-ignore" required="" ></textarea>
       <span class="error_message" id="chat_comment_message" style="display: none">Field is required</span>
    </div>

    <div class="form-group col-md-10">
      <input type="hidden" name="qh_id" value="{{$id}}">   
      <input type="submit" class="btn btn-info mtop10 pull-left" >          
    </div>
  </form>
</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script>
$(document).ready(function() {
    $('#contact').multiselect({
      nonSelectedText: 'Select Contact',
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      buttonWidth:'332px'
     });
   
});
</script>



