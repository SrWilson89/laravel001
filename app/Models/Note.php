<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Note extends Model // <-- ¡CLASE CORREGIDA!
{
    use HasFactory;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'title', 
        'content', 
        'color', 
        'is_public',
        'user_id'
    ];
    
    // Campos que se deberían castear a tipos nativos
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Define la relación de uno a muchos inversa con el modelo User.
     * Una nota pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación de muchos a muchos con el modelo Tag.
     * Una nota puede tener muchos tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Define la relación de muchos a muchos con el modelo User para los 'likes'.
     * Una nota puede ser gustada por muchos usuarios.
     */
    public function likes(): BelongsToMany
    {
        // La tabla pivote es 'note_user_likes'
        return $this->belongsToMany(User::class, 'note_user_likes');
    }
}
