<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    // $table->string('name');
    // $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    // $table->string("type")->nullable();
    // $table->string("address")->nullable();
    // $table->string("profile")->nullable();
    // $table->string("description")->nullable();

    protected $fillable=[
        "name",
        "user_id",
        "type",
        "address",
        "profile",
        "description"
    ];
}
