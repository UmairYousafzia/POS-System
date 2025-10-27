<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{asset('assets/images/favicon-32x32.png')}}" type="image/png" />
    <!--plugins-->
    <link href="{{asset('assets/plugins/notifications/css/lobibox.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet" />

    <!-- loader-->
    <link href="{{asset('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/js/pace.min.js')}}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('assets/css/dark-theme.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/semi-dark.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/header-colors.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>@yield('title')</title>
</head>

<body>
<!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{asset('assets/images/logo-icon.png')}}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text" style="margin-top: 15px;">POS SYSTEM</h4>
                </div>
                
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <div class="parent-icon"> <i class="bx bx-home-circle"></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                @can('profile.edit')
                    <li class="{{ request()->routeIs('profile.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('profile.edit') }}"><i class="fa-solid fa-id-card mx-2"></i>Profile</a>
                    </li>
                @endcan
                {{-- POS Menu --}}
                @canany(['pos.sell','pos.purchase','pos.products.view','pos.products.manage','pos.parties.view','pos.parties.manage','pos.sales.view','pos.purchases.view','pos.expenses.view','pos.expenses.manage','pos.locations.manage','pos.warehouses.manage','pos.stock.view','pos.backup.manage'])
                    <li class="{{ request()->routeIs('pos.*') ? 'mm-active' : '' }}">
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-barcode-reader'></i></div>
                            <div class="menu-title">POS</div>
                        </a>
                        <ul class="{{ request()->routeIs('pos.*') ? 'mm-collapse mm-show' : '' }}">
                            @can('pos.sell')
                                <li class="{{ request()->routeIs('pos.sell') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.sell') }}"><i class="bx bx-right-arrow-alt"></i>Sell</a>
                                </li>
                            @endcan
                            @can('pos.purchase')
                                <li class="{{ request()->routeIs('pos.purchase') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.purchase') }}"><i class="bx bx-right-arrow-alt"></i>Purchase</a>
                                </li>
                            @endcan
                            @canany(['pos.products.view','pos.products.manage'])
                                <li class="{{ request()->routeIs('pos.products.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.products.index') }}"><i class="bx bx-right-arrow-alt"></i>Products</a>
                                </li>
                            @endcanany
                            @canany(['pos.parties.view','pos.parties.manage'])
                                <li class="{{ request()->routeIs('pos.parties.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.parties.index') }}"><i class="bx bx-right-arrow-alt"></i>Clients & Suppliers</a>
                                </li>
                            @endcanany
                            @can('pos.sales.view')
                                <li class="{{ request()->routeIs('pos.sales.index') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.sales.index') }}"><i class="bx bx-right-arrow-alt"></i>Sales History</a>
                                </li>
                            @endcan
                            @can('pos.purchases.view')
                                <li class="{{ request()->routeIs('pos.purchases.index') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.purchases.index') }}"><i class="bx bx-right-arrow-alt"></i>Purchases History</a>
                                </li>
                            @endcan
                            @canany(['pos.expenses.view','pos.expenses.manage'])
                                <li class="{{ request()->routeIs('pos.expenses.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.expenses.index') }}"><i class="bx bx-right-arrow-alt"></i>Expenses</a>
                                </li>
                            @endcanany
                            @can('pos.locations.manage')
                                <li class="{{ request()->routeIs('pos.locations.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.locations.index') }}"><i class="bx bx-right-arrow-alt"></i>Locations</a>
                                </li>
                            @endcan
                            @can('pos.warehouses.manage')
                                <li class="{{ request()->routeIs('pos.warehouses.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.warehouses.index') }}"><i class="bx bx-right-arrow-alt"></i>Warehouses</a>
                                </li>
                            @endcan
                            @can('pos.stock.view')
                                <li class="{{ request()->routeIs('pos.stock.index') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.stock.index') }}"><i class="bx bx-right-arrow-alt"></i>Stock On Hand</a>
                                </li>
                            @endcan
                            @can('pos.backup.manage')
                                <li class="{{ request()->routeIs('pos.backup.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('pos.backup.index') }}"><i class="bx bx-right-arrow-alt"></i>Backup</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['settings.users.view','settings.roles.view'])
                    <li class="{{ request()->routeIs('settings.*') ? 'mm-active' : '' }}">
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cart'></i>
                            </div>
                            <div class="menu-title">Setting</div>
                        </a>
                        <ul class="{{ request()->routeIs('settings.*') ? 'mm-collapse mm-show' : '' }}">

                           @canany(['settings.users.view','settings.users.manage'])
                                <li class="{{ request()->routeIs('settings.users.*') ? 'mm-active' : '' }}">
                                    <a href="{{route('settings.users.index')}}"><i class="fa-regular fa-user  mx-2"></i>Users</a>
                                </li>
                           @endcanany

                            @canany(['settings.roles.view','settings.roles.manage'])
                                <li class="{{ request()->routeIs('settings.roles.*') ? 'mm-active' : '' }}">
                                    <a href="{{route('settings.roles.index')}}"><i class="fa-solid fa-circle-dot"></i>Roles</a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
            </ul>
            <!--end navigation-->

        </div>

    </div>

    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand">
                <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                </div>
                <div class="search-bar flex-grow-1">
                    <div class="position-relative search-bar-box">
                        <input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                        <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
                    </div>
                </div>
                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item mobile-search-icon">
                            <a class="nav-link" href="#">	<i class='bx bx-search'></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">1</span>
                                <i class='bx bx-bell'></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:;">
                                    <div class="msg-header">
                                        <p class="msg-header-title">Notifications</p>
                                        <p class="msg-header-clear ms-auto">Marks all as read</p>
                                    </div>
                                </a>
                                <div class="header-notifications-list">
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
												ago</span></h6>
                                                <p class="msg-info">5 new user registered</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a href="javascript:;">
                                    <div class="text-center msg-footer">View All Notifications</div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="user-box dropdown">
                    <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       @php($avatar = auth()->user()->avatar)
