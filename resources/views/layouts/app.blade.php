@auth
    <!-- Role-based content here -->
@else
    <script>window.location.href = '{{ route('login') }}';</script>
@endauth

<!--
=========================================================
* Corporate UI - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/corporate-ui
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if (config('app.is_demo'))
        <title itemprop="name">
            Corporate UI Dashboard Laravel by Creative Tim & UPDIVISION
        </title>
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@CreativeTim" />
        <meta name="twitter:creator" content="@CreativeTim" />
        <meta name="twitter:title" content="Corporate UI Dashboard Laravel by Creative Tim & UPDIVISION" />
        <meta name="twitter:description" content="Fullstack tool for building Laravel apps with hundreds of UI components and
            ready-made CRUDs" />
        <meta name="twitter:image"
            content="https://s3.amazonaws.com/creativetim_bucket/products/737/original/corporate-ui-dashboard-laravel.jpg?1695288974" />
        <meta name="twitter:url" content="https://www.creative-tim.com/live/corporate-ui-dashboard-laravel" />
        <meta name="description" content=""Fullstack tool for building Laravel apps with hundreds of UI components
            and ready-made CRUDs">
        <meta name="keywords"
            content="creative tim, updivision, html dashboard, laravel, api, html css dashboard laravel,  Corporate UI Dashboard Laravel,  Corporate UI Laravel,  Corporate Dashboard Laravel, UI Dashboard Laravel, Laravel admin, laravel dashboard, Laravel dashboard, laravel admin, web dashboard, bootstrap 5 dashboard laravel, bootstrap 5, css3 dashboard, bootstrap 5 admin laravel, frontend, responsive bootstrap 5 dashboard, corporate dashboard laravel,  Corporate UI Dashboard Laravel">
        <meta property="og:app_id" content="655968634437471">
        <meta property="og:type" content="product">
        <meta property="og:title" content="Corporate UI Dashboard Laravel by Creative Tim & UPDIVISION">
        <meta property="og:url" content="https://www.creative-tim.com/live/corporate-ui-dashboard-laravel">
        <meta property="og:image"
            content="https://s3.amazonaws.com/creativetim_bucket/products/737/original/corporate-ui-dashboard-laravel.jpg?1695288974">
        <meta property="product:price:amount" content="FREE">
        <meta property="product:price:currency" content="USD">
        <meta property="product:availability" content="in Stock">
        <meta property="product:brand" content="Creative Tim">
        <meta property="product:category" content="Admin &amp; Dashboards">
        <meta name="data-turbolinks-track" content="false">
    @endif
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>
        Corporate UI by Creative Tim & UPDIVISION
    </title>
    <!--     Fonts and icons     -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700"
        rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/349ee9c857.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/corporate-ui-dashboard.css?v=1.0.0') }}" rel="stylesheet" />
    
    <!-- Bootstrap CSS (jika diperlukan) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS untuk perbaikan tampilan -->
    <style>
        .main-content {
            margin-left: 17rem;
            transition: all 0.15s ease-in-out;
        }
        
        @media (max-width: 1199.98px) {
            .main-content {
                margin-left: 0;
            }
        }
        
        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.75rem;
        }
        
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 0.5rem;
            border: none;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    @php
        $topSidenavArray = ['wallet', 'profile'];
        $topSidenavTransparent = ['signin', 'signup'];
        $topSidenavRTL = ['RTL'];
    @endphp
    @if (in_array(request()->route()->getName(), $topSidenavArray))
        <x-sidenav-top />
    @elseif(in_array(request()->route()->getName(), $topSidenavTransparent))

    @elseif(in_array(request()->route()->getName(), $topSidenavRTL))
    @else
        <x-app.sidebar />
    @endif

    @if(isset($slot))
        {{ $slot }}
    @else
        @yield('content')
    @endif

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <!-- Hapus bootstrap lokal -->
    <!-- <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script> -->
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    
    <!-- Control Center for Corporate UI Dashboard -->
    <script src="{{ asset('assets/js/corporate-ui-dashboard.min.js?v=1.0.0') }}"></script>
    
    <!-- Custom JavaScript untuk form validation -->
    <script>
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        
        // Auto-hide alerts
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-dismissible')) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>
</body>

</html>
