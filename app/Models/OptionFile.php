<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionFile extends Model
{
    protected $fillable = ['key', 'filename', 'mime_type', 'data'];

    /**
     * Get file as base64 data URL
     */
    public static function getDataUrl(string $key): ?string
    {
        $file = static::where('key', $key)->first();
        
        if (!$file || !$file->data) {
            return null;
        }

        return 'data:' . $file->mime_type . ';base64,' . base64_encode($file->data);
    }

    /**
     * Save file from upload
     */
    public static function saveFile(string $key, $uploadedFile): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'filename' => $uploadedFile->getClientOriginalName(),
                'mime_type' => $uploadedFile->getMimeType(),
                'data' => file_get_contents($uploadedFile->getRealPath()),
            ]
        );
    }

    /**
     * Delete file
     */
    public static function deleteFile(string $key): void
    {
        static::where('key', $key)->delete();
    }
}