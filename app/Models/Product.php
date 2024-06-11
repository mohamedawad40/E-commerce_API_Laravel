<?php

namespace App\Models;

use App\Http\Controllers\ProductController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=['name' , 'description' , 'price' , 'image'];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function attributes(){
        return $this->hasMany(Attribute::class);
    }
}
