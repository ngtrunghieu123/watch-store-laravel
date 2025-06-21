<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <base href="{{ url('/') }}/">

    <!-- Thêm trước thẻ đóng </head> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SEO Meta Tags -->
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#ffffff" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Trang chủ') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon and Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/img/EL_180.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/img/EL_40x40_11zon.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/img/EL_16x16.png') }}" />

    <!-- CSS -->
    <link rel="stylesheet" media="screen" href="{{ asset('public/vendor/simplebar/simplebar.min.css') }}" />
    <link rel="stylesheet" media="screen" href="{{ asset('public/vendor/tiny-slider/tiny-slider.css') }}" />
    <link rel="stylesheet" media="screen" href="{{ asset('public/vendor/nouislider/nouislider.min.css') }}" />
    <link rel="stylesheet" media="screen" href="{{ asset('public/vendor/drift-zoom/drift-basic.min.css') }}" />
    <link rel="stylesheet" media="screen" href="{{ asset('public/vendor/lightgallery/lightgallery-bundle.min.css') }}" />
    <link rel="stylesheet" media="screen" href="{{ asset('public/css/theme.min.css') }}" />
    <!-- Custom CSS -->
    <link rel="stylesheet" media="screen" href="{{ asset('public/css/sanphamchitiet.css') }}" />
    <link rel="stylesheet" media="screen" href="{{ asset('public/css/home.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .star-rating {
            display: inline-flex;
            align-items: center;
        }

        .star-rating-icon {
            font-size: 14px;
            margin-right: 1px;
        }

        .star-rating-icon.active {
            color: #ffc107 !important;
            /* Gold color */
        }

        .star-rating-icon:not(.active) {
            color: #e1e1e1;
            /* Light gray */
        }
    </style>

</head>

