<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Purchase\Models\PurchaseSetting;
use Illuminate\Http\Request;

class SettingsController extends AdminController
{
    public function index()
    {
        $settings = [
            // General
            'vendor_prefix' => PurchaseSetting::getValue('vendor_prefix', 'VND-'),
            'pr_prefix' => PurchaseSetting::getValue('pr_prefix', 'PR-'),
            'po_prefix' => PurchaseSetting::getValue('po_prefix', 'PO-'),
            'pr_approval_required' => PurchaseSetting::getValue('pr_approval_required', '1'),
            'default_payment_terms' => PurchaseSetting::getValue('default_payment_terms', 'Net 30'),
            'default_tax_percent' => PurchaseSetting::getValue('default_tax_percent', '18'),
            
            // PDF Settings
            'pdf_primary_color' => PurchaseSetting::getValue('pdf_primary_color', '#1e40af'),
            'pdf_secondary_color' => PurchaseSetting::getValue('pdf_secondary_color', '#f3f4f6'),
            'pdf_show_logo' => PurchaseSetting::getValue('pdf_show_logo', '1'),
            'pdf_show_gst' => PurchaseSetting::getValue('pdf_show_gst', '1'),
            'pdf_show_terms' => PurchaseSetting::getValue('pdf_show_terms', '1'),
            'pdf_show_signature' => PurchaseSetting::getValue('pdf_show_signature', '1'),
            'pdf_show_notes' => PurchaseSetting::getValue('pdf_show_notes', '1'),
            'pdf_compact_mode' => PurchaseSetting::getValue('pdf_compact_mode', '1'),
            'pdf_font_size' => PurchaseSetting::getValue('pdf_font_size', '9'),
            
            // Default Terms
            'po_terms' => PurchaseSetting::getValue('po_terms', "1. Goods once sold will not be taken back.\n2. Delivery within specified time.\n3. Payment as per agreed terms."),
        ];
        
        return view('purchase::settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $fields = [
            'vendor_prefix', 'pr_prefix', 'po_prefix', 'pr_approval_required',
            'default_payment_terms', 'default_tax_percent',
            'pdf_primary_color', 'pdf_secondary_color', 'pdf_show_logo',
            'pdf_show_gst', 'pdf_show_terms', 'pdf_show_signature', 
            'pdf_show_notes', 'pdf_compact_mode', 'pdf_font_size', 'po_terms'
        ];
        
        foreach ($fields as $field) {
            if ($request->has($field)) {
                PurchaseSetting::setValue($field, $request->input($field));
            }
        }
        
        // Handle checkboxes (off = not sent)
        $checkboxes = ['pdf_show_logo', 'pdf_show_gst', 'pdf_show_terms', 'pdf_show_signature', 'pdf_show_notes', 'pdf_compact_mode', 'pr_approval_required'];
        foreach ($checkboxes as $checkbox) {
            PurchaseSetting::setValue($checkbox, $request->has($checkbox) ? '1' : '0');
        }
        
        return redirect()->route('admin.purchase.settings')->with('success', 'Settings updated successfully!');
    }
}
