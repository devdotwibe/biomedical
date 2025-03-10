@extends('layouts.app')

<?php
$title       = 'All Products';
$description = 'All Products';
$keywords    = 'Products';
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
<link href="{{ asset('css/productlisting.css') }}" rel="stylesheet">
@section('content')

<section class="hero-banner products-bnr"> <!--py-3-->
      <div class=" hero-container" >     <!--removed container-->
        <div class=" align-items-center"> <!--removed row-->
          <div class="banner-img ">
            <div class="inr-bnr-head ">
            <div class="container ">
              <div class="row inr-bnr-row">
              <div class="col-md-12 text-center">
               <h1>Our products</h1>
               </div>
              </div>
            </div>
            </div>
               <div class="inner-bnr-img ">
                  <img src="images/products-brn-img.jpg" class="" style="width: 100%;" alt="" />
                </div>
          </div>
        </div>
      </div>
    </section>


<section class="inrpage-menu">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
          <div class="filter-out">
            <a href="#" class="filter-icon"><img src="images/filter-icon.svg"  alt="" /></a>
            <span>Narrow your Results</span>
          </div>
          </div>
          <div class="col-md-5 text-right inrpage-search ml-auto">
          <form  class="searchform" name="frm_searchHead" id="frm_searchHead" method="POST" action="{{route('search')}}" autocomplete="off">
                    @csrf
              <input type="text" class="form-control" placeholder="Search for products..." name="search_word" id="search_word">
               <button class="btn btn-secondary" type="submit" >Search</button>
          </form>

          </div>
        </div>
       </div>
    </section>

  <section class="prdt-list-wrapper">
  <div class="container">

  <div class="row">
    @if(session()->has('message'))
      <div class="box no-border">
          <div class="box-tools">
              <p class="alert alert-success alert-dismissible">
                  {{ session()->get('message') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </p>
          </div>
      </div>
    @endif
 </div>
 
  <div class="row">
    <main class="col-md-12 col-xs-11 p-l-2 p-t-2">
          <div class="prdt-list-top">
              <!-- <span class="lft-clrtxt text-left"><p>Shop Ambulatory Equipmenti</p></span> -->
             <!-- <span class="rgt-txt text-right"><p>Showing 1 – 24 products of 30 products</p></span>-->
          </div>
      <div class="prdt-list-outer">
        <div class="row">
            
            @foreach($products as $values)
          <div class="col-md-3">
            
                <div class="prdt-box">
                  <div class="prdt-img">
                  <a href="{{ url('products/'.$values->slug) }}">
                  @if($values->image_name!='')
                      <img src="<?php echo asset("public/storage/products/$values->image_name") ?>" alt="{{$values->name}}" title="{{$values->name}}">
                      @endif
                   @if($values->image_name=='')
                    <img src="{{ asset('images/no-image.jpg') }}" alt="No Image" title="No Image">
                    @endif
                    </a>
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
                  </div>
                  <div class="prdt-content">
                      
                      <h2>{{$values->name}}</h2>
                  
                    {!!preg_replace('/\s+?(\S+)?$/', '', substr($values->description, 0, 120))!!}
                    @if(strlen($values->description)>120)...</p>
                    @endif
                   

                         @guest
                         
                         <button class="btn btn-warning quotebtn" type="button" onclick="window.location.href ='{{ url('products/'.$values->slug) }}'">Read More</button>
                   
                           
                          @else

                          @if($values->unit_price=="")
                           <!-- <button data-id = {{$values->id}} class="btn btn-warning request_quote quotebtn" type="button" >Request Quote</button>-->
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
            {{ $products->links() }}
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
     var product_id=$(this).attr('data-id');

     var APP_URL = {!! json_encode(url('/')) !!}
     var url = APP_URL+'/admin/addquote';
     
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
         // $("#quote-modal").modal('show');
        }
      });
 
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
           // $("#cart-modal").modal('show');
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
