<?php
declare(strict_types=1);

namespace CajunWebPro\Landscaping;

use Core\Modules\ModuleContext;

final class Provider
{
    public function register(ModuleContext $ctx): void
    {
        $ns = $ctx->views(dirname(__DIR__) . '/views');
        $ctl = fn () => new LandscapingController($ctx, $ns);

        $ctx->route('GET', '/', fn ($request) => $ctl()->home($request));
        foreach (['services', 'projects', 'maintenance-plans', 'service-areas', 'about', 'contact', 'request-estimate'] as $page) {
            $ctx->route('GET', '/' . $page, fn ($request) => $ctl()->standardPage($request));
        }

        $ctx->navigationMenu([
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Services', 'url' => '/services', 'children' => [
                ['label' => 'Lawn Maintenance', 'url' => '/services#lawn-maintenance'],
                ['label' => 'Landscape Installation', 'url' => '/services#landscape-installation'],
                ['label' => 'Beds & Mulch', 'url' => '/services#beds-and-mulch'],
                ['label' => 'Property Cleanup', 'url' => '/services#property-cleanup'],
            ]],
            ['label' => 'Projects', 'url' => '/projects'],
            ['label' => 'Maintenance Plans', 'url' => '/maintenance-plans'],
            ['label' => 'Service Areas', 'url' => '/service-areas'],
            ['label' => 'Contact', 'url' => '/contact'],
        ]);
        $ctx->navigationMenu([
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Projects', 'url' => '/projects'],
            ['label' => 'Maintenance Plans', 'url' => '/maintenance-plans'],
            ['label' => 'Service Areas', 'url' => '/service-areas'],
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Contact', 'url' => '/contact'],
            ['label' => 'Request an Estimate', 'url' => '/request-estimate'],
        ], 'footer', 'Footer navigation');

        $ctx->brandAssets(['logo' => 'assets/images/landscaping-mark.png']);
    }
}
