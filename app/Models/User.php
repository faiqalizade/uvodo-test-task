<?php
namespace App\Models;

use Core\Model;

class User extends Model
{
    public static string $tableName = 'users';

    protected array $fillable = [
        'name' => ['required'],
        'surname' => ['required'],
        'email' => ['required']
    ];
}
