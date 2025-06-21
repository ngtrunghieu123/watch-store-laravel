<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;



class NguoiDung extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'nguoidung';

    protected $fillable = [
        'name',
        'email',
        'hinhanh',
        'username',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGiaSanPham::class, 'nguoidung_id', 'id');
    }
    public function DonHang(): HasMany
    {
        return $this->hasMany(DonHang::class, 'nguoidung_id', 'id');
    }
    public function BaiViet(): HasMany
    {
        return $this->hasMany(BaiViet::class, 'nguoidung_id', 'id');
    }

    public function BinhLuanBaiViet(): HasMany
    {
        return $this->hasMany(BinhLuanBaiViet::class, 'nguoidung_id', 'id');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function yeuthich()
    {
        return $this->hasMany(YeuThich::class, 'nguoidung_id', 'id');
    }
}

class CustomResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Khôi phục mật khẩu')
            ->line('Bạn vừa yêu cầu ' . config('app.name') . ' khôi phục mật khẩu của mình.')
            ->line('Liên kết đặt lại mật khẩu này sẽ hết hạn sau 60 phút.')
            ->line('Xin vui lòng nhấn vào nút "Khôi phục mật khẩu" bên dưới để tiến hành cấp mật khẩu mới.')
            ->action('Khôi phục mật khẩu', url(config('app.url') . route('password.reset', $this->token, false)))
            ->line('Nếu bạn không yêu cầu đặt lại mật khẩu, xin vui lòng không làm gì thêm và báo lại cho quản trị hệ thống về vấn đề này.');
    }
}
