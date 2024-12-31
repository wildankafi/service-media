<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueDelete extends Model
{
    protected $table = 'QueueDelete';
    protected $fillable = [
        'requesparam', 'folder', 'uniqkey', 'app', 'type', 'iddata', 'status', 'is_prosess'
    ];
    protected $hidden = ['rowstatus'];
}
