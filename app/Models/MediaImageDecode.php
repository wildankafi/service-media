<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaImageDecode extends Model
{
    protected $table = 'ServiceMediaImageDecode';
    protected $fillable = [
        'status','idmedia','uniqkey','isDecode'
    ];
    protected $hidden = ['rowstatus'];
}
