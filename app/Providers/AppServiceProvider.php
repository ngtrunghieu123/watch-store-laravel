<?php

namespace App\Providers;

use App\Models\LoaiSanPham;
use App\Models\HangSanXuat;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::composer('layouts.frontend', function ($view) {
            // Lấy danh sách loại sản phẩm sắp xếp theo tên
            $loaisanpham = LoaiSanPham::orderBy('tenloai')->get();

            // Truyền biến vào view
            $view->with('loaisanpham', $loaisanpham);
        });
        View::composer('layouts.frontend', function ($view) {
            // Lấy danh sách hang sắp xếp theo tên
            $hangsanxuat = HangSanXuat::orderBy('tenhang')->get();
            // Truyền biến vào view
            $view->with('hangsanxuat', $hangsanxuat);
        });
    }
}
