<?php

namespace App\Models;

use App\Observers\LoanObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([LoanObserver::class])]
class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'loaned_at',
        'returned_at',
        'due_at'
    ];

    protected $casts = [
        'loaned_at'   => 'datetime',
        'returned_at' => 'datetime',
        'due_at' => 'datetime'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
