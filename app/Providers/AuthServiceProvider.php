<?php
// app/Providers/AuthServiceProvider.php

use App\Models\Note;
use App\Policies\NotePolicy;

class AuthServiceProvider
{
    protected $policies = [
        Note::class => NotePolicy::class,
    ];
}
