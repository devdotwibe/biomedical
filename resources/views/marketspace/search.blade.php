@extends('layouts.appmasterspace')
<?php
$title       = 'Search';
$description = 'Search';
$keywords    = 'Search';
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<div class="searchSection">
        <div class="container">

        <div class="mobilemenusearch">
                   
                    
                          @if(session('MARKETSPACE_ID')==0)
                          <div class="login">
                              <a class="nav-link" href="{{ route('marketspace/login') }}">{{ __('Login') }}</a>
                            </div>
                            <div class="register">
                              <a class="nav-link" href="{{ route('marketspace/register') }}">{{ __('Register') }}</a>
                              </div>
                           @endif
                       
                   
                  </div>



            <div class="search_wrap search_wrap_5">
                <div class="search_box">
                    <input type="text" class="input" placeholder="Enter keywords here like Brand, Category Type, Product Type, Product" id="search_txt">
                   
                    <div class="btn" onclick="search_word()">
                        <p>Search</p>
                    </div>
                </div>
                <span id="error_msg" style="display:none;" class="error">Field is required!</span>
            </div>
        </div>
    </div>
    
    <section class="searchListing">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                <div class="filter-icon" id="togglefilter">Filter <i class="fa fa-filter"></i> </div>
                
                    <div class="sideFilterbar" id="showFilter">
                    <div class="close-icon" id="closeFfilter">Close <i class="fa fa-times" aria-hidden="true"></i></div>
                        <div class="priceFilter btmborder">
                            <div class="sideFilterTitle"><h4>Filter by Price</h4></div> 
                            <label for="customRange1" class="form-label">  <div class="kb_show_range">0</div></label>
                            <input type="range" class="form-range"  min="0" max="1000" value="0" id="kb_drangeInput">
                            
                            
                        </div>
                        <div class="skillFilter btmborder">
                            <div class="sideFilterTitle"><h4>Filter by Skills</h4></div>
                            <input type="text" placeholder="Search" onkeyup="search_skills(this.value)">
                            <div class="loader_sec_skill" style="display:none;">
                                <img src="{{ asset('images/wait.gif') }}" alt=""/>
                            </div>
                            <div class="skillfilterlist">
                            
                            @if(count($skills)==0)
                            No skills information has been added.
                            @endif
                                @if(count($skills)>0)
                                @php $k=0; @endphp
                                @foreach($skills as $val)
                                
                                <div class="form-check @if($k>=5) viewmore @endif" @if($k>=5) style="display:none;" @endif">
                                    <input type="checkbox" name="skill[]" class="form-check-input skillcheck" id="flexCheckDefault" value="{{$val->product_id}}">
                                    <label class="form-check-label" for="flexCheckDefault">{{$val->product->name}}</label>
                                </div>

                                @php $k++; @endphp
                                @endforeach
                                @endif
                               
                            </div>
                            <div class="filterViewAll">View all </div>
                        </div>
                        <div class="reviewFilter btmborder">
                            <div class="sideFilterTitle"><h4>Filter by Reviews</h4></div>
                            <label for="customRange1" class="form-label"><div class="custom_show_range">0</div></label>
                            <input type="range" class="form-range" min="0" max="5" value="0" id="customRange1" >
                            
                        </div>
                        
                    </div>
                
                </div>
                <div class="col-md-9">
                    
<!--  -->
<div id="table_data">
@include('layouts.postdata')
</div>

               <!--  -->
                </div>
            </div>
        </div>



        
            
    </section>


    
<div class="modal fade" id="modalhire" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog h-popup" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="saveHire" name="saveHire" method="POST" action="" enctype="multipart/form-data">
        {{ csrf_field() }}
      <div class="modal-body">
     
          <div class="form-group">
            <label for="title" class="col-form-label">Project Title</label>
            <input type="text" class="form-control" id="title" name="title" value="">
            <input type="hidden" class="form-control" id="hire_service_id" name="hire_service_id" value="">
            
          </div>
          <div class="form-group">
            <label for="details" class="col-form-label">Description</label>
            <textarea class="form-control" id="details" name="details"></textarea>
          </div>

          <div class="form-group hire-error">
                    <input class="form-check-input" type="checkbox" value="Y" id="accept"  name="accept" >
                    <label class="form-check-label" for="flexCheckChecked">
                                            I accept the terms & conditions
                                        </label>
                                        </div>

     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save_hire">Hire</button>
      </div>
      </form>
    </div>
  </div>
</div>



@endsection


@section('scripts')
<script>

