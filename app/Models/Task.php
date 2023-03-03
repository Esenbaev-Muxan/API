<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
    ];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suggested_users()
    {
        return $this->belongsToMany(User::class);
    }

    // public function suggestedUsers()
    // {
    //     return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id')
    //         ->withPivot('suggested_by')
    //         ->as('suggestion')
    //         ->using(TaskUser::class);
    // }

    public function suggestedUsers()
{
    return $this->belongsToMany(User::class, 'task_user')
        ->withPivot('suggested_by')
        ->as('task_user');
}


}
