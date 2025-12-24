<?php

namespace Modules\Website\Http\Controllers\Website;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Website\WebsiteController as BaseWebsiteController;
use Modules\Website\Models\Website\WebsiteSetting;

class WebsiteController extends BaseWebsiteController
{
    protected function getSettings()
    {
        $settings = WebsiteSetting::instance();
        $contentPath = module_path('Website', 'Resources/content/settings.json');
        $headerSettings = File::exists($contentPath) ? json_decode(File::get($contentPath), true) : [];
        
        // Get prefixes
        $sitePrefix = '/' . ($settings->site_prefix ?? 'site');
        $shopPrefix = '/' . ($settings->shop_prefix ?? 'shop');
        $siteMode = $settings->site_mode ?? 'both';
        
        if (!empty($headerSettings['menu'])) {
            $filteredMenu = [];
            foreach ($headerSettings['menu'] as $item) {
                // Skip Shop if mode is 'website_only'
                if ($siteMode === 'website_only' && strtolower($item['title']) === 'shop') {
                    continue;
                }
                
                // Apply correct prefix based on URL type
                if (stripos($item['url'], '/shop') === 0 || strtolower($item['title']) === 'shop') {
                    // Shop URLs use shop prefix
                    $item['url'] = $shopPrefix . (stripos($item['url'], '/shop') === 0 ? substr($item['url'], 5) : '');
                } else {
                    // Page URLs use site prefix
                    $item['url'] = $sitePrefix . $item['url'];
                }
                
                if (!empty($item['children'])) {
                    foreach ($item['children'] as &$child) {
                        if (stripos($child['url'], '/shop') === 0) {
                            $child['url'] = $shopPrefix . substr($child['url'], 5);
                        } else {
                            $child['url'] = $sitePrefix . $child['url'];
                        }
                    }
                }
                $filteredMenu[] = $item;
            }
            $headerSettings['menu'] = $filteredMenu;
        }
        
        // Add prefix to footer links
        if (!empty($headerSettings['footer']['columns'])) {
            foreach ($headerSettings['footer']['columns'] as &$col) {
                if (!empty($col['links'])) {
                    $filteredLinks = [];
                    foreach ($col['links'] as $link) {
                        if ($siteMode === 'website_only' && strtolower($link['text']) === 'shop') {
                            continue;
                        }
                        if (stripos($link['url'], '/shop') === 0) {
                            $link['url'] = $shopPrefix . substr($link['url'], 5);
                        } else {
                            $link['url'] = $sitePrefix . $link['url'];
                        }
                        $filteredLinks[] = $link;
                    }
                    $col['links'] = $filteredLinks;
                }
            }
        }
        
        return compact('settings', 'headerSettings');
    }

    public function home()
    {
        $data = $this->getSettings();
        
        // Redirect to shop if ecommerce only mode
        if ($data['settings']->site_mode === 'ecommerce_only') {
            return redirect()->route('website.shop');
        }
        
        $contentPath = module_path('Website', 'Resources/content/home.html');
        $data['content'] = File::exists($contentPath) ? File::get($contentPath) : '';
        
        return view('website::public.home', $data);
    }

    public function page($slug)
    {
        $data = $this->getSettings();
        
        // Redirect to shop if ecommerce only mode
        if ($data['settings']->site_mode === 'ecommerce_only') {
            return redirect()->route('website.shop');
        }
        
        $contentPath = module_path('Website', 'Resources/content/' . $slug . '.html');
        
        if (!File::exists($contentPath)) {
            abort(404);
        }
        
        $data['content'] = File::get($contentPath);
        $data['slug'] = $slug;
        
        return view('website::public.page', $data);
    }

    public function submitContact(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ]);
        
        return back()->with('success', 'Thank you for your message!');
    }
}
