@extends('layouts.appmasterspace')
<?php
$title       = 'Profile';
$description = 'Profile';
$keywords    = 'Profile';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<link href="{{ asset('css/fonticonall.css') }}" rel="stylesheet">
<section class="user-profile">
    <div class="container">
        <div class="row viewbtn">
            <div class="col">
                <div class="viewclientbtn">
                    <a href="{{ route('marketspace/editprofile') }}">View Client Profile</a>
                </div>
            </div>
        </div>
        <div class="row main-section">
            <div class="col-md-4">
                <div class="profileId">
                    <div class="profilPic">
                        
                    <div id="imagesec">
                    @if($marketspace->image!='')
					<img src="{{ asset('storage/app/public/masterspace/'.$marketspace->image) }}" id="category-img-tag"  />
					@endif
					@if($marketspace->image=='')
					<img src="{{ asset('images/noimage.jpg') }}" id="category-img-tag">
					@endif
                    </div>
                  

                        <!-- <img src="{{ asset('images/user-profile.png') }}" alt="user-profile"> -->
                        <div class="profileOverlay">

                        <div class="imageupload_sec" style="display:none;">
                        <form id="reg_form" method="POST" action="" enctype="multipart/form-data">
        {{ csrf_field() }}
				<span class="text-danger" id="image_message" style="display:none;">Field Required</span>
				<span class="text-danger" id="imagesize" style="display:none;">Please check image size</span>
				<span class="text-danger" id="imageWH" style="display:none;">Required minimum width & height (640 x 480)</span>
			<p class="file-name"></p>
			<input type="hidden" name="image_name" id="image_name" value="{{$marketspace->image}}">
			<div class="custome-file">
				<input type="file" name="image" id="image" class="form-control" accept=".jpg, .png, .jpeg"/>
				<label for="image"></label>
			</div>
			<div class="loader_sec" style="display:none;">
			<img src="{{ asset('images/wait.gif') }}" alt=""/></div>
				</form>
                </div>

                            <!-- <h6 class="user-name">{{$marketspace->name}}</h6> -->
                            
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="short-info btmlineborder pi-p">
                        <div class="online-indicator"> Online</div>
                        @if($marketspace->address1!='')
                        <div class="address">{{$marketspace->address1}}</div>
                        @endif
                            <div class="userRate">
                                <input type="radio" name="star" id="star1"><label for="star1"></label>
                                <input type="radio" name="star" id="star2"><label for="star2"></label>
                                <input type="radio" name="star" id="star3"><label for="star3"></label>
                                <input type="radio" name="star" id="star4"><label for="star4"></label>
                                <input type="radio" name="star" id="star5"><label for="star5"></label>
                            </div>
                        </div>
                    <div class="user-work-summary btmlineborder pi-p">
                        <div class="work-summary">0% Job Completed</div>
                        <div class="work-summary">0% On Budget</div>
                        <div class="work-summary">0% On Time</div>
                    </div>
                    <div class="user-verification btmlineborder pi-p">
                        <h5 class="veri-title">Verification</h5>
                        <div class="identity-grp">
                        <div class="identity-ver"><img src="{{ asset('images/not-verifiedmaster.svg') }}">Identity Verified</div>
                        <div class="identity-ver"><img src="{{ asset('images/not-verifiedmaster.svg') }}">Payment Verified</div>
                        <div class="identity-ver"><img src="{{ asset('images/not-verifiedmaster.svg') }}">Phone Verified</div>
                        <div class="identity-ver"><img src="{{ asset('images/verifiedmaster.svg') }}">Email Verified</div>
                        </div>
                    </div>
                    <!-- <div class="user-certification pi-p">
                        <h5 class="certi-title">Verification</h5>
                        <div class="certi-grp">
                            <div class="certifi-ver">No Result</div>
                       
                        </div>
                    </div> -->
                </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="aboutCard">
                    <div class="aboutWrap">
                        <div class="wrap-about">
                            <div class="about-title">
                                <div class="aboutTitle">{{$marketspace->name}}</div>
                    @if($marketspace->pro_headline!='')
                            <div class="aboutTitle" id="pro_headline_sec">{{$marketspace->pro_headline}}</div> 
