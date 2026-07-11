<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
auth()->login($user);

$livewire = new App\Livewire\Settings\InvoiceTemplates();
$livewire->slug = 'tally';
$livewire->fontChoice = 'Helvetica';
$livewire->colorPrimary = '#000000';
$livewire->colorSecondary = '#000000';
$livewire->showFields = ['shipping_address' => true, 'hsn' => true, 'tax' => true, 'discount' => true];
try {
    $html = $livewire->getPreviewHtml();
    echo "RENDER OK\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
