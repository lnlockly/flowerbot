<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ChipBot</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('base/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <!-- endinject -->
    @laravelViewsStyles
    @notifyCss
</head>

<body>
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo me-5"><img src="{{ asset('/images/icon.svg') }}"
                    class="me-2" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini"><img src="{{ asset('/images/icon.svg') }}" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <ul class="navbar-nav navbar-nav-right" style="margin-right:10px">
                <li class="nav-item nav-profile dropdown" style="margin-right:10px">
                    @if (auth()->user()->current_shop != null)
                        <a class="nav-link dropdown-toggle"  data-toggle="dropdown" id="shopsDropdown" style="margin-right:10px">
                            <div class="nav-username">{{ auth()->user()->current_shop->username }}</div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="nav-link dropdown-item" href="{{ route('shop.switch') }}"  style="margin-right:10px">
                                <div class="nav-username">{{ auth()->user()->shops()->where('id', '!=',  auth()->user()->current_shop->id)->first()->username }}</div>
                            </a>
                        </div>
                        <div class="id-username">Ваш ID: {{ auth()->user()->id }}</div>
                    @endif
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="ti-view-list"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('statistic.catalogs') }}">
                        <span class="menu-title">Мои букеты</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('statistic.flowers') }}">
                        <span class="menu-title">Мои цветы</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('statistic.orders') }}">
                        <span class="menu-title">Мои заказы</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.create') }}">
                        <span class="menu-title">Добавить магазин</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->

    <!-- container-scroller -->
    @laravelViewsScripts
    <!-- plugins:js -->
    <script src="vendors/base/vendor.bundle.base.js"></script>

    <!-- inject:js -->
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- End custom js for this page-->
    <!-- footer -->
    <footer>

    </footer>
    <x:notify-messages />
    @notifyJs
    <style>
        .notify {
            z-index: 1000000;
            align-items: flex-end;
        }
    </style>
</body>


</html>
