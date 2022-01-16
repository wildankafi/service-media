<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'service-media';
    protected $fillable = [
        'images','app','type','iddata'
    ];
    protected $hidden = ['rowstatus'];
}
