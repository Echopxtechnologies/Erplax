-- =====================================================
-- Purchase Module Dummy Data
-- Run after migrations: php artisan migrate
-- =====================================================

-- Note: Assumes taxes table exists with id 1-4 (from Inventory module)
-- If not, create taxes first:
-- INSERT INTO taxes (id, name, rate, is_active, created_at, updated_at) VALUES
-- (1, 'CGST 9%', 9.00, 1, NOW(), NOW()),
-- (2, 'SGST 9%', 9.00, 1, NOW(), NOW()),
-- (3, 'CGST 6%', 6.00, 1, NOW(), NOW()),
-- (4, 'SGST 6%', 6.00, 1, NOW(), NOW()),
-- (5, 'IGST 18%', 18.00, 1, NOW(), NOW()),
-- (6, 'IGST 12%', 12.00, 1, NOW(), NOW());

-- =====================================================
-- 1. VENDORS
-- =====================================================
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `email`, `phone`, `mobile`, `contact_person`, `gst_number`, `pan_number`, `billing_address`, `billing_city`, `billing_state`, `billing_pincode`, `billing_country`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_pincode`, `shipping_country`, `payment_terms`, `credit_limit`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'VND-0001', 'ABC Electronics Pvt Ltd', 'sales@abcelectronics.com', '080-12345678', '9876543210', 'Rajesh Kumar', '29AABCU9603R1ZM', 'AABCU9603R', '123 Industrial Area, Phase 1', 'Bangalore', 'Karnataka', '560058', 'India', '123 Industrial Area, Phase 1', 'Bangalore', 'Karnataka', '560058', 'India', 'Net 30', 500000.00, 'ACTIVE', 'Preferred vendor for electronics components', NOW(), NOW()),
(2, 'VND-0002', 'XYZ Hardware Supplies', 'orders@xyzhardware.in', '080-98765432', '9988776655', 'Suresh Reddy', '29AADCX1234M1ZP', 'AADCX1234M', '456 Commercial Street', 'Chennai', 'Tamil Nadu', '600001', 'India', '456 Commercial Street', 'Chennai', 'Tamil Nadu', '600001', 'India', 'Net 15', 250000.00, 'ACTIVE', 'Hardware and tools supplier', NOW(), NOW()),
(3, 'VND-0003', 'Global Tech Solutions', 'info@globaltech.com', '022-44556677', '9123456789', 'Amit Patel', '27AABCG5678N1ZQ', 'AABCG5678N', '789 IT Park, Sector 5', 'Mumbai', 'Maharashtra', '400001', 'India', '789 IT Park, Sector 5', 'Mumbai', 'Maharashtra', '400001', 'India', 'Net 45', 1000000.00, 'ACTIVE', 'IT equipment and software vendor', NOW(), NOW()),
(4, 'VND-0004', 'Office Essentials India', 'sales@officeessentials.in', '011-22334455', '9876123456', 'Priya Sharma', '07AABCO9012P1ZR', 'AABCO9012P', '321 Nehru Place', 'New Delhi', 'Delhi', '110019', 'India', '321 Nehru Place', 'New Delhi', 'Delhi', '110019', 'India', 'Immediate', 100000.00, 'ACTIVE', 'Office supplies and stationery', NOW(), NOW()),
(5, 'VND-0005', 'Premium Packaging Co', 'contact@premiumpack.com', '080-55667788', '9654321098', 'Mohammed Ali', '29AABCP3456Q1ZS', 'AABCP3456Q', '654 Peenya Industrial Area', 'Bangalore', 'Karnataka', '560058', 'India', '654 Peenya Industrial Area', 'Bangalore', 'Karnataka', '560058', 'India', 'Net 30', 200000.00, 'INACTIVE', 'Packaging materials supplier', NOW(), NOW());

-- =====================================================
-- 2. PURCHASE REQUESTS
-- =====================================================
INSERT INTO `purchase_requests` (`id`, `pr_number`, `request_date`, `required_date`, `priority`, `department`, `requested_by`, `status`, `notes`, `approved_by`, `approved_at`, `rejected_reason`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PR-202512-0001', '2025-12-01', '2025-12-15', 'HIGH', 'IT Department', 'John Doe', 'APPROVED', 'Urgent requirement for new workstations', 1, '2025-12-02 10:30:00', NULL, 1, NOW(), NOW()),
(2, 'PR-202512-0002', '2025-12-05', '2025-12-20', 'MEDIUM', 'Operations', 'Jane Smith', 'CONVERTED', 'Monthly office supplies', 1, '2025-12-06 14:00:00', NULL, 1, NOW(), NOW()),
(3, 'PR-202512-0003', '2025-12-10', '2025-12-25', 'LOW', 'Admin', 'Mike Wilson', 'PENDING', 'Furniture for new office', NULL, NULL, NULL, 1, NOW(), NOW()),
(4, 'PR-202512-0004', '2025-12-12', '2025-12-30', 'HIGH', 'Production', 'Sarah Brown', 'DRAFT', 'Raw materials for Q1 production', NULL, NULL, NULL, 1, NOW(), NOW());

-- =====================================================
-- 3. PURCHASE REQUEST ITEMS
-- =====================================================
INSERT INTO `purchase_request_items` (`id`, `purchase_request_id`, `product_id`, `variation_id`, `unit_id`, `qty`, `ordered_qty`, `estimated_price`, `specifications`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, 5.000, 5.000, 45000.00, 'Dell OptiPlex 7090 Desktop', NOW(), NOW()),
(2, 1, 2, NULL, 1, 5.000, 5.000, 8500.00, '24 inch LED Monitor', NOW(), NOW()),
(3, 2, 3, NULL, 1, 100.000, 100.000, 25.00, 'A4 Paper Ream 500 sheets', NOW(), NOW()),
(4, 2, NULL, NULL, 1, 50.000, 0.000, 150.00, 'Black Ink Cartridge', NOW(), NOW()),
(5, 3, NULL, NULL, 1, 10.000, 0.000, 8500.00, 'Office Chair Ergonomic', NOW(), NOW()),
(6, 4, 1, NULL, 1, 20.000, 0.000, 1500.00, 'Steel Plates 4mm', NOW(), NOW());

-- =====================================================
-- 4. PURCHASE ORDERS
-- =====================================================
INSERT INTO `purchase_orders` (`id`, `po_number`, `vendor_id`, `purchase_request_id`, `po_date`, `expected_date`, `delivery_date`, `status`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_pincode`, `subtotal`, `tax_amount`, `discount_amount`, `shipping_charge`, `total_amount`, `payment_terms`, `terms_conditions`, `notes`, `sent_at`, `confirmed_at`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PO-202512-0001', 1, 1, '2025-12-02', '2025-12-15', NULL, 'CONFIRMED', '177, 19th Main Rd, Rajajinagar', 'Bangalore', 'Karnataka', '560021', 267500.00, 48150.00, 0.00, 500.00, 316150.00, 'Net 30', '1. Goods once sold will not be taken back.\n2. Delivery within specified time.\n3. Payment as per agreed terms.', 'First PO for IT equipment', '2025-12-02 11:00:00', '2025-12-03 09:30:00', 1, NOW(), NOW()),
(2, 'PO-202512-0002', 4, 2, '2025-12-06', '2025-12-20', NULL, 'SENT', '177, 19th Main Rd, Rajajinagar', 'Bangalore', 'Karnataka', '560021', 10000.00, 1800.00, 500.00, 0.00, 11300.00, 'Immediate', '1. Goods once sold will not be taken back.\n2. Delivery within specified time.', 'Office supplies order', '2025-12-06 15:30:00', NULL, 1, NOW(), NOW()),
(3, 'PO-202512-0003', 2, NULL, '2025-12-10', '2025-12-25', NULL, 'DRAFT', '177, 19th Main Rd, Rajajinagar', 'Bangalore', 'Karnataka', '560021', 75000.00, 13500.00, 0.00, 1000.00, 89500.00, 'Net 15', '1. Quality check required before acceptance.', 'Hardware tools order', NULL, NULL, 1, NOW(), NOW()),
(4, 'PO-202512-0004', 3, NULL, '2025-12-12', '2025-12-30', '2025-12-28', 'RECEIVED', '177, 19th Main Rd, Rajajinagar', 'Bangalore', 'Karnataka', '560021', 150000.00, 27000.00, 2000.00, 0.00, 175000.00, 'Net 45', 'Standard terms apply.', 'Software licenses', '2025-12-12 10:00:00', '2025-12-13 11:00:00', 1, NOW(), NOW());

