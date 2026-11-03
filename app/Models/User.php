<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Especificar la tabla
    protected $table = "users";

    // Especificar los campos
    protected $fillable = [
        "name",
        "surname",
        "nick",
        "email",
        "avatar",
        "bio",
        "password",
        "created_at",
        "updated_at"
    ];
}
