<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaImage extends Model
{
    protected $table = 'ServiceMediaImage';
    protected $fillable = [
        'images','app','type','iddata','uniqkey','mimetype'
    ];
    protected $hidden = ['rowstatus'];
}
