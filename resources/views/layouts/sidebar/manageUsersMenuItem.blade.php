@auth()
    @if (auth()->user()->ownsCurrentWorkspace())
        <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fa-fw fas fa-users mr-2"></i><span>{{ __('Manage Users') }}</span>
            </a>
        </li>
         <li class="nav-item {{ request()->is('from-emails*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('from-emails.index') }}">
                <i class="fa-fw fas fa-users mr-2"></i><span>{{ __('Manage From Emails') }}</span>
            </a>
        </li>
    @endif
@endauth
