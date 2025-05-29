<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'roles';
    protected $fillable = ['name'];


    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }
}
