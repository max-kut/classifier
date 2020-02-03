<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposedPhrase extends Model
{
    protected $table = 'proposed_phrases';

    public $timestamps = false;

    protected $fillable = ['topic'];
}
