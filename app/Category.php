<?php

namespace App;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable, NodeTrait;

    protected $table = 'categories';

    protected $fillable = ['category_name'];

    protected $guarded = [];
}
