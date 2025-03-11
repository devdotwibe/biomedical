<aside class="col-md-2" id="sidebar">
    <div class="sidebar-row1">
        <div class="prfl-col1">
            <span id="imagesec"  class="prfl-img">
                
        @if($marketspace->image!='')
                  <img src="{{ asset('storage/app/public/user/'.$marketspace->image) }}" id="category-img-tag"  />
                  @endif
                  @if($marketspace->image=='')
                  <img src="{{ asset('images/noimage.jpg') }}" id="category-img-tag">
                  @endif
    </span>

    <form id="reg_form" method="POST" action="" enctype="multipart/form-data">
        {{ csrf_field() }}
				<span class="text-danger" id="image_message" style="display:none;">Field Required</span>
				<span class="text-danger" id="imagesize" style="display:none;">Please check upload file size Maximum upload file size 2 MB</span>
				<span class="text-danger" id="imageWH" style="display:none;">Required minimum width & height (640 x 480)</span>
			{{-- <p class="file-name"></p> --}}
			<input type="hidden" name="image_name" id="image_name" value="{{$marketspace->image}}">
			<div class="custome-file">
				<input type="file" name="image" id="image" class="form-control" />
				<label for="image"></label>
			</div>
			<div class="loader_sec" style="display:none;">
			<img src="{{ asset('images/wait.gif') }}" alt=""/></div>
				</form>

            <h3>@if($marketspace) @if($marketspace->name!='') {{$marketspace->name}} @endif @if($marketspace->name=='') User @endif @endif</h3>
        </div>    
    </div>
    <div class="sidebar-row2">
        <div class="bio-col1">
            <h3>Bio</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                Mauris laoreet mi id nulla ullamcorper, eget elementum nisl aliquet. 
                Orci varius natoque penatibus et magnis dis parturient monte
            </p>
        </div>
        <div class="bio-col1">
            <h3>Location</h3>
            <p>Ernakulam-686 442 
            </p>
        </div>
        <div class="bio-col1">
            <h3>Contact No</h3>
            <p>0844 8634 343
            </p>
        </div>
    </div>
    
</aside>

