@if (Auth::check())
    <li class="dropdown {{ isset($class) ? $class : '' }}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>

        <ul class="dropdown-menu dropdown-menu-right" role="menu">
            <li><a href="{{ url('/my/dashboard') }}"> Dashboard </a></li>
            <li><a href="{{ url('/my/settings') }}"> Settings </a></li>
            <li><a href="{{ url('/my/subscription') }}"> Subscription </a></li>

            @if (isUserSubscribed())
                <li><a href="{{ url('/my/invoices') }}"> Invoices </a></li>
            @endif

            <li role="separator" class="divider"></li>
            <li><a href="{{ url('/my/reports/daily-revenue') }}"> Revenue Reports </a></li>
            <li><a href="{{ url('/my/reports/offer-violations') }}"> Offer Violations </a></li>
            <li><a href="{{ url('/my/reports/price-violations') }}"> Price Violations </a></li>

            <li role="separator" class="divider"></li>
            <li>
                <a href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </li>
@endif
