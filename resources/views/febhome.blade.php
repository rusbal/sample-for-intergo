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

    <div class="container-fluid home-block-two">

        <div class="row">
            <div class="col-lg-8 col-md-6 col-sm-12 image-div">
                <img src="/img/sku-selection-page.png" alt="">
            </div>

            <div class="col-lg-3 col-lg-offset-0 col-md-6 col-md-offset-0 col-sm-10 col-sm-offset-1 p-b-40">
                <h1>Praesent placerat</h1>
                <p>To copy a specific application window, press and hold Command-Control-Shift-4 then tap on the Spacebar. The cursor will change to a camera, which you can move around the screen. As you move the cursor over an application window, the window will be highlighted.</p>

                <ol class="custom-counter">
                    <li class="m-t-20">
                        <div class="title">Entire screen</div>
                        <p>The Macintosh operating system has always made it easy to capture a screen shot. A screen shot is an image of your computer desktop or an active window.</p>
                    </li>
                    <li class="m-t-20">
                        <div class="title">Portion of the screen</div>
                        <p>To capture a specific application window, press and hold Command-Shift-4 then tap on the Spacebar. The cursor will change to a camera, and you can move it around the screen.</p>
                    </li>
                    <li class="m-t-20">
                        <div class="title">Specific application window</div>
                        <p>As you move the cursor over an application window, the window will be highlighted.</p>
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid home-block-reviews" style="background-color:#87d9bf; min-height:300px; color: white;">

        <h2 class="text-center p-tb-20">See what other SKUBright Users are saying</h2>

        <div class="row p-b-40">
            <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">

                <div class="row">

                    <div class="col-sm-4">
                        <figure>
                            <blockquote class="p-12">To capture a specific application window, press and hold Command-Shift-4 then tap on the Spacebar. The cursor will change to a camera, and you can move it around the screen.</blockquote>
                            <footer>
                                <cite class="author"> James Smith </cite>
                                <cite class="info"> Owner, Ipsum Corp. </cite>
                            </footer>
                        </figure>
                    </div>

                    <div class="col-sm-4">
                        <figure>
                            <blockquote class="p-12">Lorem ipsum window, press and hold Command-Shift-4 then tap on the Spacebar. The cursor will change to a camera, and you can move it around the screen.</blockquote>
                            <footer>
                                <cite class="author"> Mary Olberg </cite>
                                <cite class="info"> Banana Corp. </cite>
                            </footer>
                        </figure>
                    </div>

                    <div class="col-sm-4">
                        <figure>
                            <blockquote class="p-12">Print a monkey specific application window, press and hold Command-Shift-4 then tap on the Spacebar. The cursor will change to a camera, and you can move it around the screen.</blockquote>
                            <footer>
                                <cite class="author"> Henry Guttenberg </cite>
                                <cite class="info"> Printing Corp. </cite>
                            </footer>
                        </figure>
                    </div>

                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
