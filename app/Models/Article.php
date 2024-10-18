<?php

namespace App\Models;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function index()
    {
        $articles = Article::where('user_id', Auth::id())->get();

        return Inertia::render('Articles/Index', [
            'articles' => $articles
        ]);
    }
}
