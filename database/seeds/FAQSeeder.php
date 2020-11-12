<?php

use App\Faq;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faqs = [
            ['question' => 'Is the Web App Secure?', 'answer' => 'Web application security is the process of protecting websites and online services against different security threats that exploit vulnerabilities in an application’s code.'],
            ['question' => 'What features does the Web App have?', 'answer' => 'The web app give you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.'],
            ['question' => 'How do I access the web app from my phone?', 'answer' => 'Both mobile and desktop devices having a web browser installed give you the ability to you to access your account information, view orders, report, and contact your customers.'],
            ['question' => 'How does QuickQart differ from other platforms?', 'answer' => 'Both the mobile app and the web app gives you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.'],
        ];

        foreach ($faqs as $faq) {
            Faq::query()->updateOrCreate($faq);
        }
    }
}