$(document).on('click', '.filterViewAll', function(){
    $(".viewmore").show();
    $(".filterViewAll").hide();
});
 $(document).on('click', '#togglefilter', function(){
$(".sideFilterbar").show();
$(".sideFilterbar").addClass("show");
$(".sideFilterbar").removeClass("hide");
 });


 $(document).on('click', '#closeFfilter', function(){
    $(".sideFilterbar").hide();
    $(".sideFilterbar").addClass("hide");
    $(".sideFilterbar").removeClass("show");
});


    var page=1;
    var listvalues = []

    function search_word(skill)
    {
        var search=$("#search_txt").val();
      if(search!='')
        {
            fetch_data(page=1)
            $("#error_msg").hide();
        }else{
            $("#error_msg").show();
        }
        
    }

    

    $(document).on('change', '.skillcheck', function(){
    if(this.checked){
        listvalues.push(this.value);
    }
    else {
        listvalues = listvalues.filter(item => item != this.value);
    }
    fetch_data(page)
});




  
    function search_skills(skill)
    {
        $(".loader_sec_skill").show();
		$.ajax({
			type:'POST',
			url:'{{ route("search_skills") }}',
			//dataType:'json',
			headers: {
					'X-CSRF-Token': '{{ csrf_token() }}',
			},
            data: {
                skill:skill
            },
			success:function(data){
				$(".loader_sec_skill").hide();
                var proObj = JSON.parse(data);
      var  htmls=' ';
      
                for (var i = 0; i < proObj.length; i++) {
                    htmls +='<div class="form-check">'+
                        '<input type="checkbox" name="skill[]" class="form-check-input skillcheck" id="flexCheckDefault" value="'+proObj[i]["id"]+'">'+
                        '<label class="form-check-label" for="flexCheckDefault">'+proObj[i]["name"]+'</label>'+
                    '</div>';
                }
                if(proObj.length==0)
                {
                    htmls +='<div class="not-found">No result found</div>';
                }
              
                $(".skillfilterlist").html(htmls);
				
			}, 
			error: function(data){
				console.log('error')
			}
		});
    }
    $(document).ready(function(){
     $("#kb_drangeInput").on('input', function(){   
    $(".kb_show_range").text($(this).val());
    fetch_data(page=1)
    let countRange = parseFloat($(".kb_show_range").text());
    });

    $("#customRange1").on('input', function(){   
    $(".custom_show_range").text($(this).val());
    fetch_data(page=1)
    let countRange = parseFloat($(".custom_show_range").text());
    });

});
function fetch_data(page)
{
    var price=$("#kb_drangeInput").val();
    var review=$("#customRange1").val();
   
    var search_word=$("#search_txt").val();
 $.ajax({
			type:'GET',
			url:'{{ route("fetch_data") }}',
			//dataType:'json',
			headers: {
					'X-CSRF-Token': '{{ csrf_token() }}',
			},
            data: {
                page:page,skills_id:listvalues,price:price,search_word:search_word,review:review
  },
  success:function(data){
    $('#table_data').html(data);
  }
});

}


$(document).ready(function(){


$(document).on('click', '.pagination a', function(event){

 event.preventDefault(); 

 var page = $(this).attr('href').split('page=')[1];

 fetch_data(page);

});



  
    $(document).on('click', '.hirebtn', function(){
    $("#hire_service_id").val($(this).attr('attr-id'));
   $("#modalhire").modal("show");
});

$(document).on('click', '.startmsgbtn', function(){
    var service_id=$(this).attr('attr-id');
    $("#startmsgbtn_loader"+service_id).show();
   $.ajax({
      url:'{{ route("chat") }}',
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
      type: 'post',
      data: {
        service_id:service_id
      },
      success: function( data ) {
        $("#startmsgbtn_loader"+service_id).hide();
        location.href="{{ route('marketspace/chat') }}";
      }
    });

});




$("#save_hire").click(function() {
 
 var form = $("#saveHire");
form.validate({
 rules: {
  title: {
        required:true,
     },
     details: {
         required: true,
     },
     accept: {
         required: true,
     },
     
     
 },
 messages: {
    title: {
         required:"Field is required!",
     },
     details: {
         required: "Field is required!",
     },
     accept: {
         required: "Field is required!",
     },
 }
}); 
if(form.valid() === true) {
  $(".quali_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("saveHire") }}',
    //dataType:'json',
    data: $("#saveHire").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
      $(".quali_gif").hide();
      window.location.href=("{{ route('marketspace/mywork') }}");
      
    }, 
    error: function(data){
      console.log('error')
    }
    });

}
});

});



</script>
<style>
.userRate img {
    width: 15px;
    height: auto;
    border: none;
    margin: 0 5px 0 0;
}
    </style>
@endsection