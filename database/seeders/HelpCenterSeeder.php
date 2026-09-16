<?php

namespace Database\Seeders;

use App\Models\HelpCategory;
use App\Models\HelpFaq;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class HelpCenterSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['icon' => 'local_shipping', 'judul' => 'Shipping', 'subjudul' => 'Track & delivery'],
            ['icon' => 'assignment_return', 'judul' => 'Returns', 'subjudul' => 'Exchanges & refunds'],
            ['icon' => 'payments', 'judul' => 'Payments', 'subjudul' => 'Methods & security'],
            ['icon' => 'support_agent', 'judul' => 'Contact', 'subjudul' => 'Talk to our team'],
        ];

        foreach ($kategori as $i => $row) {
            HelpCategory::firstOrCreate(
                ['judul' => $row['judul']],
                ['icon' => $row['icon'], 'subjudul' => $row['subjudul'], 'urutan' => $i + 1, 'is_active' => true]
            );
        }

        $faqs = [
            [
                'pertanyaan' => 'How do I track my order?',
                'kategori' => 'Shipping',
                'jawaban' => 'Go to Account → My Orders and select the order you want to follow. You will see the latest delivery status there, from preparing to delivered.',
            ],
            [
                'pertanyaan' => 'What is your return policy?',
                'kategori' => 'Returns',
                'jawaban' => 'Returns are accepted within 14 days of delivery. Items must be unworn with original tags attached. Start a return by contacting our support team with your order number.',
            ],
            [
                'pertanyaan' => 'Which payment methods do you accept?',
                'kategori' => 'Payments',
                'jawaban' => 'We accept bank transfer, major credit cards, and popular e-wallets. All payments are processed securely at checkout.',
            ],
            [
                'pertanyaan' => 'Can I change my delivery address?',
                'kategori' => 'Shipping',
                'jawaban' => 'Yes, as long as the order has not been shipped. Update your saved addresses in Account → Addresses, then contact support so we can apply it to your open order.',
            ],
            [
                'pertanyaan' => 'How do I contact a store?',
                'kategori' => 'Contact',
                'jawaban' => 'Open the store page from any product or from Featured Stores on the home page. Store contact options are available on their profile.',
            ],
        ];

        foreach ($faqs as $i => $row) {
            $cat = HelpCategory::where('judul', $row['kategori'])->first();
            HelpFaq::updateOrCreate(
                ['pertanyaan' => $row['pertanyaan']],
                ['jawaban' => $row['jawaban'], 'urutan' => $i + 1, 'is_active' => true, 'help_category_id' => $cat?->help_category_id]
            );
        }

        $defaults = [
            Setting::HELP_HERO_TITLE => 'How can we help?',
            Setting::HELP_HERO_SUBTITLE => 'Search our help center or browse popular topics below.',
            Setting::HELP_HERO_SEARCH => 'Search help topics...',
            Setting::HELP_WHATSAPP_HOURS => 'Mon–Fri, 09.00–17.00 WIB',
        ];

        foreach ($defaults as $key => $value) {
            if (Setting::get($key) === null) {
                Setting::set($key, $value);
            }
        }
    }
}
