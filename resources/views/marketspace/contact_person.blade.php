
    
<!-- Modal -->
<div id="myModal" class="modal fade modal_contact" role="dialog">
  <div class="modal-dialog">
  <form name="contactform" id="contactform" method="post" action"" />
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
     
        <h4 class="modal-title">Add Contact Person</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">

      <div class="row">
      <div class="form-group  col-md-6">
          <label for="name">Title*</label>
          <select  id="title" name="title" class="form-control" >
          <option value="">Select Title</option>
          <option value="Mr.">Mr.</option>
          <option value="Ms.">Ms.</option>
          <option value="Dr.">Dr.</option>
          <option value="Fr.">Fr.</option>
          <option value="Sr.">Sr.</option>

          </select>
          <span class="error_message" id="title_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">First Name*</label>
          <input type="text" id="name" name="name" class="form-control" value="{{ old('name')}}" placeholder="Name">
          <span class="error_message" id="name_message" style="display: none">Field is required</span>
        </div>
      </div>
      

        
        <div class="row">
        <div class="form-group  col-md-6">
          <label for="name">Last Name</label>
          <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name')}}" placeholder="Last Name">
          <span class="error_message" id="last_name_message" style="display: none">Field is required</span>
        </div>

         <div class="form-group  col-md-6">
          <label for="name">Designation*</label>
         
          <select id="designation" name="designation" class="form-control" >
              <option value="">Select Designation</option>      
              @foreach ($hosdesignation as $desigval)
              <option value="{{$desigval->id}}">{{$desigval->name}}</option> 
              @endforeach
          </select>

          <span class="error_message" id="designation_message" style="display: none">Field is required</span>
        </div>
        </div>

        <div class="row">
        <div class="form-group  col-md-6">
          <label for="name">Department*</label>
          <select id="department" name="department" class="form-control" >
              <option value="">Select Department</option>      
              @foreach ($hosdeparment as $hosdepart)
              <option value="{{$hosdepart->id}}">{{$hosdepart->name}}</option> 
              @endforeach
          </select>
          <span class="error_message" id="department_message" style="display: none">Field is required</span>
        </div>

        

        <div class="form-group  col-md-6 checkFlex">
        <div class="checkStatus">
                    <!-- <label >Status*</label> -->
                  <div class="radio">
                    <label>
                      <input type="radio" name="contact_type" id="status" value="HOD" checked="true">
                      HOD
                    </label>
                  </div>
                 
                </div>
        
                <div class="checkStatus">
                    <!-- <label >Status*</label> -->
                  <div class="radio">
                    <label>
                      <input type="radio" name="contact_type" id="status1" value="Staff">
                      Staff
                    </label>
                  </div>
                  

                </div>
                </div>
                </div>         
        <div class="form-group  col-md-12" style="display:none;">
          <label for="name">Email*</label>
          <input type="text" id="email" name="email" class="form-control" value="{{ old('email')}}" placeholder="Email">
          <span class="error_message" id="email_message" style="display: none">Field is required</span>
        </div>

        
        <div class="form-group col-md-6" style="display:none;">
                  <label for="name">Password*<a onclick="get_password()"> Generate Password</a></label>
                  <?php
                  $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
                  $passwordval = substr($random, 0, 10);
                
                  ?>
                    <input type="hidden" id="demopassword" name="demopassword" value="{{$passwordval}}">
                  
                  <input type="text" id="password" name="password" value="{{ $passwordval}}" class="form-control" placeholder="Password">
                  <span class="error_message" id="password_message" style="display: none">Field is required</span>
                  
                </div>

        <div class="row">
         <div class="form-group  col-md-6">
          <label for="name">Phone*</label>
          <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone')}}" placeholder="Phone">
          <span class="error_message" id="phone_message" style="display: none">Field is required</span>
        </div>

        
        <div class="form-group  col-md-6">
          <label for="name">Mobile</label>
          <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile')}}" placeholder="Mobile">
          <span class="error_message" id="mobile_message" style="display: none">Field is required</span>
        </div>
        </div>
        <div class="row">
        <div class="form-group  col-md-6">
          <label for="name">Whatsapp</label>
          <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ old('whatsapp')}}" placeholder="Whatsapp">
          <span class="error_message" id="whatsapp_message" style="display: none">Field is required</span>
        </div>
        </div>
         
        <div class="row">
          <div class="form-group  col-md-12">
          <label for="name">Remark</label>
          <textarea  id="remark" name="remark" class="form-control" value="{{ old('remark')}}" placeholder="Remark"></textarea>
          <span class="error_message" id="remark_message" style="display: none">Field is required</span>
        </div>

        </div>

        <div class="row">
            <div class="form-group col-md-6">
                  <label for="image_name">Image1</label>
                  <input type="file" id="image_name1" name="image_name1" accept=".jpg,.jpeg,.png,.pdf"/>
               

                  <p class="help-block">(Allowed Type: jpg,jpeg,png,pdf )</p>
                    <span class="error_message" id="image_name1_message" style="display: none">Field is required</span>
                </div>
        </div>

        <div class="row">
                 <div class="form-group col-md-6">
                  <label for="image_name">Image2</label>
                  <input type="file" id="image_name2" name="image_name2" accept=".jpg,.jpeg,.png,.pdf"/>
                 

                  <p class="help-block">(Allowed Type: jpg,jpeg,png,pdf )</p>
                    <span class="error_message" id="image_name2_message" style="display: none">Field is required</span>
                </div>
            </div>
        
        <input type="hidden" id="user_id" name="user_id" class="form-control" value="{{$marketspace->user_id}}">
        

      </div>
      <div class="modal-footer">
      <div class="load-add" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
      <span id="successshow" style="display:none;color:green;">Contact Person Added</span>
        <button type="button" class="btn btn-primary"  onclick="validate_contact()">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>
