<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPermissionRole extends Model
{
    use HasFactory;

    protected $table = 'menu_permission_role';
    public $timestamps = false;

    protected $fillable = ['role_id', 'menu_id', 'permission_id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
