<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get full URL
     */
    public function getUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/no-image.png');
        }
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get thumbnail URL (assumes you have thumbnails stored)
     */
    public function getThumbnailUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/no-image.png');
        }
        
        // If you generate thumbnails, use them. Otherwise return main image
        $thumbPath = str_replace('products/', 'products/thumbs/', $this->image_path);
        
        if (Storage::disk('public')->exists($thumbPath)) {
            return asset('storage/' . $thumbPath);
        }
        
        return $this->url;
    }

    // ==================== METHODS ====================

    /**
     * Set as primary image (unset others)
     */
    public function setAsPrimary(): void
    {
        // Remove primary from other images
        self::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set this as primary
        $this->is_primary = true;
        $this->save();
    }

    /**
     * Delete image file and record
     */
    public function deleteWithFile(): bool
    {
        try {
            // Delete file
            if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
                Storage::disk('public')->delete($this->image_path);
            }

            // Delete thumbnail if exists
            if ($this->image_path) {
                $thumbPath = str_replace('products/', 'products/thumbs/', $this->image_path);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }

            return (bool) $this->delete();
        } catch (\Exception $e) {
            Log::error('Failed to delete product image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload and create image - IMPROVED VERSION
     * 
     * @param Product $product
     * @param UploadedFile $file
     * @param bool $isPrimary
     * @return self|null
     */
    public static function uploadForProduct(Product $product, $file, bool $isPrimary = false): ?self
    {
        // Validate file
        if (!$file || !($file instanceof UploadedFile)) {
            Log::warning('ProductImage::uploadForProduct - Invalid file object');
            return null;
        }

        if (!$file->isValid()) {
            Log::warning('ProductImage::uploadForProduct - File is not valid: ' . $file->getErrorMessage());
            return null;
        }

        // Validate mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            Log::warning('ProductImage::uploadForProduct - Invalid mime type: ' . $file->getMimeType());
            return null;
        }

        // Validate size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            Log::warning('ProductImage::uploadForProduct - File too large: ' . $file->getSize());
            return null;
        }

        try {
            // Generate unique filename
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = 'product_' . $product->id . '_' . time() . '_' . uniqid() . '.' . $extension;
            
            // Store file
            $path = $file->storeAs('products', $filename, 'public');
            
            if (!$path) {
                Log::error('ProductImage::uploadForProduct - Failed to store file');
                return null;
            }

            // Check if this is the first image for the product
            $existingCount = self::where('product_id', $product->id)->count();
            $isFirstImage = $existingCount === 0;
            
            // Get next sort order
            $sortOrder = $existingCount + 1;
            
            // If setting as primary or it's the first image, unset other primaries
            $shouldBePrimary = $isPrimary || $isFirstImage;
            if ($shouldBePrimary) {
                self::where('product_id', $product->id)->update(['is_primary' => false]);
            }
            
            // Create the image record
            $image = self::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'alt_text' => $product->name,
                'sort_order' => $sortOrder,
                'is_primary' => $shouldBePrimary,
            ]);

            Log::info('ProductImage::uploadForProduct - Image uploaded successfully', [
                'product_id' => $product->id,
                'image_id' => $image->id,
                'path' => $path,
                'is_primary' => $shouldBePrimary,
            ]);

            return $image;

        } catch (\Exception $e) {
            Log::error('ProductImage::uploadForProduct - Exception: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Bulk upload images for a product
     * 
     * @param Product $product
     * @param array $files
     * @param int $primaryIndex
     * @return array
     */
    public static function uploadMultipleForProduct(Product $product, array $files, int $primaryIndex = 0): array
    {
        $uploaded = [];
        $errors = [];

        foreach ($files as $index => $file) {
            $isPrimary = ($index === $primaryIndex);
            $image = self::uploadForProduct($product, $file, $isPrimary);
            
            if ($image) {
                $uploaded[] = $image;
            } else {
                $errors[] = "Failed to upload image at index {$index}";
            }
        }

        return [
            'uploaded' => $uploaded,
            'errors' => $errors,
            'count' => count($uploaded),
        ];
    }

    /**
     * Reorder images
     */
    public static function reorderImages(int $productId, array $imageIds): bool
    {
        try {
            foreach ($imageIds as $order => $imageId) {
                self::where('id', $imageId)
                    ->where('product_id', $productId)
                    ->update(['sort_order' => $order + 1]);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('ProductImage::reorderImages - Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure product has a primary image
     */
    public static function ensurePrimaryImage(int $productId): void
    {
        $hasPrimary = self::where('product_id', $productId)
            ->where('is_primary', true)
            ->exists();

        if (!$hasPrimary) {
            $firstImage = self::where('product_id', $productId)
                ->orderBy('sort_order')
                ->first();
            
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }
    }
}