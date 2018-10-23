@extends('layouts.frontend')

@section('body_class', 'page-home')

@section('highlight')
    <div class="jumbotron">
        <div class="container">
            @include('frontend.partials.slider')
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            @lang('labels.frontend.titles.home')
        </div>

        <div class="card-body">
            <p>
                Nulla quis lorem ut libero malesuada feugiat. Sed porttitor lectus nibh. Vivamus suscipit tortor
                eget felis porttitor volutpat. Nulla porttitor accumsan tincidunt. Vivamus suscipit tortor eget
                felis porttitor volutpat. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
                posuere
                cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula.
                Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Curabitur non nulla sit amet
                nisl
                tempus convallis quis ac lectus. Curabitur arcu erat, accumsan id imperdiet et, porttitor at
                sem.
                Quisque velit nisi, pretium ut lacinia in, elementum id enim.
            </p>
        </div>
    </div>

@endsection
