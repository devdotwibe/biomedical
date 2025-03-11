
@extends('staff/layouts.app')

@section('title', 'Customer')

@section('content')


<section class="content-header">
      <h1>
        Edit Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Manage Customer</a></li>
        <li class="active">Customer</li>
      </ol>
    </section>

<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-3 leftside-menu">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                  {{ucfirst($user->business_name)}}
                     </h4>
               </div>
               <div class="panel-body padding-10">
                 <h4 class="bold">
                    Oppertunity List
                  </h4>
                  <div>
                    <table class="table table-bordered table-striped data-">
                      <tr>
                        <td>No</td>
                        <td>Name</td>
                      </tr>
                      @if(sizeof($oppertunity)>0)
                      @php $i = 1; @endphp
                      @foreach($oppertunity as $op)
                      <tr>
                        <td>{{$i++}}</td>
                        <td><a href="{{url('staff/edit_oppertunity/'.$op->id)}}">{{$op->oppertunity_name}}</a></td>
                      </tr>
                      @endforeach
                      @endif
                    </table>
                  </div>
               </div>
                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
                  <li class="customer_tab_contacts ">
                    <a data-group="contacts" href="{{ url('staff/view_contact/'.$user->id) }}">
                      <i class="fa fa-users menu-icon" aria-hidden="true"></i>
                      Contact person      </a>
                  </li> 
                </ul>
            </div>
           
         </div>
        <div class="col-md-9">
          <!-- general form elements -->
          <div class="box box-primary">

           <form role="form" name="frm_user" id="frm_user" method="post" action="{{ route('staff.customer.update', $user->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
               
       
           
               <div class="box-body">

               
<div class="form-group">
    <div class="row">
		<div class="col-xs-12">

			<!-- tabs -->
			<div class="tabbable tabs-left">
				<div class="tab-content">
				

					<div class="tab-pane active" id="personal_details">
						<div class="">
            <!-- <h4>Personal Details</h4> -->
            <div class="form-group  col-md-6">
                  <label for="name">Head of the Institution</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{$user->name}}" readonly>
                </div>
             <div class="form-group  col-md-6">
                  <label for="name">Hospital Name</label>
                  <input type="text" id="business_name" name="business_name" class="form-control" value="{{$user->business_name}}" readonly>
              </div>

              <div class="form-group  col-md-6">
                  <label for="name">Staff</label>
                  <input type="text" id="staff" name="staff" class="form-control" value="{{$staff}}" readonly>
              </div>

              <div class="form-group  col-md-6">
                  <label for="name">Customer Category</label>
                  <input type="text" id="business_name" name="business_name" class="form-control" value="{{$customer_category}}" readonly>
              </div>

                  <div class="form-group  col-md-6">
                  <label for="name">Email</label>
                  <input  type="email" id="email" name="email" class="form-control" value="{{$user->email}}" readonly>
                </div>

                 <div class="form-group  col-md-6">
                  <label for="name">Phone</label>
                  <input type="text" id="phone" name="phone" class="form-control" value="{{$user->phone}}" readonly>
                </div>

                 <div class="form-group  col-md-12">
                  <label for="name">Address</label>
                  <textarea  id="address1" name="address1" class="form-control"  placeholder="Address" readonly>{{$user->address1}}</textarea>
                </div>

                  <div class="form-group  col-md-3">
                  <label for="name">Country</label>
                  <input type="text" id="phone" name="phone" class="form-control" value="{{$country}}" readonly>
                </div>

                 <div class="form-group  col-md-3">
                  <label for="name">State</label>
                  <input type="text" id="phone" name="phone" class="form-control" value="{{$state}}" readonly>
                </div>

                 <div class="form-group  col-md-3">
                  <label for="name">District</label>
                  <input type="text" id="phone" name="phone" class="form-control" value="{{$district}}" readonly>
                 
                </div>

                 <div class="form-group  col-md-3">
                  <label for="name">Taluk</label>
                  <input type="text" id="phone" name="phone" class="form-control" value="{{$taluk}}" readonly>
                </div>


                <div class="form-group  col-md-6">
                  <label for="name">GST</label>
                  <input type="text" id="gst" name="gst" class="form-control" value="{{$user->gst}}" readonly>
                  <span class="error_message" id="gst_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-12">
                  <label for="name">Zip</label>
                  <input type="text" id="zip" name="zip" class="form-control" value="{{$user->zip}}" readonly>
                  <span class="error_message" id="zip_message" style="display: none">Field is required</span>
                </div>

              
						</div>
					</div>


			</div>
			<!-- /tabs -->
		</div>
	</div>
</div>

            </form>


          
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
 