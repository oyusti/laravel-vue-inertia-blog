<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'extract',
        'content',
        'image_path',
        'is_published',
        'published_at',
        'user_id',
        'category_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array{
    
        return [
            
            'id' => 'integer',
            'title' => 'string',
            'slug' => 'string',
            'extract' => 'string',
            'content' => 'string',
            'image_path' => 'string',
            'user_id' => 'integer',
            'category_id' => 'integer',
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    //relationship inverse with user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //relationship inverse with category
    public function category(){
        return $this->belongsTo(Category::class);
    }

    
}
