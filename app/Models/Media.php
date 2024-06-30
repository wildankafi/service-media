<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'ServiceMedia';
    protected $fillable = [
        'images','app','type','iddata'
    ];
    protected $hidden = ['rowstatus'];
}
