@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="row mb-3 mt-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <form class="search-email" method="POST" action="{{route('emailList')}}">
                            @csrf
                            <input type="hidden" id="hidden_id" value="{{$password}}" name="hidden">
                            <input type="text" placeholder="Search.." name="search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <div class="card-header">{{ __('Emails') }}</div>

                <div class="card-body">
                    <?php $i=1; ?>
                    @foreach($record as $records)
                    @if(str_contains($records['subject'],'Re:'))
                    <ul class="bullets">
                        <li>
                            <h4>Subject:</h4> <span class="sub-heading ml-2">"{{$records['subject']}}"</span>
                        </li>
                        <li>
                            <h4>Date:</h4> <span class="sub-heading ml-2">{{$records['date']}}</span>
                        </li>
                        <li>
                            <h4>Body:</h4> <span class="sub-heading ml-2"> <button onClick="reply_click(this.id)"
                                    type="button" class="btn btn-primary" id="{{$i}}">Click
                                    here</button></span>
                            <div class="collapse" id="body-email-{{$i}}">{!!$records['body']!!}</div>
                        </li>
                    </ul>
                    <hr>
                    @else
                    <ul class="bullets">
                        <li>
                            <h4>Subject:</h4> <span class="sub-heading ml-2">"{{$records['subject']}}"</span>
                        </li>
                        <li>
                            <h4>Date:</h4> <span class="sub-heading ml-2">{{$records['date']}}</span>
                        </li>
                        <li>
                            <h4>Body:</h4> <span class="sub-heading ml-2"> <button onClick="reply_click(this.id)"
                                    type="button" class="btn btn-primary" id="{{$i}}">Click
                                    here</button></span>
                            <div class="collapse" id="body-email-{{$i}}">{!!$records['body']!!}</div>
                        </li>
                    </ul>
                    <hr>
                    @endif
                    <?php $i++ ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function reply_click(clicked_id)
  {
      var body = "#body-email-";
      var x = body.concat(clicked_id);
    $(x).collapse('toggle');
  }
</script>
@endsection