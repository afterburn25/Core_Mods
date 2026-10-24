<?php
declare(strict_types=1);

namespace CajunWebPro\Landscaping;

use Core\Http\Request;
use Core\Http\Response;
use Core\Modules\ModuleContext;
use Core\Settings\SettingsRepository;
use Core\View\View;

final class LandscapingController
{
    public function __construct(private ModuleContext $ctx, private string $ns) {}

    public function home(Request $request): Response
    {
        return $this->page($request, 'home', [], 'Home', 'Professional lawn care and landscape services.');
    }

    public function standardPage(Request $request): Response
    {
        $slug = trim((string) ($request->path ?? ''), '/');
        $pages = [
            'services' => ['Services', 'Lawn maintenance, landscape installation, beds, mulch, trimming, and cleanup.'],
            'projects' => ['Projects', 'Explore completed lawn and landscape transformations.'],
            'maintenance-plans' => ['Maintenance Plans', 'Consistent property care built around your yard and schedule.'],
            'service-areas' => ['Service Areas', 'See where our lawn and landscaping team works.'],
            'about' => ['About', 'Learn about the team responsible for caring for your property.'],
            'contact' => ['Contact', 'Talk with our lawn and landscaping team.'],
            'request-estimate' => ['Request an Estimate', 'Tell us what your property needs.'],
        ];
        if (!isset($pages[$slug])) return Response::notFound();
        return $this->page($request, 'standard-page', ['slug' => $slug], $pages[$slug][0], $pages[$slug][1]);
    }

    private function page(Request $request, string $view, array $data, string $title, string $description): Response
    {
        $settings = $this->ctx->service(SettingsRepository::class);
        $theme = (string) $settings->get('theme.active', 'base');
        $supported = ['landscaping-bold-outdoors', 'landscaping-estate-green', 'landscaping-neighborhood-lawn', 'landscaping-earth-stone'];
        $theme = in_array($theme, $supported, true) ? $theme : 'landscaping-bold-outdoors';
        $shared = [
            'basePath' => $request->basePath,
            'assetBase' => $this->ctx->assetBase($request->basePath),
            'siteName' => (string) $settings->get('site.name', 'Landscaping Co.'),
            'tagline' => (string) $settings->get('site.tagline', 'Beautiful yards. Brighter days.'),
            'page' => ['title' => $title, 'meta_description' => $description],
            'navigation' => $this->ctx->navigationTree(),
            'footerNavigation' => $this->ctx->navigationTree('footer'),
            'brandLogoUrl' => $this->ctx->brandAssetUrl($request, 'logo', 256),
            'copyrightNotice' => $this->ctx->copyrightNotice((string) $settings->get('site.name', 'Landscaping Co.')),
            'headerSocialLinks' => $this->ctx->socialLinks('header'),
            'footerSocialLinks' => $this->ctx->socialLinks('footer'),
            'activeTheme' => $theme,
        ];
        $content = $this->ctx->service(View::class)->render($this->ns . ':' . $view, $shared + $data);
        return Response::html($this->ctx->service(View::class)->render($this->ns . ':layout', $shared + ['content' => $content]));
    }
}
