<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description','cat_id'];

    public function cat(){
        return $this->belongsTo(Category::class ,'foreign_key', 'cat_id');
    }

}