-- =====================================================
-- 5. PURCHASE ORDER ITEMS
-- =====================================================
INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `purchase_request_item_id`, `product_id`, `variation_id`, `unit_id`, `qty`, `received_qty`, `rate`, `discount_percent`, `discount_amount`, `tax_percent`, `tax_amount`, `tax_1_id`, `tax_1_name`, `tax_1_rate`, `tax_1_amount`, `tax_2_id`, `tax_2_name`, `tax_2_rate`, `tax_2_amount`, `total`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 1, 5.000, 5.000, 45000.00, 0.00, 0.00, 18.00, 40500.00, 1, 'CGST 9%', 9.00, 20250.00, 2, 'SGST 9%', 9.00, 20250.00, 265500.00, 'Dell OptiPlex Desktop', NOW(), NOW()),
(2, 1, 2, 2, NULL, 1, 5.000, 5.000, 8500.00, 0.00, 0.00, 18.00, 7650.00, 1, 'CGST 9%', 9.00, 3825.00, 2, 'SGST 9%', 9.00, 3825.00, 50150.00, 'LED Monitor 24 inch', NOW(), NOW()),
(3, 2, 3, 3, NULL, 1, 100.000, 0.000, 25.00, 0.00, 0.00, 18.00, 450.00, 1, 'CGST 9%', 9.00, 225.00, 2, 'SGST 9%', 9.00, 225.00, 2950.00, 'A4 Paper Ream', NOW(), NOW()),
(4, 2, 4, NULL, NULL, 1, 50.000, 0.000, 150.00, 0.00, 0.00, 18.00, 1350.00, 1, 'CGST 9%', 9.00, 675.00, 2, 'SGST 9%', 9.00, 675.00, 8850.00, 'Ink Cartridge Black', NOW(), NOW()),
(5, 3, NULL, 1, NULL, 1, 10.000, 0.000, 7500.00, 0.00, 0.00, 18.00, 13500.00, 1, 'CGST 9%', 9.00, 6750.00, 2, 'SGST 9%', 9.00, 6750.00, 88500.00, 'Power Tools Set', NOW(), NOW()),
(6, 4, NULL, 2, NULL, 1, 10.000, 10.000, 15000.00, 0.00, 0.00, 18.00, 27000.00, 1, 'CGST 9%', 9.00, 13500.00, 2, 'SGST 9%', 9.00, 13500.00, 177000.00, 'Software License Annual', NOW(), NOW());

