@php
    $userDetails = null;
    if (auth()->check()) {
        $userDetails = \App\Models\User::where('id', auth()->id())->first();
    }
@endphp

<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-6">
                <div class="header-left d-flex align-items-center">
                    <div class="menu-toggle-btn mr-15">
                        <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                            <i class="lni lni-chevron-left me-2"></i> Menu
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-6">
                <div class="header-right">
                    <div class="profile-box ml-15">
                        <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-info">
                                <div class="info">
                                    <div class="image">
                                        @if ($userDetails && $userDetails->avatar)
                                            <img src="{{ asset($userDetails->avatar) }}" alt="image">
                                        @else
                                            <img src="{{ asset('backend/images/profile/profile-image.png') }}"
                                                alt="image">
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-500">
                                            @if ($userDetails && $userDetails->name)
                                                {{ Auth::user()->name }}
                                            @else
                                                <p>Admin</p>
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                            <li>
                                <div class="author-info flex items-center !p-1">
                                    <div class="image">
                                        @if ($userDetails && $userDetails->avatar)
                                            <img src="{{ asset($userDetails->avatar) }}" alt="image">
                                        @else
                                            <img src="{{ asset('backend/images/profile/profile-image.png') }}"
                                                alt="image">
                                        @endif
                                    </div>
                                    <div class="content">
                                        <h4 class="text-sm">
                                            @if ($userDetails && $userDetails->name)
                                                {{ Auth::user()->name }}
                                            @else
                                                <p>Admin</p>
                                            @endif
                                        </h4>
                                        <a class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white text-xs"
                                            href=""></a>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('profile.index') }}"><i class="lni lni-user"></i> Profile </a>
                            </li>

                            <li>
                                <a href="{{ route('system.index') }}"> <i class="lni lni-cog"></i> Settings </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="lni lni-exit"></i> Sign Out </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
