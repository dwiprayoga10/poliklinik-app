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
        'nama',
        'alamat',
        'no_ktp',
        'no_hp',
        'no_rm',
        'role',
        'id_poli',
        'email',
        'password'
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

    public static function generateNoRM()
{
    // Format: Tahun + Bulan + '-' + Nomor urut
    $tanggal = date('Ym'); // contoh: 202510

    // Cari user terakhir dengan no_rm yang mirip (misal bulan yang sama)
    $last = self::where('no_rm', 'like', $tanggal . '%')->orderBy('id', 'desc')->first();

    if ($last) {
        // Ambil 3 digit terakhir
        $lastNumber = (int) substr($last->no_rm, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '001';
    }

    return $tanggal . '-' . $newNumber; // contoh hasil: 202510-001
}





public function poli(){
    return $this->belongsTo(Poli::class, 'id_poli');
}

public function jadwalPeriksas(){
    return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
}

}
