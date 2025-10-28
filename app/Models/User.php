<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'ai_quota', 'ai_prompt_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    // Relasi: User punya banyak order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relasi: User punya banyak permintaan kustom
    public function customRequests()
    {
        return $this->hasMany(CustomRequest::class);
    }

    public function cart()
    {
        // Satu User 'hasOne' (punya satu) Keranjang
        return $this->hasOne(Cart::class);
    }
    
    // Relasi: User punya banyak log prompt AI
    // public function aiPromptLogs()
    // {
    //     return $this->hasMany(AiPromptLog::class);
    // }
}
