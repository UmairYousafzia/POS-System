<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
   protected $fillable = [

            'project_id',
            'task',
            'start_date',
            'end_date',
            'created_by'

   ];

   public  function users(): BelongsToMany
   {
       return $this->belongsToMany(User::class);

   }
    public function projects(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');

    }
    public function  createBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
