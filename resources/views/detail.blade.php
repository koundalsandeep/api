@extends('layouts.app')

@section('content')
<?php
$totalRating = 0;
$totalReviews = 0;
$avgRating = 0;
?>
<div class="wrapper row3">
    <main class="hoc container clear">

        <div class="content three_quarter">
            <div class="col-md-12">
                <h1>{{$activity->title}}</h1>
                @if($activity->image)
                <img class="imgr borderedbox inspace-5" src="{{ asset('images/'.$activity->image) }}">
                @else
                <img class="imgr borderedbox inspace-5" src="{{ asset('images/320x220.jpg') }}" alt="">
                @endif
                <p>{{$activity->description}}</p>
            </div>
        </div>
</div>
<div class="clear"></div>
</main>
</div>

@endsection