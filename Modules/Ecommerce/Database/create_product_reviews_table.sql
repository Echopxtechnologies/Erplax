-- Create product_reviews table for Ecommerce module
-- Run this SQL in phpMyAdmin

CREATE TABLE IF NOT EXISTS `product_reviews` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` bigint(20) UNSIGNED NOT NULL,
    `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
    `order_id` bigint(20) UNSIGNED DEFAULT NULL,
    `reviewer_name` varchar(100) NOT NULL,
    `reviewer_email` varchar(255) DEFAULT NULL,
    `rating` tinyint(3) UNSIGNED NOT NULL,
    `title` varchar(255) DEFAULT NULL,
    `review` text NOT NULL,
    `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
    `admin_reply` varchar(500) DEFAULT NULL,
    `replied_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `product_reviews_product_id_status_index` (`product_id`, `status`),
    KEY `product_reviews_customer_id_index` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify table was created
SELECT 'product_reviews table created successfully!' AS status;
