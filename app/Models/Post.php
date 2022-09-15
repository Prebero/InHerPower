<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'category_id',
        'description',
        'categories',
        'picture'
    ];





    public function category(){
        return $this->belongTo(Category::class);}

}