<img src="{{ $avatar ? asset('storage/'.$avatar) : asset('assets/images/avatars/avatar-1.png') }}"
     class="user-img"
     alt="user avatar"
     onerror="this.onerror=null;this.src='{{ asset('assets/images/avatars/avatar-1.png') }}';">
                        <div class="user-info ps-3">
                            <p class="user-name mb-0">{{ auth()->user()->name }}</p>
                            <p class="designation mb-0">@foreach(auth()->user()->roles as $role)
                                {{$role->name}}
                            @endforeach</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class='bx bx-log-out-circle'></i><span>Logout</span>
                            </a>
                            <li>
                            <a href="{{route('profile.edit')}}" class="dropdown-item"><i class="bx bx-right-arrow-alt "></i><span>Profile</span></a>
                             </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="page-wrapper">
            <div class="page-content">
                @yield('content')           
        </div>
    </div>

    <footer class="page-footer">
        <p class="mb-0">Copyright © {{ now()->format('Y') }}. All right reserved.</p>
    </footer>


    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('assets/plugins/chartjs/js/Chart.min.js')}}"></script>
    <script src="{{asset('assets/plugins/chartjs/js/Chart.extension.js')}}"></script>
    <script src="{{asset('assets/plugins/sparkline-charts/jquery.sparkline.min.js')}}"></script>
    <!--notification js -->
    <script src="{{asset('assets/plugins/notifications/js/lobibox.min.js')}}"></script>
    <script src="{{asset('assets/plugins/notifications/js/notifications.min.js')}}"></script>
    <script src="{{asset('assets/js/index.js')}}"></script>
    <!--app JS-->
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/._dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <!-- Add this in your layout or view -->
<script src="https://cdn.jsdelivr.net/gh/msurguy/MultiSelectTag@latest/dist/multiselect.min.js"></script>

 <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        toastr.options = {
            "progressBar": true,
        }
        @if(session('success'))
            toastr.success(@json(session('success')));
        @endif
        @if(session('error'))
            toastr.error(@json(session('error')));
        @endif
        @if($errors->any())
            @foreach($errors->all() as $e)
                toastr.error(@json($e));
            @endforeach
        @endif
    </script>

    @yield('script')
    <script src="{{asset('assets/js/app.js')}}"></script>

</body>

</html>
