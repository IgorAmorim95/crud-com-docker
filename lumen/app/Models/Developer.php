<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'sexo',
        'idade',
        'hobby',
        'datanascimento'
    ];

    public function getDatanascimentoAttribute($value) {
        return Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    static function sexoTypes() {
        return collect([
            'M',
            'F',
            'O'
        ]);
    }
}
