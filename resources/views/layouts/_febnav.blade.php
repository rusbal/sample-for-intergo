<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/" class="navbar-left">
                {{ Html::image('img/skubright-logo.png', null, ['class' => 'navbar-logo']) }}
            </a>
        </div>
        <div id="navbar1" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-nav-links">
                @if (Auth::check())
                    <li class="{{ Request::path() == 'my/dashboard' ? 'active' : '' }}"><a href="/my/dashboard">Dashboard</a></li>
                @else
                    <li class="{{ Request::path() == '/' ? 'active' : '' }}"><a href="/">Home</a></li>
                @endif
                <li><a href="#">About SKUBright</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Pricing</a></li>
                @if (Auth::guest())
                    <li class="mobile-only {{ Request::path() == 'login' ? 'active' : '' }}">
                        <a href="{{ url('/login') }}">Login</a>
                    </li>
                    <li class="mobile-only {{ Request::path() == 'register' ? 'active' : '' }}">
                        <a href="{{ url('/register') }}">Register</a>
                    </li>
                @else
                    @include('layouts._myuser', ['class' => 'mobile-only'])
                @endif
            </ul>
            @if (Auth::guest())
                <ul class="nav navbar-nav navbar-nav-buttons pull-right desktop-only">
                    @if (Request::path() != 'login')
                        <li><a class="btn {{ Request::path() != 'register' ? 'btn-primary' : '' }}" href="{{ url('/login') }}">Login</a></li>
                    @endif
                    @if (Request::path() != 'register')
                        <li><a class="btn" href="{{ url('/register') }}">Register</a></li>
                    @endif
                </ul>
            @else
                <ul class="nav navbar-nav navbar-nav-links pull-right desktop-only">
                    @include('layouts._myuser')
                </ul>
            @endif
        </div>
        <!--/.nav-collapse -->
    </div>
    <!--/.container-fluid -->
</nav>
