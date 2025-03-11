<div class="row">
    <div class="col-md-10"  >
      <div class="clearfix"></div>
      <form name="contactformeditopper" id="contactformeditopper" method="post" action="{{url('staff/send_mail')}}">
        {{ csrf_field() }}
        <div class="form-group col-md-10">
          <a id="contact_link" href="{{url('staff/customer/'.$user_id)}}" target='_blank'>Add contact</a>     
        </div>

        <div class="form-group col-md-10">
          <label>Enter Email</label>
    
            <input type="text" id="customer_mail" name="customer_mail" class="form-control">
            <span class="error_message" id="customer_mail_message" style="display: none">Field is required</span>
        </div> 
        
        <div class="form-group col-md-10">
          <label>Contact</label>
          <select name="contact[]" id="contact" class="form-control"  multiple="multiple">
           <!--  <option value="">Select Contact</option> -->
           @if(!empty($hospital->email))
           <option value="customer">{{$hospital->business_name}} ({{$hospital->email}})</option>
           @endif
            @foreach($contact as $ct)
              <option value="{{$ct->id}}" data-email={{$ct->email}}>{{$ct->name}} ({{$ct->email}})</option>
            @endforeach
          </select>
          <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group col-md-10">
          <label>Subject*</label>
            <input type="text" id="subject" name="subject" class="form-control" required="" value="{{isset($hospital->business_name)?ucfirst($hospital->business_name)." - ":""}} MSA Proposal">
          <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
        </div>
        
        <div class="form-group col-md-10">
          <label>Description</label>
          <textarea name="comment" placeholder="Add Comment" id="comment" rows="3" class="form-control ays-ignore" required="" >
        
            <p>
    
            Madam/Sir, <br> <br>
    
            Greetings from BEC Healthcare India Pvt.Ltd , the  authorized organization that offers  notch service on behalf of M/s.  Wipro GE Healthcare Pvt.Ltd   <br>
    
            Please find attached details mentioned below  <br>
    
            We trust our offer is in line with your requirement and look forward to receive your valued order. <br>
    
            However, should you require any clarification please feel free to contact us. <br>
    
            Thanking you and assuring you the best of services <br>
    
            </p>
    
        Your support engineer<br>
        @foreach($staff as $sta)
        {{$sta->name}}<br>
        <?php 
        if($sta->designation_id>0)
        {
          $desig = App\Designation::find($sta->designation_id);
          $desig_name=$desig->name;
        }
        else{
          $desig_name='';
        }
        ?>
        {{$desig_name}}<br>
        {{$sta->company_no}}<br>
        @endforeach
       
        <p>
          @if($state->name =='Kerala')

            {{ setting('QUOTE_MAIL_KERALA') }}

            @elseif($state->name =='Tamil Nadu')
    
            {{ setting('QUOTE_MAIL_TAMIL_NADU') }}
          
            @else
    
             {{ setting('QUOTE_MAIL_CONTENT_FOOTER') }}
    
            @endif
    
        </p>
          
          </textarea>
           <span class="error_message" id="chat_comment_message" style="display: none">Field is required</span>
        </div>
    
        <div class="form-group col-md-10">
          <input type="hidden" name="qh_id" value="{{$id}}">   
          <input type="button" id="submit_email" class="btn btn-info mtop10 pull-left" >          
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
          buttonWidth:'610px'
         });
       
       
    
                      CKEDITOR.replace('comment',
                 {
                            customConfig: '{{ asset('AdminLTE/bower_components/ckeditor/custom/config.js') }}',
                            height: '300',
                            width:'500',
                            toolbar: 'Basic'
                    });
    
                
    
    });

    // $(function() {
    //     $('#contactformeditopper').submit(function(event) {
    //         var contact = $('#contact').val();
    //         var customer_mail = $('#customer_mail').val();

    //         var emailPattern = /^[a-zA-Z\s][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    //         if ((contact === null || contact.length === 0) && (customer_mail === null || customer_mail.length === 0)) {

    //           $('#customer_mail_message').show();
    //             event.preventDefault(); 
    //         }
    //         else
    //         {
    //           if (customer_mail.length > 0 && !emailPattern.test(customer_mail)) {
    //               $('#customer_mail_message').show().text('Please enter a valid email address.');
    //               event.preventDefault();
    //           } else {
    //               $('#customer_mail_message').hide();
    //           }
    //         }
    //     });
    // });

    $('#submit_email').on('click', function(event) {
        var contact = $('#contact').val();
        var customer_mail = $('#customer_mail').val();

        var customer_mail_array = customer_mail.split(',').map(function(item) {
            return item.trim(); 
        });

        var emailList = []; 

        $('#contact option:selected').each(function(index) {

          emailList['contact[' + index + ']'] = $(this).data('email');
        });

        if ((contact === null || contact.length === 0) && (customer_mail_array === null || customer_mail_array.length === 0)) {
           $('#contact_id_message').show();

        }
        else
        {
          $('#contact_id_message').hide();
          
          $('#customer_mail_message').hide();

            var url = APP_URL+'/staff/emailvalidationusers';
                    $.ajax({
                    type: "POST",
                    cache: false,
                    url: url,
                    data:{
                      ...emailList,
                      customer_mail: customer_mail_array,
                    },
                    success: function (data) {
                          if (data.success === false) {
                            
                            for (let key in data.errors) {
                                if (data.errors.hasOwnProperty(key)) {
                                    if (key.startsWith('contact.')) {

                                        $('#contact_id_message').text(data.errors[key][0]).show();
                                    }
                                    else if (key.startsWith('customer_mail.')) {

                                      $('#customer_mail_message').text(data.errors[key][0]).show();
                                    }
                                }
                            }
                              
                              // if (data.errors.customer_mail) {
                              //     $('#customer_mail_message').text(data.errors.customer_mail[0]).show();
                              // }
                             
                          } 
                          else
                          {
                            $('#contactformeditopper').submit();
                          } 
                      },
                      error: function (xhr) {
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                          
                              for (let key in xhr.responseJSON.errors) {
                                if (xhr.responseJSON.errors.hasOwnProperty(key)) {
                                    if (key.startsWith('contact.')) {

                                        $('#contact_id_message').text(xhr.responseJSON.errors[key][0]).show();
                                    }
                                    else if (key.startsWith('customer_mail.')) {

                                      $('#customer_mail_message').text(xhr.responseJSON.errors[key][0]).show();
                                      
                                    }
                                }
                            }
                              
                              // if (xhr.responseJSON.errors.customer_mail) {
                              //     $('#customer_mail_message').text(xhr.responseJSON.errors.customer_mail[0]).show();
                              // }

                             
                            } 
                      }

                  });
        }
    });

  //   $(function() {
  //     $('#contactformeditopper').submit(function(event) {

  //       var contact = $('#contact').val();

  //         // var customer_mail = $('#customer_mail').val();

  //         if ((contact === null || contact.length === 0)) {
  //           $('#contact_id_message').show();
  //             event.preventDefault(); 
  //         }
  //         else
  //         {
  //           $('#contact_id_message').hide();
  //         }
  //     });
  // });
    </script>
    
    
    
    