-- =====================================================
-- 6. GOODS RECEIPT NOTES
-- =====================================================
INSERT INTO `goods_receipt_notes` (`id`, `grn_number`, `purchase_order_id`, `vendor_id`, `grn_date`, `warehouse_id`, `rack_id`, `invoice_number`, `invoice_date`, `lr_number`, `vehicle_number`, `total_qty`, `accepted_qty`, `rejected_qty`, `notes`, `status`, `stock_updated`, `received_by`, `approved_by`, `approved_at`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'GRN-202512-0001', 1, 1, '2025-12-14', 1, NULL, 'INV-ABC-2025-1234', '2025-12-13', 'LR123456', 'KA01AB1234', 10.000, 10.000, 0.000, 'All items received in good condition', 'APPROVED', 1, 1, 1, '2025-12-14 16:00:00', 1, NOW(), NOW()),
(2, 'GRN-202512-0002', 4, 3, '2025-12-28', 1, NULL, 'INV-GT-2025-5678', '2025-12-27', 'LR789012', 'MH02CD5678', 10.000, 10.000, 0.000, 'Software licenses verified', 'APPROVED', 1, 1, 1, '2025-12-28 14:00:00', 1, NOW(), NOW());

-- =====================================================
-- 7. GOODS RECEIPT NOTE ITEMS
-- =====================================================
INSERT INTO `goods_receipt_note_items` (`id`, `goods_receipt_note_id`, `purchase_order_item_id`, `product_id`, `variation_id`, `unit_id`, `ordered_qty`, `received_qty`, `accepted_qty`, `rejected_qty`, `rate`, `discount_percent`, `tax_1_id`, `tax_1_name`, `tax_1_rate`, `tax_2_id`, `tax_2_name`, `tax_2_rate`, `rejection_reason`, `lot_no`, `batch_no`, `manufacturing_date`, `expiry_date`, `stock_movement_id`, `lot_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 1, 5.000, 5.000, 5.000, 0.000, 45000.00, 0.00, 1, 'CGST 9%', 9.00, 2, 'SGST 9%', 9.00, NULL, 'LOT-2025-001', 'BATCH-001', '2025-11-01', '2028-11-01', NULL, NULL, 'All desktops working properly', NOW(), NOW()),
(2, 1, 2, 2, NULL, 1, 5.000, 5.000, 5.000, 0.000, 8500.00, 0.00, 1, 'CGST 9%', 9.00, 2, 'SGST 9%', 9.00, NULL, 'LOT-2025-002', 'BATCH-002', '2025-10-15', '2028-10-15', NULL, NULL, 'Monitors tested OK', NOW(), NOW()),
(3, 2, 6, 2, NULL, 1, 10.000, 10.000, 10.000, 0.000, 15000.00, 0.00, 1, 'CGST 9%', 9.00, 2, 'SGST 9%', 9.00, NULL, NULL, NULL, NULL, '2026-12-31', NULL, NULL, 'License keys verified', NOW(), NOW());

-- =====================================================
-- 8. PURCHASE BILLS
-- =====================================================
INSERT INTO `purchase_bills` (`id`, `bill_number`, `vendor_id`, `grn_id`, `purchase_order_id`, `vendor_invoice_no`, `vendor_invoice_date`, `bill_date`, `due_date`, `warehouse_id`, `subtotal`, `tax_amount`, `discount_amount`, `shipping_charge`, `adjustment`, `grand_total`, `paid_amount`, `balance_due`, `status`, `payment_status`, `notes`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 'BILL-202512-0001', 1, 1, 1, 'INV-ABC-2025-1234', '2025-12-13', '2025-12-14', '2026-01-13', 1, 267500.00, 48150.00, 0.00, 500.00, 0.00, 316150.00, 100000.00, 216150.00, 'APPROVED', 'PARTIAL', 'First partial payment made', 1, 1, '2025-12-14 17:00:00', NOW(), NOW()),
(2, 'BILL-202512-0002', 3, 2, 4, 'INV-GT-2025-5678', '2025-12-27', '2025-12-28', '2026-02-11', 1, 150000.00, 27000.00, 2000.00, 0.00, 0.00, 175000.00, 175000.00, 0.00, 'APPROVED', 'PAID', 'Paid in full via NEFT', 1, 1, '2025-12-28 15:00:00', NOW(), NOW()),
(3, 'BILL-202512-0003', 2, NULL, 3, NULL, NULL, '2025-12-15', '2026-01-15', NULL, 75000.00, 13500.00, 0.00, 1000.00, 0.00, 89500.00, 0.00, 89500.00, 'DRAFT', 'UNPAID', 'Pending vendor invoice', 1, NULL, NULL, NOW(), NOW());

-- =====================================================
-- 9. PURCHASE BILL ITEMS
-- =====================================================
INSERT INTO `purchase_bill_items` (`id`, `purchase_bill_id`, `grn_item_id`, `product_id`, `variation_id`, `unit_id`, `description`, `qty`, `rate`, `tax_percent`, `tax_amount`, `discount_percent`, `discount_amount`, `tax_1_id`, `tax_1_name`, `tax_1_rate`, `tax_1_amount`, `tax_2_id`, `tax_2_name`, `tax_2_rate`, `tax_2_amount`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 1, 'Dell OptiPlex Desktop', 5.000, 45000.00, 18.00, 40500.00, 0.00, 0.00, 1, 'CGST 9%', 9.00, 20250.00, 2, 'SGST 9%', 9.00, 20250.00, 265500.00, NOW(), NOW()),
(2, 1, 2, 2, NULL, 1, 'LED Monitor 24 inch', 5.000, 8500.00, 18.00, 7650.00, 0.00, 0.00, 1, 'CGST 9%', 9.00, 3825.00, 2, 'SGST 9%', 9.00, 3825.00, 50150.00, NOW(), NOW()),
(3, 2, 3, 2, NULL, 1, 'Software License Annual', 10.000, 15000.00, 18.00, 27000.00, 0.00, 0.00, 1, 'CGST 9%', 9.00, 13500.00, 2, 'SGST 9%', 9.00, 13500.00, 177000.00, NOW(), NOW()),
(4, 3, NULL, 1, NULL, 1, 'Power Tools Set', 10.000, 7500.00, 18.00, 13500.00, 0.00, 0.00, 1, 'CGST 9%', 9.00, 6750.00, 2, 'SGST 9%', 9.00, 6750.00, 88500.00, NOW(), NOW());

-- =====================================================
-- 10. PURCHASE PAYMENTS
-- =====================================================
INSERT INTO `purchase_payments` (`id`, `purchase_bill_id`, `payment_number`, `payment_date`, `amount`, `payment_method`, `reference_number`, `bank_name`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'PAY-202512-0001', '2025-12-15', 100000.00, 'NEFT', 'NEFT123456789', 'HDFC Bank', 'First payment for IT equipment', 1, NOW(), NOW()),
(2, 2, 'PAY-202512-0002', '2025-12-28', 175000.00, 'NEFT', 'NEFT987654321', 'ICICI Bank', 'Full payment for software licenses', 1, NOW(), NOW());

-- =====================================================
-- END OF DUMMY DATA
-- =====================================================
