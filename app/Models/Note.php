<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Favoritos (funcionalidad existente)
    public function likes()
    {
        return $this->belongsToMany(User::class, 'note_user_likes');
    }

    // Marcadores (nueva funcionalidad)
    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'note_user_bookmarks');
    }
    public function likedNotes()
    {
        return $this->belongsToMany(Note::class, 'note_user_likes')->withTimestamps();
    }

    protected $fillable = [
        'title',
        'content',
        'color_class',
        'user_id',
        'is_public'
    ];
}