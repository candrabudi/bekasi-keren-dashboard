<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function menuPermissions()
    {
        return $this->hasMany(MenuPermissionRole::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_permission_role')
            ->withPivot('permission_id')
            ->with('permissions')
            ->distinct();
    }
    
}
