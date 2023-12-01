<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiResponse extends Model
{
    protected $fillable = [
        'code',
        'type',
        'message'
    ];

    public static function success($message)
    {
        return new self([
            'code' => 200,
            'type' => 'success',
            'message' => $message,
        ]);
    }

    public static function error($code, $message)
    {
        return new self([
            'code' => $code,
            'type' => 'error',
            'message' => $message,
        ]);
    }
}
