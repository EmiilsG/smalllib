<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'isbn', 'total_copies', 'available_copies'];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }
}
