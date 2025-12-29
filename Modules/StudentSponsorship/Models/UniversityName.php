<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityName extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get all students from this university
     */
    public function students(): HasMany
    {
        return $this->hasMany(UniversityStudent::class, 'university_name_id');
    }

    /**
     * Get or create university by name
     */
    public static function getOrCreateByName(string $name): self
    {
        $name = trim($name);
        
        return static::firstOrCreate(['name' => $name]);
    }
}
