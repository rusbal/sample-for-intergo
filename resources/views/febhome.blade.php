@extends('layouts.feb')

@section('content')
    <div class="container-fluid text-center home-block-one">

        <h1>Consectetur adipiscin elit.</h1>

        <div class="row m-t-20">
            <div class="col-sm-4 col-sm-offset-4">
                <p>When replacing a multi-lined selection of text, the generated dummy text maintains the amount of lines. When replacing a selection of text within a single line, the amount of words is roughly being maintained.
                    </p>
            </div>
        </div>

        <a class="btn btn-primary m-t-10" href="{{ url('/register') }}">JOIN SKUBright</a>
    </div>
    <div class="container-fluid" style="background-color: #00dd00; height: 100px">

        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
    </div>
@endsection
