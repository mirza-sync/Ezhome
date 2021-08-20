<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
  <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
          {{ config('app.name', 'EZHOME') }}
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav mr-auto">
            @if (Auth::guard('landlord')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check())
                @if (Auth::guard('landlord')->check())
                    <li class="nav-item"><a class="nav-link" href="/landlord">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/homestay">My Homestays</a></li>
                    <li class="nav-item"><a class="nav-link" href="/booking">Booking List</a></li>
                    <li class="nav-item"><a class="nav-link" href="/homestay/create">Create Homestay</a></li>
                @elseif(Auth::guard('admin')->check())
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/users">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/landlords">Landlords</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/facilities">Facilities</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/homestay">Homestay List</a></li>
                    <li class="nav-item"><a class="nav-link" href="/booking">My Bookings</a></li>
                @endif
            @else
                <li>Guest</li>
            @endif
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
              <!-- Authentication Links -->
              @if (Auth::guard('landlord')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check())
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        @if (Auth::guard('landlord')->check())
                            {{Auth::guard('landlord')->user()->name}}
                        @elseif(Auth::guard('admin')->check())
                            {{Auth::guard('admin')->user()->name}}
                        @else
                            {{Auth::user()->name}}
                        @endif
                         <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
              @else
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"  id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link" href="/login">Login</a>   
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="{{ route('login') }}">User</a>
                    <a class="dropdown-item" href="{{ route('landlord.login') }}">Landlord</a>
                    <a class="dropdown-item" href="{{ route('admin.login') }}">Admin</a>
                </div>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
              @endif
          </ul>
      </div>
  </div>
</nav>
