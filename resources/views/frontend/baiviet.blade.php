@extends('layouts.frontend')
@section('title', $title)
@section('content')
<div class="bg-secondary py-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item">
                        <a class="text-nowrap" href="{{ route('frontend.home') }}"><i class="ci-home"></i>Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 mb-0">{{ $title }}</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="pt-3 mt-md-3">
        <div class="masonry-grid" data-columns="3">
            @php
            function LayHinhDauTien($strNoiDung)
            {
            $first_img = '';
            ob_start();
            ob_end_clean();
            $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $strNoiDung, $matches);
                if(empty($output))
                return asset('public/img/noimage.png');
                else
                return str_replace('&amp;', '&', $matches[1][0]);
                }
                @endphp
                @foreach($baiviet as $value)
                <article class="masonry-grid-item">
                    <div class="card">
                        <a class="blog-entry-thumb" href="{{ route('frontend.baiviet.chitiet', ['tenchude_slug' => $value->ChuDe->tenchude_slug, 'tieude_slug' => $value->tieude_slug . '-' . $value->id . '.html']) }}">
                            <img class="card-img-top" src="{{ LayHinhDauTien($value->noidung) }}" />
                        </a>
                        <div class="card-body">
                            <h2 class="h6 blog-entry-title">
                                <a href="{{ route('frontend.baiviet.chitiet', ['tenchude_slug' => $value->ChuDe->tenchude_slug, 'tieude_slug' => $value->tieude_slug . '-' . $value->id . '.html']) }}">
                                    {{ $value->tieude }}
                                </a>
                            </h2>
                            <p class="fs-sm" style="text-align:justify">{{ $value->tomtat }}</p>
                            <a class="btn-tag me-2 mb-2" href="{{ route('frontend.baiviet.chude', ['tenchude_slug' => $value->ChuDe->tenchude_slug]) }}">{{ $value->ChuDe->tenchude }}</a>
                        </div>
                        <div class="card-footer d-flex align-items-center fs-xs">
                            <a class="blog-entry-meta-link" href="#user">
                                <div class="blog-entry-author-ava"><img src="{{ asset('public/img/03.jpg') }}" /></div>{{ $value->NguoiDung->name }}
                            </a>
                            <div class="ms-auto text-nowrap">
                                <a class="blog-entry-meta-link text-nowrap" href="#date">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)->format('d/m/Y') }}</a>
                                <span class="blog-entry-meta-divider mx-2"></span>
                                <a class="blog-entry-meta-link text-nowrap" href="#view"><i class="ci-eye"></i>{{ $value->luotxem }}</a>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
        </div>
        <hr class="mb-4">

        <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation">
            <ul class="pagination">
                <!-- ở trang đầu thì disabled prev -->
                @if ($baiviet->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="ci-arrow-left me-2"></i>Prev</span>
                </li>
                <!-- trả về url của trang trước -->
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $baiviet->previousPageUrl() }}">
                        <i class="ci-arrow-left me-2"></i>Prev
                    </a>
                </li>
                @endif
            </ul>
            <!-- duyệt qua trang ròi đánh dấu trang hiện tại ở số giữa  -->
            <ul class="pagination">
                @for ($i = 1; $i <= $baiviet->lastPage(); $i++)
                    <li class="page-item {{ ($baiviet->currentPage() == $i) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $baiviet->url($i) }}">{{ $i }}</a>
                    </li>
                    @endfor
            </ul>

            <ul class="pagination">
                @if ($baiviet->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $baiviet->nextPageUrl() }}">
                        Next<i class="ci-arrow-right ms-2"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link">Next<i class="ci-arrow-right ms-2"></i></span>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endsection