<body class="handheld-toolbar-enabled">
    <main class="page-wrapper">
        @if(session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        </div>
        @endif
        <header class="shadow-sm">
            <div class="navbar-sticky bg-light">
                <div class="navbar navbar-expand-lg navbar-light">
                    <div class="container">
                        <a class="navbar-brand d-none d-sm-block flex-shrink-0" href="{{ route('frontend.home') }}">
                            <img src="{{ asset('public/img/logo_internalux.png') }}" width="180" />
                        </a>
                        <a class="navbar-brand d-sm-none flex-shrink-0 me-2" href="{{ route('frontend.home') }}">
                            <img src="{{ asset('public/img/logo_icon_EnTer.png') }}" width="74" />
                        </a>
                        <form action="{{ route('frontend.timkiem') }}" method="GET">
                            <div class="input-group d-none d-lg-flex mx-4" style="width: 700px;">
                                <input class="form-control rounded-start"
                                    type="text"
                                    name="tukhoa"
                                    placeholder="Nhập từ khóa tìm kiếm..."
                                    required>
                                <button class="btn btn-primary rounded-end" type="submit">
                                    <i class="ci-search"></i>
                                </button>
                            </div>
                        </form>
                        <div class="navbar-toolbar d-flex flex-shrink-0 align-items-center">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <a class="navbar-tool navbar-stuck-toggler" href="#menu">
                                <span class="navbar-tool-tooltip">Mở rộng menu</span>
                                <div class="navbar-tool-icon-box"><i class="navbar-tool-icon ci-menu"></i></div>
                            </a>
                            @guest
                            <a class="navbar-tool ms-1 ms-lg-0 me-n1 me-lg-2" href="{{ route('user.dangnhap') }}">
                                <div class="navbar-tool-icon-box"><i class="navbar-tool-icon ci-user"></i></div>
                                <div class="navbar-tool-text ms-n3"><small>Xin chào</small>Khách hàng</div>
                            </a>
                            @else
                            <a class="navbar-tool ms-1 ms-lg-0 me-n1 me-lg-2" href="{{ route('user.home') }}">
                                <div class="navbar-tool-icon-box"><i class="navbar-tool-icon ci-user"></i></div>
                                <div class="navbar-tool-text ms-n3"><small>Xin chào</small>{{ Auth::user()->name }}</div>
                            </a>
                            @endguest
                            <div class="navbar-tool ms-3">
                                <a class="navbar-tool-icon-box bg-secondary" href="{{ route('frontend.giohang') }}">
                                    <span class="navbar-tool-label">{{ Cart::count() ?? 0 }}</span><i class="navbar-tool-icon ci-cart"></i>
                                </a>
                                <a class="navbar-tool-text" href="{{ route('frontend.giohang') }}"><small>Giỏ hàng</small>{{ Cart::priceTotal() }}đ</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navbar navbar-expand-lg navbar-light navbar-stuck-menu mt-n2 pt-0 pb-2">
                    <div class="container">
                        <div class="collapse navbar-collapse" id="navbarCollapse">
                            <div class="input-group d-lg-none my-3">
                                <i class="ci-search position-absolute top-50 start-0 translate-middle-y text-muted fs-base ms-3"></i>
                                <input class="form-control rounded-start" type="text" placeholder="Tìm kiếm" />
                            </div>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link ps-lg-0" href="{{ route('frontend.home') }}">
                                        <i class="ci-home me-2"></i>Trang chủ
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        <i class="ci-gift me-2"></i>Sản phẩm
                                    </a>
                                    <div class="dropdown-menu">
                                        <!-- Đồng hồ cơ -->
                                        <div class="dropdown dropend">
                                            <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">Đồng hồ cơ</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => 'dong-ho-co']) }}">
                                                    Tất cả đồng hồ cơ
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                @foreach($hangsanxuat as $hang)
                                                @if($hang->SanPham->where('loaisanpham_id', 1)->count() > 0)
                                                <a class="dropdown-item" href="{{ route('frontend.sanpham.hangsanxuat', ['tenhang_slug' => $hang->tenhang_slug]) }}">
                                                    {{ $hang->tenhang }}
                                                </a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Đồng hồ thông minh -->
                                        <div class="dropdown dropend">
                                            <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">Đồng hồ thông minh</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => 'dong-ho-thong-minh']) }}">
                                                    Tất cả đồng hồ thông minh
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                @foreach($hangsanxuat as $hang)
                                                @if($hang->SanPham->where('loaisanpham_id', 2)->count() > 0)
                                                <a class="dropdown-item" href="{{ route('frontend.sanpham.hangsanxuat', ['tenhang_slug' => $hang->tenhang_slug]) }}">
                                                    {{ $hang->tenhang }}
                                                </a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- Dropdown lọc giá -->
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">Giá</a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('frontend.sanpham.gia', ['mucgia' => 'duoi-1-trieu']) }}">
                                                        Dưới 1 triệu
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('frontend.sanpham.gia', ['mucgia' => '1-9-trieu']) }}">
                                                        Từ 1 - 9 triệu
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('frontend.sanpham.gia', ['mucgia' => 'tren-10-trieu']) }}">
                                                        Trên 10 triệu
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('frontend.khuyenmai') }}">
                                        <i class="ci-discount text-danger me-1"></i>Khuyến mãi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('frontend.baiviet') }}"><i class="ci-globe me-2"></i>Tin tức</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('frontend.tuyendung') }}"><i class="ci-loudspeaker me-2"></i>Tuyển dụng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('frontend.lienhe') }}"><i class="ci-support me-2"></i>Liên hệ</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    <footer class="footer bg-secondary">
        <div class="pt-5 bg-darker">
            <div class="container">
                <div class="row pb-2">
                    <div class="col-md-6 text-center text-md-start mb-4">
                        <style>
                            .footer-logo {
                                transition: filter 0.3s ease;
                            }

                            .footer-logo:hover {
                                filter: brightness(0) invert(1);
                                /* Makes image white on hover */
                            }
                        </style>

                        <div class="text-nowrap mb-4">
                            <a class="d-inline-block align-middle mt-n1 me-3" href="#">
                                <img class="d-block footer-logo"
                                    src="{{ asset('public/img/logo_footer-Photoroom.png') }}"
                                    width="200" />
                            </a>
                        </div>
                        <div class="widget widget-links widget-light">
                            <ul class="widget-list d-flex flex-wrap justify-content-center justify-content-md-start">
                                <li class="widget-list-item me-4"><a class="widget-list-link" href="{{ route('frontend.home') }}">Trang chủ</a></li>
                                <li class="widget-list-item me-4"><a class="widget-list-link" href="{{ route('frontend.sanpham') }}">Sản phẩm</a></li>
                                <li class="widget-list-item me-4"><a class="widget-list-link" href="{{ route('frontend.baiviet') }}">Tin tức</a></li>
                                <li class="widget-list-item me-4"><a class="widget-list-link" href="{{ route('frontend.tuyendung') }}">Tuyển dụng</a></li>
                                <li class="widget-list-item me-4"><a class="widget-list-link" href="{{ route('frontend.lienhe') }}">Liên hệ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 text-center text-md-end mb-4">
                        <div class="mb-3">
                            <a class="btn-social bs-light bs-twitter ms-2 mb-2" href="#"><i class="ci-twitter"></i></a>
                            <a class="btn-social bs-light bs-facebook ms-2 mb-2" href="#"><i class="ci-facebook"></i></a>
                            <a class="btn-social bs-light bs-instagram ms-2 mb-2" href="#"><i class="ci-instagram"></i></a>
                            <a class="btn-social bs-light bs-pinterest ms-2 mb-2" href="#"><i class="ci-pinterest"></i></a>
                            <a class="btn-social bs-light bs-youtube ms-2 mb-2" href="#"><i class="ci-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="pb-4 fs-xs text-light opacity-50 text-center text-md-start">Bản quyền © 2023 bởi {{ config('app.name', 'Laravel') }}.</div>
            </div>
        </div>
    </footer>
    <style>
        .bg-dark {
            background-color: #2b3035 !important;
            /* Slightly lighter than bg-darker */
        }
    </style>

    <a class="btn-scroll-top" href="#top" data-scroll>
        <span class="btn-scroll-top-tooltip text-muted fs-sm me-2">Top</span>
        <i class="btn-scroll-top-icon ci-arrow-up"></i>
    </a>

    <script src="{{ asset('public/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/vendor/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/vendor/tiny-slider/tiny-slider.js') }}"></script>
    <script src="{{ asset('public/vendor/smooth-scroll/smooth-scroll.polyfills.min.js') }}"></script>
    <script src="{{ asset('public/vendor/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ asset('public/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('public/vendor/shufflejs/shuffle.min.js') }}"></script>
    <script src="{{ asset('public/vendor/lightgallery/lightgallery.min.js') }}"></script>
    <script src="{{ asset('public/vendor/lightgallery/plugins/fullscreen/lg-fullscreen.min.js') }}"></script>
    <script src="{{ asset('public/vendor/lightgallery/plugins/zoom/lg-zoom.min.js') }}"></script>
    <script src="{{ asset('public/vendor/lightgallery/plugins/video/lg-video.min.js') }}"></script>
    <script src="{{ asset('public/vendor/drift-zoom/Drift.min.js') }}"></script>
    <script src="{{ asset('public/js/theme.min.js') }}"></script>
    <!-- Thêm trước thẻ đóng </body> -->
    @if(Session::has('toast_success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ Session::get("toast_success") }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif
    <!-- Chatbot -->
    @include('layouts.chatbot')
    <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
    <script src="{{ asset('public/js/chatbot.js') }}"></script>
</body>

</html>