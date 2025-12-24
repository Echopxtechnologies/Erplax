<?php

namespace Modules\Inventory\Helpers;

/**
 * Barcode Helper - Generate and validate barcodes
 * 
 * Supports:
 * - EAN-13 (13 digits) - International standard, works with all scanners
 * - EAN-8 (8 digits) - Compact version
 * - CODE128 (alphanumeric) - For SKU-based barcodes
 * - Internal (custom prefix + sequential)
 */
class BarcodeHelper
{
    /**
     * Generate EAN-13 barcode
     * Format: PPP-MMMM-IIIII-C (Country-Manufacturer-Item-Check)
     * We use 200-299 prefix which is for internal/store use
     */
    public static function generateEAN13(?string $prefix = null): string
    {
        // Use 200-299 range for internal use (not assigned to any country)
        $prefix = $prefix ?? '200';
        
        // Generate 9 random digits after prefix (total 12 before check digit)
        $baseNumber = $prefix . str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        $baseNumber = substr($baseNumber, 0, 12);
        
        // Calculate check digit
        $checkDigit = self::calculateEAN13CheckDigit($baseNumber);
        
        return $baseNumber . $checkDigit;
    }

    /**
     * Generate EAN-8 barcode
     */
    public static function generateEAN8(?string $prefix = null): string
    {
        $prefix = $prefix ?? '20';
        
        // Generate 5 random digits after prefix (total 7 before check digit)
        $baseNumber = $prefix . str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $baseNumber = substr($baseNumber, 0, 7);
        
        // Calculate check digit
        $checkDigit = self::calculateEAN8CheckDigit($baseNumber);
        
        return $baseNumber . $checkDigit;
    }

    /**
     * Generate CODE128 barcode from SKU
     * Good for alphanumeric codes, supported by most scanners
     */
    public static function generateCode128(string $sku, ?string $prefix = null): string
    {
        $prefix = $prefix ?? 'INV';
        $clean = preg_replace('/[^A-Za-z0-9]/', '', $sku);
        return strtoupper($prefix . '-' . $clean);
    }

    /**
     * Generate internal sequential barcode
     * Format: PREFIX + YYYYMMDD + SEQUENCE
     */
    public static function generateInternal(?string $prefix = null, ?int $sequence = null): string
    {
        $prefix = $prefix ?? 'PRD';
        $date = date('ymd');
        $seq = $sequence ?? mt_rand(1000, 9999);
        return strtoupper($prefix) . $date . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique barcode checking database
     */
    public static function generateUnique(string $type = 'EAN13', ?string $prefix = null, ?string $sku = null): string
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            switch (strtoupper($type)) {
                case 'EAN13':
                    $barcode = self::generateEAN13($prefix);
                    break;
                case 'EAN8':
                    $barcode = self::generateEAN8($prefix);
                    break;
                case 'CODE128':
                    $barcode = self::generateCode128($sku ?? uniqid(), $prefix);
                    break;
                case 'INTERNAL':
                default:
                    $barcode = self::generateInternal($prefix, mt_rand(1000, 9999));
                    break;
            }
            
            $attempt++;
            
            // Check if exists in database
            $exists = self::barcodeExists($barcode);
            
        } while ($exists && $attempt < $maxAttempts);
        
        return $barcode;
    }

    /**
     * Check if barcode already exists
     */
    public static function barcodeExists(string $barcode): bool
    {
        // Check in products
        $productExists = \Modules\Inventory\Models\Product::where('barcode', $barcode)->exists();
        if ($productExists) return true;
        
        // Check in variations
        $variationExists = \Modules\Inventory\Models\ProductVariation::where('barcode', $barcode)->exists();
        if ($variationExists) return true;
        
        // Check in product units
        $unitExists = \Modules\Inventory\Models\ProductUnit::where('barcode', $barcode)->exists();
        
        return $unitExists;
    }

    /**
     * Validate EAN-13 barcode
     */
    public static function validateEAN13(string $barcode): bool
    {
        if (!preg_match('/^\d{13}$/', $barcode)) {
            return false;
        }
        
        $checkDigit = self::calculateEAN13CheckDigit(substr($barcode, 0, 12));
        return $checkDigit === $barcode[12];
    }

    /**
     * Validate EAN-8 barcode
     */
    public static function validateEAN8(string $barcode): bool
    {
        if (!preg_match('/^\d{8}$/', $barcode)) {
            return false;
        }
        
        $checkDigit = self::calculateEAN8CheckDigit(substr($barcode, 0, 7));
        return $checkDigit === $barcode[7];
    }

    /**
     * Calculate EAN-13 check digit
     */
    public static function calculateEAN13CheckDigit(string $digits): string
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$digits[$i] * (($i % 2 === 0) ? 1 : 3);
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        return (string)$checkDigit;
    }

    /**
     * Calculate EAN-8 check digit
     */
    public static function calculateEAN8CheckDigit(string $digits): string
    {
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $sum += (int)$digits[$i] * (($i % 2 === 0) ? 3 : 1);
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        return (string)$checkDigit;
    }

    /**
     * Detect barcode type from string
     */
    public static function detectType(string $barcode): ?string
    {
        if (preg_match('/^\d{13}$/', $barcode)) {
            return 'EAN13';
        }
        if (preg_match('/^\d{8}$/', $barcode)) {
            return 'EAN8';
        }
        if (preg_match('/^[A-Z0-9\-]+$/', strtoupper($barcode))) {
            return 'CODE128';
        }
        return null;
    }

    /**
     * Find product by barcode (searches products, variations, and units)
     */
    public static function findByBarcode(string $barcode): ?array
    {
        // Search in products
        $product = \Modules\Inventory\Models\Product::where('barcode', $barcode)->first();
        if ($product) {
            return [
                'type' => 'product',
                'product' => $product,
                'variation' => null,
                'unit' => null,
            ];
        }
        
        // Search in variations
        $variation = \Modules\Inventory\Models\ProductVariation::with('product')
            ->where('barcode', $barcode)
            ->first();
        if ($variation) {
            return [
                'type' => 'variation',
                'product' => $variation->product,
                'variation' => $variation,
                'unit' => null,
            ];
        }
        
        // Search in product units
        $unit = \Modules\Inventory\Models\ProductUnit::with('product')
            ->where('barcode', $barcode)
            ->first();
        if ($unit) {
            return [
                'type' => 'unit',
                'product' => $unit->product,
                'variation' => null,
                'unit' => $unit,
            ];
        }
        
        return null;
    }

    /**
     * Generate barcode for variation based on product barcode
     */
    public static function generateVariationBarcode(string $productBarcode, int $variationIndex, string $type = 'EAN13'): string
    {
        if ($type === 'EAN13' && strlen($productBarcode) === 13) {
            // For EAN-13, modify last digits before check digit
            $base = substr($productBarcode, 0, 10);
            $varNum = str_pad($variationIndex % 100, 2, '0', STR_PAD_LEFT);
            $newBase = $base . $varNum;
            $checkDigit = self::calculateEAN13CheckDigit($newBase);
            return $newBase . $checkDigit;
        }
        
        // For other types, append variation index
        return $productBarcode . '-V' . str_pad($variationIndex, 3, '0', STR_PAD_LEFT);
    }
}
