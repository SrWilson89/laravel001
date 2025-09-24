<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    
    // Aquí es donde añadimos 'color_class'
    protected $fillable = ['title', 'content', 'color_class'];
}