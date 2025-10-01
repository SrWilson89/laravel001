<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Se elimina la dependencia 'use Laravel\Sanctum\HasApiTokens;' para resolver el error FatalError.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    // Se elimina el uso de HasApiTokens, ya que es la causa del error.
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Añadido para gestionar roles (admin, vip, user)
        'profile_photo_path', // Añadido para la foto de perfil
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // =============================================
    // RELACIONES
    // =============================================

    /**
     * Un usuario tiene muchas notas. (Relación One-to-Many)
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Un usuario tiene muchas notas marcadas como favoritas. (Relación Many-to-Many)
     * La tabla pivote es 'note_user_bookmarks'.
     * Esto resuelve el error 'bookmarkedNotes() does not exist'.
     */
    public function bookmarkedNotes(): BelongsToMany
    {
        // Nota: Laravel asume 'note_user' como tabla pivote si no se especifica.
        // Aquí usamos 'note_user_bookmarks' explícitamente.
        return $this->belongsToMany(Note::class, 'note_user_bookmarks');
    }

    /**
     * Un usuario puede enviar muchos mensajes. (Relación One-to-Many)
     */
    public function sentMessages(): HasMany
    {
        // En la tabla 'messages', el campo 'sender_id' es la clave foránea.
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Un usuario puede recibir muchos mensajes. (Relación One-to-Many)
     */
    public function receivedMessages(): HasMany
    {
        // En la tabla 'messages', el campo 'receiver_id' es la clave foránea.
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
