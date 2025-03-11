@extends('layouts.app')

<?php
$title       = 'You have searched for '.$search_word; 
$description = 'You have searched for '.$search_word;
$keywords    = 'You have searched for '.$search_word;

//print_r($_REQUEST);
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
<link href="{{ asset('css/productlisting.css') }}" rel="stylesheet">
@section('content')


<section class="inrpage-menu">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Shop Products</a></li>
                <!-- <li class="breadcrumb-item active" aria-current="page">Ambulatory Equipment</li> -->
              </ol>
            </nav>
          </div>
          <div class="col-md-5 text-right inrpage-search ml-auto">
          <form  class="searchform" name="frm_searchHead" id="frm_searchHead" method="POST" action="{{route('search')}}" autocomplete="off">
                    @csrf
              <input type="text" value="{{$search_word}}" class="form-control" placeholder="Search for products..." name="search_word" id="search_word">
               <button class="btn btn-secondary" type="submit" >Search</button>
          </form>

          </div>
        </div>
       </div>
    </section>

  <section class="prdt-list-wrapper">
  <div class="container">
  <div class="row">
    <div class="col-md-3 col-xs-1 p-l-0 p-r-0  in" id="sidebar">
      <div class="list-group panel">
      <h3>Narrow your Results</h3>
        <a href="#menu1" class="submenu-hd list-group-item collapsed" data-toggle="collapse" data-parent="#sidebar" aria-expanded="false"> <span class="hidden-sm-down">Manufacturer Name</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
        <div class="prdt-dropdown collapse show" id="menu1">
        @foreach($brand as $values)
          <a href="{{ url('products/'.$values->brandslug) }}" class="list-group-item" aria-expanded="false">{{$values->brandname}} <span>({{$values->totalproduct}})</span></a>
         @endforeach   
         
        </div>
        <a href="#menu3" class="submenu-hd list-group-item collapsed" data-toggle="collapse" data-parent="#sidebar" aria-expanded="false"><span class="hidden-sm-down">Category Name </span><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
        <div class="prdt-dropdown collapse" id="menu3">
           @foreach($category as $values) 
          <a href="{{ url('products/'.$values->catslug) }}" class="list-group-item" data-parent="#menu3">{{$values->catname}} <span>({{$values->totalproduct}})</span></a>
          @endforeach

        </div>
        
      </div>
    </div>
    <main class="col-md-9 col-xs-11 p-l-2 p-t-2">
          <div class="prdt-list-top">
              <!-- <span class="lft-clrtxt text-left"><p>Shop Ambulatory Equipmenti</p></span> -->
             <!-- <span class="rgt-txt text-right"><p>Showing 1 – 24 products of 30 products</p></span>-->
          </div>
      <div class="prdt-list-outer">
        <div class="row">
            
            @foreach($products as $values)
          <div class="col-md-4">
           
                <div class="prdt-box">
                  <div class="prdt-img">
                  <a href="{{ url('products/'.$values->slug) }}">
                   @if($values->image_name!='')
                      <img src="<?php echo asset("public/storage/products/$values->image_name") ?>" alt="">
                      @endif
                      @if($values->image_name=='')
                    <img src="{{ asset('images/no-image.jpg') }}" alt="">
                    @endif
                    </a>
                  </div>
                  <div class="prdt-content">
                      <div class="prdt-codtxt">
                          <span class="cod-clr">{{$values->item_code}}</span>
                         <span class="cod-no"><i class="fa fa-user" aria-hidden="true"></i>
                         @if($values->view_count=="") 
                         0
                        @else
                       {{$values->view_count}}
                         @endif
                         </span>
                      </div>
                      <h2>{{$values->name}}</h2>
                        {{substr($values->short_description,0,50)}}

                          @guest
                        
                          <button class="btn btn-warning quotebtn" type="button" onclick="window.location.href ='{{ url('products/'.$values->slug) }}'">Read More</button>
                       
                           
                          @else

                          @if($values->unit_price=="")
                          <button class="btn btn-warning request_quote quotebtn" type="button" >Read More</button>
                          @php
                            $cart = App\Cart::where([['product_id',$values->id],['status','add']])->get();
                            @endphp
                            @if(sizeof($cart)==0)
                              <button data-id = {{$values->id}} class="btn btn-success add_cart cartbtn" type="button" >Add to Cart</button>
                            @else
                               <button disabled data-id = {{$values->id}} class="btn cartinbtn" type="button" >Already in Cart</button>
                            @endif
                          @endif

                          @if($values->unit_price!="")
                          
                          <button type="button" class="btn btn-outline-info unitbtn">Rs. {{$values->unit_price}}</button>
                          <button class="btn btn-primary orderbtn" type="button" >Order Now</button>
                          @endif


                        @endguest

                  </div>
                </div>
        
          </div>
            
            @endforeach
            @if(count($products)==0)       
            <div class="col-md-4 noresult">
              <p>No Result</p>
            </div>          
            @endif
                    
            
            
        </div>
        <div class="row">
        <div class="m-auto text-center">
           <!-- <nav aria-label="Page navigation example">
             <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">Next</a>
                </li>
              </ul> 
                
            </nav>-->
            @if($totalCount==0)    
           
            @endif
        </div>
        </div>

      </div>
    </main>
  </div>
</div>
</section>


<div class="modal" tabindex="-1" role="dialog" id="quote-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Success</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Quote Created</p>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="cart-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Success</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Added to Cart</p>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('scripts')

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>

$( function() {


  $( ".request_quote" ).click(function() {

    window.location.href ='{{ url('products/'.$values->slug) }}';
 /*var product_id=$(this).attr('data-id');

 var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/addquote';
  
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    //dataType: "json",
    data: {
      product_id:product_id
    },
    success: function( data ) {//alert();
      $("#quote-modal").modal('show');
    }
    });
*/
    
});

$( ".add_cart" ).click(function() {
       var product_id=$(this).attr('data-id');

       var APP_URL = {!! json_encode(url('/')) !!}
       var url = APP_URL+'/admin/addcart';
      // alert(product_id);
     
       $.ajax({
          url: url,
          //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          type: 'post',
          //dataType: "json",
          data: {
            "_token": "{{ csrf_token() }}",
            "product_id": product_id
          },
          success: function( data ) {//alert();
            location.reload(true);
            $("#cart-modal").modal('show');
            //alert('Added to cart');
          }
      });

    });


// Single Select
$( "#search_word" ).autocomplete({
 source: function( request, response ) {
  // Fetch data
  var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/searchproducts';
  var search_word=$("#search_word").val();
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    dataType: "json",
    data: {
      search_word:search_word
    },
    success: function( data ) {
      response( data );
    }
    });
 },
 select: function (event, ui) {
  // Set selection
  $('#search_word').val(ui.item.label); // display the selected text
 
  return false;
 }
});



});

function split( val ) {
   return val.split( /,\s*/ );
}
function extractLast( term ) {
   return split( term ).pop();
}
 
</script>
@endsection
