<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityProgram extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get all students in this program
     */
    public function students(): HasMany
    {
        return $this->hasMany(UniversityStudent::class, 'university_program_id');
    }

    /**
     * Get or create program by name
     */
    public static function getOrCreateByName(string $name): self
    {
        $name = trim($name);
        
        return static::firstOrCreate(['name' => $name]);
    }
}
