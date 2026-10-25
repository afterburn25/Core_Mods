<?php
declare(strict_types=1);

namespace CajunWebPro\Catering;

use Core\Modules\ModuleContext;
use Core\Database\Connection;
use Core\Settings\SettingsRepository;

final class Provider
{
    public function register(ModuleContext $ctx): void
    {
        $ns = $ctx->views(dirname(__DIR__) . '/views');
        $ctl = fn () => new CateringController($ctx, $ns);

        // Core owns the reusable business identity and sales workspace. Seed
        // only blank profile values from the module's legacy settings; later
        // administrator edits remain authoritative.
        $settings = $ctx->service(SettingsRepository::class);
        $ctx->businessProfile([
            'name' => (string) $settings->get('site.name', 'Catering Co.'),
            'email' => (string) $settings->get('site.contact_email', ''),
            'phone' => (string) $settings->get('catering.phone', ''),
            'service_area' => (string) $settings->get('catering.service_area', ''),
        ]);
        $ctx->leadManagement(['quotes' => true, 'label' => 'Catering sales']);

        $ctx->route('GET', '/', fn ($r) => $ctl()->home($r));
        $ctx->route('GET', '/services', fn ($r) => $ctl()->services($r));
        $ctx->route('GET', '/services/{slug:weddings|corporate-events|private-celebrations|drop-off-catering}',
            fn ($r) => $ctl()->serviceDetail($r));
        $ctx->route('GET', '/menus', fn ($r) => $ctl()->menus($r));
        $ctx->route('GET', '/menus/{slug:[a-z0-9-]+}', fn ($r) => $ctl()->menuDetail($r));
        $ctx->route('GET', '/packages', fn ($r) => $ctl()->packages($r));
        $ctx->route('GET', '/packages/{slug:[a-z0-9-]+}', fn ($r) => $ctl()->packageDetail($r));
        $ctx->route('GET', '/gallery', fn ($r) => $ctl()->gallery($r));
        $ctx->route('GET', '/about', fn ($r) => $ctl()->about($r));
        $ctx->route('GET', '/service-area', fn ($r) => $ctl()->serviceArea($r));
        $ctx->route('GET', '/faq', fn ($r) => $ctl()->faq($r));
        $ctx->route('GET', '/contact', fn ($r) => $ctl()->contact($r));
        $ctx->route('POST', '/contact', fn ($r) => $ctl()->submitContact($r));
        $ctx->route('GET', '/request-a-quote', fn ($r) => $ctl()->quote($r));
        $ctx->route('POST', '/request-a-quote', fn ($r) => $ctl()->submitQuote($r));
        $ctx->route('POST', '/catering/estimate', fn ($r) => $ctl()->estimate($r));

        $ctx->adminRoute('GET', '/admin/catering/menus', fn ($r) => $ctl()->adminMenus($r));
        $ctx->adminRoute('POST', '/admin/catering/menus', fn ($r) => $ctl()->adminMenusSave($r));
        $ctx->adminRoute('GET', '/admin/catering/packages', fn ($r) => $ctl()->adminPackages($r));
        $ctx->adminRoute('POST', '/admin/catering/packages', fn ($r) => $ctl()->adminPackagesSave($r));
        $ctx->adminRoute('GET', '/admin/catering/menu-groups', fn ($r) => $ctl()->adminMenuGroups($r));
        $ctx->adminRoute('POST', '/admin/catering/menu-groups', fn ($r) => $ctl()->adminMenuGroupsSave($r));
        // Preserve old bookmarks while Core takes ownership of the shared
        // inquiry workspace.
        $ctx->adminRoute('GET', '/admin/catering/inquiries', fn ($r) => $ctl()->legacyInquiryRedirect($r));
        $ctx->adminRoute('POST', '/admin/catering/inquiries', fn ($r) => $ctl()->legacyInquiryRedirect($r));
        $ctx->adminRoute('GET', '/admin/catering/settings', fn ($r) => $ctl()->adminSettings($r));
        $ctx->adminRoute('POST', '/admin/catering/settings', fn ($r) => $ctl()->adminSettingsSave($r));

        $ctx->permission('catering.manage', 'Manage catering menus, packages, and settings');
        $ctx->adminPage('Menu details & pricing', '/admin/catering/menus', 'catering.manage', 'Catering');
        $ctx->adminPage('Package details & pricing', '/admin/catering/packages', 'catering.manage', 'Catering');
        $ctx->adminPage('Menu collections', '/admin/catering/menu-groups', 'catering.manage', 'Catering');
        $ctx->adminPage('Catering settings', '/admin/catering/settings', 'catering.manage', 'Catering');
        (new CatalogResources($ctx->service(Connection::class)))->register($ctx);

        $ctx->healthCheck('inquiries-table', 'Inquiry storage', function () use ($ctx): array {
            $db = $ctx->service(Connection::class);
            if (!$db->tableExists('catering_inquiries')) {
                return ['status' => 'error', 'detail' => 'Required catering questionnaire table is missing'];
            }
            $pending = CateringController::pendingCoreLinks($db);
            return $pending === 0
                ? ['status' => 'ok', 'detail' => 'Questionnaires linked to the Core inquiry workspace']
                : ['status' => 'warn', 'detail' => $pending . ' questionnaire(s) are waiting to link to Core'];
        });

        $ctx->navigationMenu([
            ['label' => 'Our Story', 'url' => '/about'],
            ['label' => 'Menus', 'url' => '/menus'],
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Packages', 'url' => '/packages'],
            ['label' => 'Gallery', 'url' => '/gallery'],
            ['label' => 'Contact', 'url' => '/contact'],
        ]);
        $ctx->navigationMenu([
            ['label' => 'Menus', 'url' => '/menus'],
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Packages', 'url' => '/packages'],
            ['label' => 'Gallery', 'url' => '/gallery'],
            ['label' => 'Service Area', 'url' => '/service-area'],
            ['label' => 'FAQ', 'url' => '/faq'],
            ['label' => 'Contact', 'url' => '/contact'],
            ['label' => 'Request a Quote', 'url' => '/request-a-quote'],
        ], 'footer', 'Footer navigation');
        $ctx->brandAssets([
            'logo' => 'assets/images/catering-co-logo.png',
        ]);

        // Import legacy inquiries in bounded batches through the supported
        // Core service. The local questionnaire remains intact for detailed
        // event fields and makes retries lossless.
        $ctl()->synchronizeLegacyInquiries();
    }
}
