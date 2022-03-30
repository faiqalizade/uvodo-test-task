<?php
namespace App\Models;

use Core\Model;

class User extends Model
{
    public static string $tableName = 'users';

    protected static array $fillable = [
        'name' => ['required'],
        'surname' => ['required'],
        'email' => ['required']
    ];
}