@endif
                             

                            </div>
                           
                        </div>
                        <div class="about-me">

                         <!--  -->
                    
                     <div class="modal-content about_sec" style="display:none;">
        
            <div class="modal-body" id="confirmMessage">
            <form method="POST" action="" action="" name="saveAbout" id="saveAbout" class="saveAbout" autocomplete="off" >
             @csrf
             <div class="form-group">
                 <label>Professional Headline</label>
                  <textarea  id="pro_headline" name="pro_headline"  class="form-control" >{{$marketspace->pro_headline}}</textarea>
                </div>
             <div class="form-group">
                 <label>Summery</label>
                  <textarea  id="about" name="about"  class="form-control" >{{$marketspace->about}}</textarea>
                </div>
                
            </form>
            </div>
            <div class="modal-footer">
                <div class="about_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                <button type="button" class="btn btn-default" id="save_about">Save</button>
                <button type="button" class="btn btn-default close_about" >Cancel</button>
            </div>
        </div>
                    <!--  -->
                       <div class="loopabout">
                           {{$marketspace->about}}
                           @if($marketspace->about=='')
                           No about information has been added.
                           @endif
                            </div>


                        </div>
                    </div>
                    <div class="reviews">
                        <div class="review-title">Reviews</div>
                    </div>
                    <div class="reviews-section">
                    <img src="{{ asset('images/nothing-to-see-here.png') }}">
                    No reviews to see here!.
                        <!-- <div class="custom-review">
                            <div class="review-col">
                                <div class="reviewer">
                                    <div class="circle"><div class="c-letter">L</div></div>
                                    <div class="reviewer-name">Leslie Alexander</div>
                                    <div class="post-time">6 Months ago</div>
                                </div>
                                <div class="review-price">$ 75</div>
                            </div>
                            <div class="userRate">
                                <input type="radio" name="star" id="star1"><label for="star1"></label>
                                <input type="radio" name="star" id="star2"><label for="star2"></label>
                                <input type="radio" name="star" id="star3"><label for="star3"></label>
                                <input type="radio" name="star" id="star4"><label for="star4"></label>
                                <input type="radio" name="star" id="star5"><label for="star5"></label>
                            </div>
                            <div class="reviewer-detail">
                                <div class="proj-name">Project name</div>
                            <div class="reviewer-content">Ornare senectus ultrices euismod aliquam gravida et sit non pharetra, 
                                egestas ac aliquam etiam lobortis euismod elementum id et cras in ultricies auctor eget neque, </div>
                            </div>
                        </div>

                        <div class="custom-review">
                            <div class="review-col">
                                <div class="reviewer">
                                    <div class="circle"><div class="c-letter">L</div></div>
                                    <div class="reviewer-name">Leslie Alexander</div>
                                    <div class="post-time">6 Months ago</div>
                                </div>
                                <div class="review-price">$ 75</div>
                            </div>
                            <div class="userRate">
                                <input type="radio" name="star" id="star1"><label for="star1"></label>
                                <input type="radio" name="star" id="star2"><label for="star2"></label>
                                <input type="radio" name="star" id="star3"><label for="star3"></label>
                                <input type="radio" name="star" id="star4"><label for="star4"></label>
                                <input type="radio" name="star" id="star5"><label for="star5"></label>
                            </div>
                            <div class="reviewer-detail">
                                <div class="proj-name">Project name</div>
                            <div class="reviewer-content">Ornare senectus ultrices euismod aliquam gravida et sit non pharetra, 
                                egestas ac aliquam etiam lobortis euismod elementum id et cras in ultricies auctor eget neque, </div>
                            </div>
                        </div> -->

                    </div>
                </div>
                <div class="skill-card">
                    <div class="skill-sec">
                        <div class="skill-title">Skills</div>
                       
                    </div>

                   
                    <div class="outer-table">
                    
                    <table class="table loopskill">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Brand</th>
                                <th scope="col">Category Type</th>
                                <th scope="col">Product Type</th>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                
                            </tr>
                        </thead>
                        <tbody id="ajax_res">
                        @if(count($marketspace_skill)==0)
                        <tr> <td colspan="6" class="t-center">
                        No skills information has been added.   </td>  </tr>
                        @endif
                        @if(count($marketspace_skill)>0)
                            @foreach($marketspace_skill as $val)
                             <tr>
                                <td>{{$val->brand->name}}</td>
                                <td>{{$val->category_type->name}}</td>
                                <td>{{$val->product_type->name}}</td>
                                <td>{{$val->product->name}}</td>
                                <td>{{number_format($val->price,2)}}</td>
                               
                                   
                            </tr>
                            @endforeach
                            @endif
                           
                        </tbody>
                    </table>
                    </div>

                </div>
                <div class="Experience-card">
                    <div class="exp-sec">
                        <div class="exp-title">Experience</div>
                        
                    </div>
                    <div class="exp-list-wrap">

                    

                    <div class="loopexp">
                    @if(count($marketspace_exp)>0)
                        @foreach($marketspace_exp as $val)
                      
                        <div class="exp-listing">
                            <div class="exp-wrap">
                                <div class="exp-name">{{$val->experience}}</div>
                                <div class="exp-date">{{$val->experience_from_date}} - {{$val->experience_to_date}}</div>
                            </div>
                            
                        </div>
                        @endforeach
                        @endif

                        @if(count($marketspace_exp)==0)
                        No Experience information has been added.
                        @endif
                      </div>

                        
                        
                    </div>
                </div>
                <div class="edu-card">
                    <div class="edu-sec">
                        <div class="edu-title">Education</div>
                       
                    </div>
                    <div class="edu-list-wrap">

                   

                    <div class="loopedu">
                    @if(count($marketspace_edu)>0)
                        @foreach($marketspace_edu as $val)
                        <div class="edu-listing">
                            <div class="edu-wrap">
                                <div class="edu-name">{{$val->education}}</div>
                                <div class="edu-date">{{$val->education_from_month}} {{$val->education_from_year}} - {{$val->education_to_month}} {{$val->education_to_year}}</div>
                            </div>
                            
                        </div>
                        @endforeach
                        @endif

                        @if(count($marketspace_edu)==0)
                        No education information has been added.
                        @endif
                      </div>
                      
                    </div>
                </div>
                <div class="qlty-card">
                    <div class="qlty-sec">
                        <div class="qlty-title">Qualification</div>
                        

                        
                    </div>
                    <div class="qlty-list-wrap">

                    
                    <div class="loopqlty">
                        @if(count($marketspace_quali)>0)
                        @foreach($marketspace_quali as $val)
                        <div class="qlty-listing">
                            <div class="qlty-wrap">
                                <div class="qlty-name">{{$val->qualification}}</div>
                                <div class="qlty-date">{{$val->qualification_from_month}} {{$val->qualification_from_year}} - {{$val->qualification_to_month}} {{$val->qualification_to_year}}</div>
                            </div>
                            
                        </div>
                        @endforeach
                        @endif

                        @if(count($marketspace_quali)==0)
                        No qualification information has been added.
                        @endif

                    </div>  

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
@section('scripts')

<script>

  
	
  
</script>
@endsection
<style>
.main-footer{display:none;}
  </style>