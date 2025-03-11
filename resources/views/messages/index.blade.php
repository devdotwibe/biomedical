<div class="message-wrapper">
    <ul class="messages">
        @foreach($messages as $message)
            <li class="message clearfix">
                {{--if message from id is equal to auth id then it is sent by logged in user --}}
                <div class="{{ ($message->from == session('MARKETSPACE_ID')) ? 'sent' : 'received' }}">
                @php
                $from = App\Marketspace::where('id', '=', $message->from)->first();
                @endphp
               
                    @if($from)
                    @if($from->image!='')
                    <img width=="50px" height="30px" src="{{ asset('storage/app/public/masterspace/'.$from->image) }}" id="category-img-tag"  />
                    @endif
                    @if($from->image=='')
                    <img width=="50px" height="30px" src="{{ asset('images/noimage.jpg') }}" id="category-img-tag">
                    @endif

                   
                @endif

               

                    <p>{{ $message->message }}</p>
                    <p class="date">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</p>
                </div>
            </li>
        @endforeach
    </ul>
</div>

<div class="input-text">
    <input type="text" name="message" class="submit" placeholder="Please type any message and enter">
</div>