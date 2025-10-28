<div class="logo-img logo-new-add">
    <a href="{{ url('/') }}"><img
            src="{{ asset('frontend/images/logo.png') }}"
            alt="Logo" class="logout" style="width: 150px;"></a>

    <li class="nav-item mx-2">
    	            @if (!Auth::id())
                            <a class="nav-link become-mobile-view" href="{{ route('host.register') }}">Become a host</a>
                        </li>
                    @endif
</div>
