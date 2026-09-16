<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HelpCategory;
use App\Models\HelpFaq;
use App\Models\Setting;

class HelpController extends Controller
{
    public function index()
    {
        $categories = HelpCategory::aktif()->terurut()->get();

        $faqGroups = collect();
        foreach ($categories as $category) {
            $faqs = $category->faqs()->aktif()->terurut()->get();
            if ($faqs->isNotEmpty()) {
                $faqGroups->push(['category' => $category, 'faqs' => $faqs]);
            }
        }

        $orphanFaqs = HelpFaq::aktif()->terurut()->whereNull('help_category_id')->get();
        if ($orphanFaqs->isNotEmpty()) {
            $faqGroups->push(['category' => null, 'faqs' => $orphanFaqs]);
        }

        return view('customer.help.index', [
            'hero' => [
                'title' => Setting::get(Setting::HELP_HERO_TITLE, 'How can we help?'),
                'subtitle' => Setting::get(Setting::HELP_HERO_SUBTITLE, 'Search our help center or browse popular topics below.'),
                'search' => Setting::get(Setting::HELP_HERO_SEARCH, 'Search help topics...'),
            ],
            'categories' => $categories,
            'faqGroups' => $faqGroups,
            'emailSupport' => Setting::get(Setting::EMAIL_SUPPORT, 'support@raliva.com'),
            'whatsappSupport' => Setting::get(Setting::WHATSAPP_SUPPORT, ''),
            'whatsappHours' => Setting::get(Setting::HELP_WHATSAPP_HOURS, 'Mon–Fri, 09.00–17.00 WIB'),
        ]);
    }
}
