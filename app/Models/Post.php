<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    /**
     * Obtiene la URL pública completa de la imagen del post.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function imagePath(): Attribute // El nombre del método debe ser camelCase del atributo deseado
    {
        return Attribute::make(
            get: fn ($value, $attributes) => isset($attributes['image_path']) && $attributes['image_path']
                ? Storage::url($attributes['image_path']) // Usa Storage::url() para obtener la URL pública
                : null, // O devuelve una imagen por defecto si lo prefieres: '/images/default-post.png'
        );
    }

    /**
     * Asegúrate de que el accesor 'image_path' se añada a las serializaciones del modelo
     * si no estás usando API Resources. Esto sobreescribirá el valor original
     * de la columna 'image_path' con el resultado del accesor en el JSON/array final.
     */
    protected $appends = ['image_path']; // ¡Importante!

    //Route Model Binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    //relationship inverse with user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //relationship inverse with category
    public function category(){
        return $this->belongsTo(Category::class);
    }

    //relationship inverse with tag
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    
}
