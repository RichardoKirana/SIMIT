<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['supplier_name', 'address', 'phone', 'agent_name', 'partner_type'];

    public function pemasukans()
    {
        return $this->hasOne(Pemasukan::class, 'supplier_id', 'id');
    }
}
