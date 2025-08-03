<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;


class Publisher extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Crypt::encryptString($value);
    }

    public function getNameAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // Caso o valor não esteja encriptado (ex: dados antigos)
            return $value;
        }
    }
}
