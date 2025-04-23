<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'route'];
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function menuPermissionRoles()
    {
        return $this->hasMany(MenuPermissionRole::class);
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'menu_permission_role')
            ->withPivot('role_id');
    }
}
