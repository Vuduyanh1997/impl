<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReposDetail extends Model
{
	protected $table = 'repos_details';

    protected $fillable = [
        'full_name', 'data', 'user_id', 'link_fork'
    ];
}
