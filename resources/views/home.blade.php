@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="wrapper row0">
            <div class="hoc container clear">
                <ul class="nospace elements">
                    @foreach ($activities as $activity)
                    <li class="one_third">
                        <article class="center">
                            <a href="#">
                                @if($activity->image)
                                <img src="{{ asset('images/'.$activity->image) }}">
                                @else
                                <img src="{{ asset('images/320x220.jpg') }}" alt="">
                                @endif
                            </a>
                            <div class="txtwrap">
                                <!-- <time class="font-xs block" datetime="2045-04-06">{{date('d M y',strtotime($activity->created_at))}}</time> -->
                                <h6 class="heading">{{$activity->title}}</h6>
                                <p>{{substr($activity->description,0,60)}}</p>
                                <footer><a href="{{ url('detail/'.Crypt::encrypt($activity->id)) }}">Read More »</a></footer>
                            </div>
                        </article>
                    </li>
                    @endforeach

                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
@endsection