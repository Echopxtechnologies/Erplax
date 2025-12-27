<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class ProductImage extends Model
{
    protected $table = 'product_images';
    
    protected $fillable = [
        'product_id',
        'variation_id',
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

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }

    // ==================== ACCESSORS ====================

    public function getUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/no-image.png');
        }
        
        // Check if it's a full URL
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->url; // Same as main for now
    }

    // ==================== STATIC METHODS ====================

    /**
     * Upload image for a product - SIMPLIFIED & ROBUST
     */
    public static function uploadForProduct(Product $product, $file, bool $isPrimary = false, ?int $variationId = null): ?self
    {
        try {
            // Validate file
            if (!$file || !($file instanceof UploadedFile)) {
                Log::warning('ProductImage: Invalid file object', ['product_id' => $product->id]);
                return null;
            }

            if (!$file->isValid()) {
                Log::warning('ProductImage: File not valid', [
                    'product_id' => $product->id,
                    'error' => $file->getErrorMessage()
                ]);
                return null;
            }

            // Validate mime type
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'];
            $mime = $file->getMimeType();
            if (!in_array($mime, $allowedMimes)) {
                Log::warning('ProductImage: Invalid mime type', ['mime' => $mime]);
                return null;
            }

            // Validate size (max 5MB to be safe)
            if ($file->getSize() > 5 * 1024 * 1024) {
                Log::warning('ProductImage: File too large', ['size' => $file->getSize()]);
                return null;
            }

            // Ensure storage directory exists
            $storagePath = storage_path('app/public/products');
            if (!File::isDirectory($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }

            // Generate unique filename
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = 'product_' . $product->id . '_' . time() . '_' . uniqid() . '.' . strtolower($extension);
            
            // Store file using Laravel's storage
            $path = $file->storeAs('products', $filename, 'public');
            
            if (!$path) {
                Log::error('ProductImage: Failed to store file', ['product_id' => $product->id]);
                return null;
            }

            // Check if this is the first image
            $existingCount = self::where('product_id', $product->id)->count();
            $isFirstImage = $existingCount === 0;
            
            // Determine if should be primary
            $shouldBePrimary = $isPrimary || $isFirstImage;
            
            // If setting as primary, unset others
            if ($shouldBePrimary) {
                self::where('product_id', $product->id)->update(['is_primary' => false]);
            }
            
            // Create record
            $image = self::create([
                'product_id' => $product->id,
                'variation_id' => $variationId,
                'image_path' => $path,
                'alt_text' => $product->name,
                'sort_order' => $existingCount + 1,
                'is_primary' => $shouldBePrimary,
            ]);

            Log::info('ProductImage: Uploaded successfully', [
                'product_id' => $product->id,
                'image_id' => $image->id,
                'path' => $path,
                'is_primary' => $shouldBePrimary,
            ]);

            return $image;

        } catch (\Exception $e) {
            Log::error('ProductImage: Upload exception', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Upload multiple images
     */
    public static function uploadMultipleForProduct(Product $product, array $files, int $primaryIndex = 0): array
    {
        $uploaded = [];
        $errors = [];

        foreach ($files as $index => $file) {
            if (!$file || !$file->isValid()) {
                $errors[] = "Invalid file at index {$index}";
                continue;
            }
            
            $isPrimary = ($index === $primaryIndex);
            $image = self::uploadForProduct($product, $file, $isPrimary);
            
            if ($image) {
                $uploaded[] = $image;
            } else {
                $errors[] = "Failed to upload file at index {$index}";
            }
        }

        return [
            'uploaded' => $uploaded,
            'errors' => $errors,
            'count' => count($uploaded),
        ];
    }

    /**
     * Set as primary image
     */
    public function setAsPrimary(): void
    {
        self::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        $this->is_primary = true;
        $this->save();
    }

    /**
     * Delete image with file
     */
    public function deleteWithFile(): bool
    {
        try {
            if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
                Storage::disk('public')->delete($this->image_path);
            }
            return (bool) $this->delete();
        } catch (\Exception $e) {
            Log::error('ProductImage: Delete failed', ['error' => $e->getMessage()]);
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
            Log::error('ProductImage: Reorder failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
