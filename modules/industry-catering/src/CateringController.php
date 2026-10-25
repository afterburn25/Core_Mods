<?php
declare(strict_types=1);

namespace CajunWebPro\Catering;

use Core\Audit\AuditLog;
use Core\Auth\AuthService;
use Core\Business\BusinessProfile;
use Core\Database\Connection;
use Core\Http\Request;
use Core\Http\Response;
use Core\Mail\SystemMail;
use Core\Leads\InquiryService;
use Core\Modules\ModuleContext;
use Core\Security\Csrf;
use Core\Security\RateLimiter;
use Core\Settings\SettingsRepository;
use Core\View\View;

final class CateringController
{
    private const DISH_IMAGES = [
        'boudin-balls.webp' => 'Boudin Balls',
        'crawfish-bread.webp' => 'Crawfish Bread',
        'cajun-shrimp-grits-bites.webp' => 'Cajun Shrimp & Grits Bites',
        'chicken-andouille-gumbo.webp' => 'Chicken & Andouille Gumbo',
        'crawfish-etouffee.webp' => 'Crawfish Étouffée',
        'crawfish-bisque.webp' => 'Crawfish Bisque',
        'shrimp-fettuccine.webp' => 'Shrimp Fettuccine',
        'cajun-jambalaya.webp' => 'Cajun Jambalaya',
        'blackened-redfish.webp' => 'Blackened Redfish',
        'bananas-foster-bread-pudding.webp' => 'Bananas Foster Bread Pudding',
    ];

    public function __construct(
        private ModuleContext $ctx,
        private string $ns,
    ) {}

    public function home(Request $request): Response
    {
        return $this->page($request, 'home', [
            'featuredItems' => $this->db()->fetchAll(
                'SELECT * FROM `' . $this->db()->table('catering_menu_items')
                . '` WHERE visible = 1 AND featured = 1 ORDER BY sort_order, name LIMIT 3'
            ),
            'packages' => $this->visiblePackages(3),
        ], 'Home', 'Thoughtful menus and polished service for memorable gatherings.');
    }

    public function services(Request $request): Response
    {
        return $this->page($request, 'services', ['services' => $this->serviceData()],
            'Catering Services', 'Explore wedding, corporate, private-event, and drop-off catering.');
    }

    public function serviceDetail(Request $request): Response
    {
        $slug = (string) ($request->attributes['slug'] ?? '');
        $service = $this->serviceData()[$slug] ?? null;
        if ($service === null) {
            return Response::notFound();
        }
        return $this->page($request, 'service-detail', ['service' => $service],
            $service['title'], $service['summary']);
    }

    public function menus(Request $request): Response
    {
        [$categories, $items] = $this->menuData();
        return $this->page($request, 'menus', ['categories' => $categories, 'items' => $items],
            'Menus', 'Browse seasonal catering menus, dietary options, and service styles.');
    }

    public function menuDetail(Request $request): Response
    {
        $slug = (string) ($request->attributes['slug'] ?? '');
        $category = $this->db()->fetch(
            'SELECT * FROM `' . $this->db()->table('catering_menu_categories') . '` WHERE slug = ? AND visible = 1',
            [$slug]
        );
        if ($category === null) {
            return Response::notFound();
        }
        $items = $this->db()->fetchAll(
            'SELECT * FROM `' . $this->db()->table('catering_menu_items')
            . '` WHERE category_id = ? AND visible = 1 ORDER BY sort_order, name',
            [(int) $category['id']]
        );
        return $this->page($request, 'menu-detail', ['category' => $category, 'items' => $items],
            (string) $category['name'], (string) ($category['description'] ?? ''));
    }

    public function packages(Request $request): Response
    {
        return $this->page($request, 'packages', ['packages' => $this->visiblePackages()],
            'Catering Packages', 'Compare flexible catering experiences and find the right starting point.');
    }

    public function packageDetail(Request $request): Response
    {
        $package = $this->db()->fetch(
            'SELECT * FROM `' . $this->db()->table('catering_packages') . '` WHERE slug = ? AND visible = 1',
            [(string) ($request->attributes['slug'] ?? '')]
        );
        if ($package === null) {
            return Response::notFound();
        }
        return $this->page($request, 'package-detail', ['package' => $package],
            (string) $package['name'], (string) ($package['description'] ?? ''));
    }

    public function gallery(Request $request): Response
    {
        return $this->page($request, 'gallery', [], 'Gallery',
            'A visual look at beautifully catered weddings, celebrations, and corporate gatherings.');
    }

    public function about(Request $request): Response
    {
        return $this->page($request, 'about', [], 'Our Story',
            'Meet the people and philosophy behind the catering experience.');
    }

    public function serviceArea(Request $request): Response
    {
        $settings = $this->settings();
        $profile = $this->ctx->service(BusinessProfile::class)->get();
        return $this->page($request, 'service-area', [
            'serviceArea' => $profile['service_area'] !== ''
                ? $profile['service_area']
                : 'Your city and surrounding communities',
            'travelNote' => $settings->get('catering.travel_note', 'Travel and delivery fees may apply outside our primary service area.'),
        ], 'Service Area', 'Delivery, pickup, venue, and travel information.');
    }

    public function faq(Request $request): Response
    {
        return $this->page($request, 'faq', ['faqs' => $this->faqData()],
            'Frequently Asked Questions', 'Answers about availability, tastings, guest counts, dietary needs, and booking.');
    }

    public function contact(Request $request, ?string $error = null, array $old = []): Response
    {
        return $this->page($request, 'contact', [
            'csrf' => $this->ctx->service(Csrf::class),
            'error' => $error,
            'sent' => $request->string('sent') === '1',
            'old' => $old,
        ], 'Contact', 'Talk with our catering team about an upcoming gathering.');
    }

    public function submitContact(Request $request): Response
    {
        if (!$this->ctx->service(Csrf::class)->validate($request)) {
            return Response::text('Invalid CSRF token.', 419);
        }
        if ($request->string('website') !== '') {
            return Response::redirect($this->ctx->url($request->basePath, '/contact', ['sent' => 1]));
        }
        if (!$this->ctx->service(RateLimiter::class)->attempt('catering-contact:' . $request->ip(), 5, 600)) {
            return $this->contact($request, 'Too many submissions. Please wait a few minutes and try again.', $request->post);
        }
        $data = $this->contactInput($request);
        $errors = $this->contactErrors($data);
        if ($errors !== []) {
            return $this->contact($request, implode(' ', $errors), $data);
        }
        $this->saveInquiry($request, 'contact', $data);
        return Response::redirect($this->ctx->url($request->basePath, '/contact', ['sent' => 1]));
    }

    public function quote(Request $request, ?string $error = null, array $old = []): Response
    {
        [$categories, $items, $packages] = $this->plannerCatalog();
        $prefill = $request->string('package');
        if ($old === [] && $prefill !== '') {
            foreach ($packages as $package) {
                if ($prefill === $package['name'] || $prefill === $package['slug']) $old['package_id'] = (string) $package['id'];
            }
        }
        if ($request->method === 'GET') {
            $old['event_type'] ??= $request->string('event_type');
            $old['menu_interest'] ??= $request->string('menu_interest');
        }
        return $this->page($request, 'quote', [
            'csrf' => $this->ctx->service(Csrf::class), 'error' => $error,
            'sent' => $request->string('sent') === '1', 'old' => $old,
            'categories' => $categories, 'items' => $items, 'packages' => $packages,
            'dietaryLabels' => MenuPlanner::DIETARY, 'allergenLabels' => MenuPlanner::ALLERGENS,
            'minimumGuests' => max(1, (int) $this->settings()->get('catering.minimum_guests', 20)),
            'initialPlan' => isset($old['guest_count']) ? (new MenuPlanner())->calculate($old, $items, $packages, max(1, (int) $this->settings()->get('catering.minimum_guests', 20))) : null,
            'estimateUrl' => $this->ctx->url($request->basePath, '/catering/estimate'),
        ], 'Plan Your Menu', 'Build your menu and request a personalized catering proposal.', true);
    }

    public function estimate(Request $request): Response
    {
        if (!$this->ctx->service(Csrf::class)->validate($request)) return Response::json(['errors' => ['Your session expired. Refresh the page.']], 419)->header('Cache-Control', 'private, no-store');
        if (!$this->ctx->service(RateLimiter::class)->attempt('catering-estimate:' . $request->ip(), 90, 60)) {
            return Response::json(['errors' => ['Please wait a moment before updating your estimate.']], 429)->header('Cache-Control', 'private, no-store');
        }
        [, $items, $packages] = $this->plannerCatalog();
        $plan = (new MenuPlanner())->calculate($request->post, $items, $packages,
            max(1, (int) $this->settings()->get('catering.minimum_guests', 20)));
        $response = Response::json($plan, $plan['errors'] === [] ? 200 : 422);
        $response->header('Cache-Control', 'private, no-store');
        return $response;
    }

    public function submitQuote(Request $request): Response
    {
        if (!$this->ctx->service(Csrf::class)->validate($request)) {
            return Response::text('Invalid CSRF token.', 419);
        }
        if ($request->string('website') !== '') {
            return Response::redirect($this->ctx->url($request->basePath, '/request-a-quote', ['sent' => 1]));
        }
        if ($request->string('preview_plan') === '1') {
            if (!$this->ctx->service(RateLimiter::class)->attempt('catering-estimate:' . $request->ip(), 90, 60)) {
                return $this->quote($request, 'Please wait a moment before calculating again.', $request->post);
            }
            return $this->quote($request, null, $request->post);
        }
        if (!$this->ctx->service(RateLimiter::class)->attempt('catering-quote:' . $request->ip(), 4, 900)) {
            return $this->quote($request, 'Too many submissions. Please wait and try again.', $request->post);
        }
        $data = $this->quoteInput($request);
        $errors = $this->quoteErrors($data);
        [, $items, $packages] = $this->plannerCatalog();
        $plan = (new MenuPlanner())->calculate($request->post, $items, $packages,
            max(1, (int) $this->settings()->get('catering.minimum_guests', 20)));
        $errors = array_merge($errors, $plan['errors']);
        if ($request->string('acknowledged') !== '1') $errors[] = 'Please acknowledge that this is a quote request, not a booking.';
        $data['menu_items'] = is_array($request->input('menu_items')) ? $request->input('menu_items') : [];
        $data['package_id'] = $request->string('package_id');
        $data['acknowledged'] = $request->string('acknowledged');
        $data['menu_plan'] = json_encode($plan, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        if ($errors !== []) {
            return $this->quote($request, implode(' ', $errors), $data);
        }
        $this->saveInquiry($request, 'quote', $data);
        return Response::redirect($this->ctx->url($request->basePath, '/request-a-quote', ['sent' => 1]));
    }

    public function adminMenus(Request $request, ?string $error = null, array $old = []): Response
    {
        if ($forbidden = $this->guard('catering.manage')) return $forbidden;
        [$categories, $items] = $this->menuData(false);
        $editing = null;
        $editId = (int) ($old['id'] ?? $request->string('edit_item'));
        foreach ($items as $item) if ((int) $item['id'] === $editId) $editing = $item;
        return $this->adminPage($request, 'admin-menus', [
            'categories' => $categories, 'items' => $items, 'dishImages' => self::DISH_IMAGES,
            'saved' => $request->string('saved') === '1', 'error' => $error,
            'old' => $old, 'editing' => $editing,
            'dietaryLabels' => MenuPlanner::DIETARY, 'allergenLabels' => MenuPlanner::ALLERGENS,
            'pricingLabels' => MenuPlanner::PRICING,
        ], 'Catering Menus');
    }

    public function adminMenusSave(Request $request): Response
    {
        if ($forbidden = $this->guard('catering.manage')) return $forbidden;
        if (!$this->ctx->service(Csrf::class)->validate($request)) return Response::text('Invalid CSRF token.', 419);
        $db = $this->db();
        $action = $request->string('action');
        try {
            if ($action === 'add_category') {
                $name = $request->string('name');
                if ($name === '' || mb_strlen($name) > 190) throw new \InvalidArgumentException('Category name is required and must be at most 190 characters.');
                $db->transaction(function () use ($db, $request, $name): void {
                    $id = $db->insert('catering_menu_categories', [
                        'slug' => $this->uniqueSlug('catering_menu_categories', $name), 'name' => $name,
                        'description' => mb_substr($request->string('description'), 0, 2000),
                        'sort_order' => (int) $request->string('sort_order', '0'), 'visible' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $this->audit('catering.category.created', (int) $id);
                });
            } elseif (in_array($action, ['add_item', 'update_item'], true)) {
                $name = $request->string('name');
                if ($name === '' || mb_strlen($name) > 190) throw new \InvalidArgumentException('Dish name is required and must be at most 190 characters.');
                $categoryId = (int) $request->string('category_id');
                if (!(bool) $db->fetchColumn('SELECT id FROM `' . $db->table('catering_menu_categories') . '` WHERE id = ?', [$categoryId])) {
                    throw new \InvalidArgumentException('Choose an existing menu category.');
                }
                $dietary = $this->validatedTags($request->input('dietary_tags', []), MenuPlanner::DIETARY);
                $allergens = $this->validatedTags($request->input('allergens', []), MenuPlanner::ALLERGENS);
                $image = $request->string('image');
                if ($image !== '' && !array_key_exists($image, self::DISH_IMAGES)) throw new \InvalidArgumentException('Choose an available dish image.');
                $data = $this->pricingInput($request) + [
                    'category_id' => $categoryId, 'name' => $name,
                    'description' => mb_substr($request->string('description'), 0, 3000),
                    'price_unit' => mb_substr($request->string('price_unit'), 0, 80),
                    'dietary' => mb_substr($request->string('dietary'), 0, 255),
                    'dietary_tags' => json_encode($dietary, JSON_THROW_ON_ERROR),
                    'allergens' => json_encode($allergens, JSON_THROW_ON_ERROR),
                    'allergen_notes' => mb_substr($request->string('allergen_notes'), 0, 1000),
                    'image' => $image, 'featured' => $request->string('featured') === '1' ? 1 : 0,
                    'sort_order' => (int) $request->string('sort_order', '0'),
                ];
                $db->transaction(function () use ($db, $request, $action, $name, $data): void {
                    if ($action === 'update_item') {
                        $id = $this->existingId($request, 'catering_menu_items');
                        $db->update('catering_menu_items', $data, 'id = ?', [$id]);
                    } else {
                        $id = (int) $db->insert('catering_menu_items', $data + [
                            'slug' => $this->uniqueSlug('catering_menu_items', $name), 'visible' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    $this->audit('catering.menu.' . $action, $id);
                });
            } elseif (in_array($action, ['toggle_item', 'delete_item'], true)) {
                $db->transaction(function () use ($db, $request, $action): void {
                    $id = $this->existingId($request, 'catering_menu_items');
                    if ($action === 'toggle_item') {
                        $db->run('UPDATE `' . $db->table('catering_menu_items') . '` SET visible = CASE WHEN visible = 1 THEN 0 ELSE 1 END WHERE id = ?', [$id]);
                    } else {
                        if ($request->string('confirm_delete') !== '1') throw new \InvalidArgumentException('Confirm dish deletion.');
                        $db->run('DELETE FROM `' . $db->table('catering_menu_items') . '` WHERE id = ?', [$id]);
                    }
                    $this->audit('catering.menu.' . $action, $id);
                });
            } else throw new \InvalidArgumentException('Unknown menu action.');
        } catch (\InvalidArgumentException $e) {
            return $this->adminMenus($request, $e->getMessage(), $request->post);
        }
        return Response::redirect($this->ctx->url($request->basePath, '/admin/catering/menus', ['saved' => 1]));
    }

    public function adminPackages(Request $request, ?string $error = null, array $old = []): Response
    {
        if ($forbidden = $this->guard('catering.manage')) return $forbidden;
        $packages = $this->db()->fetchAll('SELECT * FROM `' . $this->db()->table('catering_packages') . '` ORDER BY sort_order, name');
        [$categories] = $this->menuData(false);
        $editing = null;
        $editId = (int) ($old['id'] ?? $request->string('edit_package'));
        foreach ($packages as $package) if ((int) $package['id'] === $editId) $editing = $package;
        return $this->adminPage($request, 'admin-packages', [
            'packages' => $packages, 'categories' => $categories,
            'saved' => $request->string('saved') === '1', 'error' => $error,
            'old' => $old, 'editing' => $editing, 'pricingLabels' => MenuPlanner::PRICING,
        ], 'Catering Packages');
    }

    public function adminPackagesSave(Request $request): Response
    {
        if ($forbidden = $this->guard('catering.manage')) return $forbidden;
        if (!$this->ctx->service(Csrf::class)->validate($request)) return Response::text('Invalid CSRF token.', 419);
        $db = $this->db();
        $action = $request->string('action');
        try {
            if (in_array($action, ['add_package', 'update_package'], true)) {
                $name = $request->string('name');
                if ($name === '' || mb_strlen($name) > 190) throw new \InvalidArgumentException('Package name is required and must be at most 190 characters.');
                $min = $this->boundedInt($request->string('guest_min'), 1, 10000, 'Minimum guests');
                $max = $request->string('guest_max') === '' ? null : $this->boundedInt($request->string('guest_max'), $min, 10000, 'Maximum guests');
                $data = $this->pricingInput($request) + [
                    'name' => $name, 'eyebrow' => mb_substr($request->string('eyebrow'), 0, 120),
                    'description' => mb_substr($request->string('description'), 0, 3000),
                    'price_note' => mb_substr($request->string('price_note'), 0, 120),
                    'guest_min' => $min, 'guest_max' => $max,
                    'inclusions' => mb_substr($request->string('inclusions'), 0, 5000),
                    'choice_rules' => json_encode($this->packageRules($request), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                    'featured' => $request->string('featured') === '1' ? 1 : 0,
                    'sort_order' => (int) $request->string('sort_order', '0'),
                ];
                $db->transaction(function () use ($db, $request, $action, $name, $data): void {
                    if ($action === 'update_package') {
                        $id = $this->existingId($request, 'catering_packages');
                        $db->update('catering_packages', $data, 'id = ?', [$id]);
                    } else {
                        $id = (int) $db->insert('catering_packages', $data + [
                            'slug' => $this->uniqueSlug('catering_packages', $name), 'visible' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    $this->audit('catering.package.' . $action, $id);
                });
            } elseif (in_array($action, ['toggle_package', 'delete_package'], true)) {
                $db->transaction(function () use ($db, $request, $action): void {
                    $id = $this->existingId($request, 'catering_packages');
                    if ($action === 'toggle_package') {
                        $db->run('UPDATE `' . $db->table('catering_packages') . '` SET visible = CASE WHEN visible = 1 THEN 0 ELSE 1 END WHERE id = ?', [$id]);
                    } else {
                        if ($request->string('confirm_delete') !== '1') throw new \InvalidArgumentException('Confirm package deletion.');
                        $db->run('DELETE FROM `' . $db->table('catering_packages') . '` WHERE id = ?', [$id]);
                    }
                    $this->audit('catering.package.' . $action, $id);
                });
            } else throw new \InvalidArgumentException('Unknown package action.');
        } catch (\InvalidArgumentException $e) {
            return $this->adminPackages($request, $e->getMessage(), $request->post);
        }
        return Response::redirect($this->ctx->url($request->basePath, '/admin/catering/packages', ['saved' => 1]));
    }

    public function legacyInquiryRedirect(Request $request): Response
    {
        if ($forbidden = $this->guard('inquiries.manage')) return $forbidden;
        return Response::redirect($request->routeUrl('/admin/inquiries'));
    }

    public function adminSettings(Request $request): Response
    {
        if ($forbidden = $this->guard('catering.manage')) {
            return $forbidden;
        }
        $settings = $this->settings();
        return $this->adminPage($request, 'admin-settings', [
            'values' => [
                'travel_note' => $settings->get('catering.travel_note', 'Travel and delivery fees may apply outside our primary service area.'),
                'minimum_guests' => $settings->get('catering.minimum_guests', '20'),
                'lead_time' => $settings->get('catering.lead_time', '14'),
                'quote_expiry' => $settings->get('catering.quote_expiry', '14'),
            ],
            'saved' => $request->string('saved') === '1',
        ], 'Catering Settings');
    }

    public function adminSettingsSave(Request $request): Response
    {
        if ($forbidden = $this->guard('catering.manage')) {
            return $forbidden;
        }
        $settings = $this->settings();
        $settings->set('catering.travel_note', mb_substr($request->string('travel_note'), 0, 1000));
        $settings->set('catering.minimum_guests', max(1, (int) $request->string('minimum_guests', '20')));
        $settings->set('catering.lead_time', max(0, (int) $request->string('lead_time', '14')));
        $settings->set('catering.quote_expiry', max(1, (int) $request->string('quote_expiry', '14')));
        return Response::redirect($request->routeUrl('/admin/catering/settings', ['saved' => 1]));
    }

    private function page(
        Request $request,
        string $template,
        array $data,
        string $title,
        string $description,
        bool $noIndex = false,
    ): Response {
        $view = $this->ctx->service(View::class);
        $settings = $this->settings();
        $profile = $this->ctx->service(BusinessProfile::class)->get();
        $siteName = $profile['name'] !== '' ? $profile['name'] : (string) $settings->get('site.name', 'Catering Co.');
        $shared = [
            'basePath' => $this->ctx->routeBase($request->basePath),
            'assetBase' => $this->ctx->assetBase($request->basePath),
            'currentPath' => rtrim($request->path, '/') ?: '/',
            'siteName' => $siteName,
            'moduleVersion' => (string) (json_decode((string) file_get_contents(dirname(__DIR__) . '/addon.json'), true)['version'] ?? 'development'),
            'tagline' => (string) $settings->get('site.tagline', 'Gather beautifully.'),
            'phone' => $profile['phone'] !== '' ? $profile['phone'] : '(555) 123-4567',
            'contactEmail' => $profile['email'] !== '' ? $profile['email'] : (string) $settings->get('site.contact_email', 'hello@example.com'),
            'navigation' => $this->navigation(),
            'footerNavigation' => $this->ctx->navigationTree('footer'),
            'brandLogoUrl' => $this->ctx->brandAssetUrl($request, 'logo', 480),
            'faviconUrl' => $this->ctx->brandAssetUrl($request, 'favicon'),
            'copyrightNotice' => $this->ctx->copyrightNotice($siteName),
            'headerSocialLinks' => $this->ctx->socialLinks('header'),
            'footerSocialLinks' => $this->ctx->socialLinks('footer'),
            'dietaryLabels' => MenuPlanner::DIETARY,
            'allergenLabels' => MenuPlanner::ALLERGENS,
            'page' => [
                'title' => $title,
                'meta_title' => $title,
                'meta_description' => $description,
                'noindex' => $noIndex,
            ],
        ];
        foreach ($shared as $key => $value) {
            $view->share($key, $value);
        }
        $body = $view->render($this->ns . ':' . $template, array_merge($shared, $data));
        return Response::html($view->render($this->ns . ':layout', array_merge($shared, ['content' => $body])));
    }

    /** @return list<array> */
    private function navigation(): array
    {
        return $this->ctx->navigationTree();
    }

    private function adminPage(Request $request, string $template, array $data, string $title): Response
    {
        return $this->ctx->adminView($request, $this->ns . ':' . $template, $data, $title, 'catering.manage');
    }

    private function saveInquiry(Request $request, string $kind, array $data): void
    {
        $record = [
            'kind' => $kind,
            'status' => 'new',
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'company' => $data['company'] ?? '',
            'event_type' => $data['event_type'] ?? '',
            'event_date' => $data['event_date'] ?? '',
            'event_time' => $data['event_time'] ?? '',
            'guest_count' => ($data['guest_count'] ?? '') !== '' ? (int) $data['guest_count'] : null,
            'venue' => $data['venue'] ?? '',
            'service_style' => $data['service_style'] ?? '',
            'menu_interest' => $data['menu_interest'] ?? '',
            'budget' => $data['budget'] ?? '',
            'dietary' => $data['dietary'] ?? '',
            'message' => $data['message'] ?? '',
            'source_ip' => $request->ip(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
            'menu_plan' => $data['menu_plan'] ?? null,
        ];
        $id = (int) $this->db()->insert('catering_inquiries', $record);
        $this->linkInquiryToCore($id, $record);
        $profile = $this->ctx->service(BusinessProfile::class)->get();
        $to = $profile['email'] !== ''
            ? $profile['email']
            : (string) $this->settings()->get('site.contact_email', '');
        if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $lines = [];
            foreach ($record as $key => $value) {
                if ($value !== null && $value !== '') {
                    if ($key === 'menu_plan') {
                        $plan = json_decode((string) $value, true) ?: [];
                        $lines[] = 'Selected package: ' . ($plan['package_name'] ?? 'Custom menu');
                        $lines[] = 'Selected dishes: ' . implode(', ', array_column($plan['items'] ?? [], 'name'));
                        $lines[] = 'Preliminary food estimate: ' . (isset($plan['estimate_cents']) ? '$' . number_format($plan['estimate_cents'] / 100, 2) : 'Team confirmation required');
                        $lines[] = 'Estimate excludes taxes, staffing, rentals, delivery and service charges.';
                    } else {
                        $lines[] = ucwords(str_replace('_', ' ', $key)) . ': ' . (string) $value;
                    }
                }
            }
            $this->ctx->service(SystemMail::class)->queue(
                $to,
                ($kind === 'quote' ? 'New catering quote request from ' : 'New catering message from ') . $data['name'],
                implode("\n", $lines),
                'catering-inquiry:' . $id
            );
        }
    }

    /**
     * Link preserved module questionnaires to Core's package-scoped sales
     * workspace in bounded, retry-safe batches. This runs after migrations
     * and never deletes or rewrites the detailed catering record.
     */
    public function synchronizeLegacyInquiries(int $limit = 100): void
    {
        $db = $this->db();
        $table = $db->table('catering_inquiries');
        if (!$db->tableExists('catering_inquiries') || !self::hasColumn($db, $table, 'core_inquiry_id')) {
            return;
        }
        $rows = $db->fetchAll(
            "SELECT * FROM `{$table}` WHERE core_inquiry_id IS NULL ORDER BY id ASC LIMIT " . max(1, min(250, $limit))
        );
        foreach ($rows as $row) {
            $this->linkInquiryToCore((int) $row['id'], $row);
        }
    }

    public static function pendingCoreLinks(Connection $db): int
    {
        if (!$db->tableExists('catering_inquiries')) {
            return 0;
        }
        $table = $db->table('catering_inquiries');
        if (!self::hasColumn($db, $table, 'core_inquiry_id')) {
            return (int) $db->fetchColumn("SELECT COUNT(*) FROM `{$table}`");
        }
        return (int) $db->fetchColumn("SELECT COUNT(*) FROM `{$table}` WHERE core_inquiry_id IS NULL");
    }

    private function linkInquiryToCore(int $localId, array $record): void
    {
        if ($localId < 1 || !self::hasColumn($this->db(), $this->db()->table('catering_inquiries'), 'core_inquiry_id')) {
            return;
        }
        $this->db()->transaction(function () use ($localId, $record): void {
            $linked = $this->db()->fetchColumn(
                'SELECT core_inquiry_id FROM `' . $this->db()->table('catering_inquiries') . '` WHERE id = ?'
                    . ($this->db()->driver() === 'mysql' ? ' FOR UPDATE' : ''),
                [$localId]
            );
            if ($linked !== false && $linked !== null && (int) $linked > 0) {
                return;
            }

            $kind = (string) ($record['kind'] ?? 'quote');
            [$coreId, $error] = $this->ctx->service(InquiryService::class)->submit(
                $this->ctx->packageId(),
                [
                    'name' => (string) ($record['name'] ?? ''),
                    'email' => (string) ($record['email'] ?? ''),
                    'phone' => (string) ($record['phone'] ?? ''),
                    'subject' => $kind === 'quote'
                        ? 'Catering quote request' . (($record['event_type'] ?? '') !== '' ? ': ' . $record['event_type'] : '')
                        : 'Catering contact inquiry',
                    'message' => $this->inquirySummary($record),
                ]
            );
            if ($error !== null || $coreId === null) {
                return;
            }
            $this->ctx->service(InquiryService::class)->update(
                $coreId,
                $this->ctx->packageId(),
                $this->coreInquiryStatus((string) ($record['status'] ?? 'new')),
                'normal',
                null
            );
            $this->db()->update('catering_inquiries', ['core_inquiry_id' => $coreId], 'id = ? AND core_inquiry_id IS NULL', [$localId]);
        });
    }

    private function inquirySummary(array $record): string
    {
        $labels = [
            'company' => 'Company', 'event_type' => 'Event type', 'event_date' => 'Event date',
            'event_time' => 'Start time', 'guest_count' => 'Estimated guests', 'venue' => 'Venue',
            'service_style' => 'Service style', 'menu_interest' => 'Menu interest', 'budget' => 'Budget',
            'dietary' => 'Dietary needs', 'message' => 'Customer message', 'created_at' => 'Originally received',
        ];
        $lines = [];
        foreach ($labels as $key => $label) {
            $value = trim((string) ($record[$key] ?? ''));
            if ($value !== '') {
                $lines[] = $label . ': ' . $value;
            }
        }
        $plan = json_decode((string) ($record['menu_plan'] ?? ''), true);
        if (is_array($plan)) {
            $lines[] = 'Menu submission snapshot';
            $lines[] = 'Selected package: ' . ($plan['package_name'] ?? 'Custom menu');
            $lines[] = 'Selected dishes: ' . implode(', ', array_column($plan['items'] ?? [], 'name'));
            $lines[] = 'Preliminary food estimate: ' . (isset($plan['estimate_cents'])
                ? '$' . number_format($plan['estimate_cents'] / 100, 2) : 'Team confirmation required');
            $lines[] = 'Estimate excludes taxes, staffing, rentals, delivery and service charges.';
        }
        return $lines !== [] ? implode("\n", $lines) : 'Catering inquiry submitted through the website.';
    }

    private function coreInquiryStatus(string $status): string
    {
        return match ($status) {
            'reviewing', 'more-info' => 'contacted',
            'quote-in-progress' => 'qualified',
            'quote-sent' => 'quoted',
            'accepted', 'deposit-pending', 'booked', 'completed' => 'won',
            'declined', 'cancelled' => 'lost',
            default => 'new',
        };
    }

    private static function hasColumn(Connection $db, string $table, string $column): bool
    {
        if ($db->driver() === 'sqlite') {
            return in_array($column, array_column($db->fetchAll("PRAGMA table_info(`{$table}`)"), 'name'), true);
        }
        return (bool) $db->fetchColumn(
            'SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? AND column_name=?',
            [$table, $column]
        );
    }

    private function contactInput(Request $request): array
    {
        return [
            'name' => mb_substr($request->string('name'), 0, 190),
            'email' => mb_substr($request->string('email'), 0, 190),
            'phone' => mb_substr($request->string('phone'), 0, 80),
            'message' => mb_substr($request->string('message'), 0, 5000),
        ];
    }

    private function quoteInput(Request $request): array
    {
        return [
            'name' => mb_substr($request->string('name'), 0, 190),
            'email' => mb_substr($request->string('email'), 0, 190),
            'phone' => mb_substr($request->string('phone'), 0, 80),
            'company' => mb_substr($request->string('company'), 0, 190),
            'event_type' => mb_substr($request->string('event_type'), 0, 100),
            'event_date' => mb_substr($request->string('event_date'), 0, 20),
            'event_time' => mb_substr($request->string('event_time'), 0, 20),
            'guest_count' => mb_substr($request->string('guest_count'), 0, 10),
            'venue' => mb_substr($request->string('venue'), 0, 255),
            'service_style' => mb_substr($request->string('service_style'), 0, 100),
            'menu_interest' => mb_substr($request->string('menu_interest'), 0, 190),
            'budget' => mb_substr($request->string('budget'), 0, 100),
            'dietary' => mb_substr($request->string('dietary'), 0, 3000),
            'message' => mb_substr($request->string('message'), 0, 5000),
        ];
    }

    private function contactErrors(array $data): array
    {
        $errors = [];
        if ($data['name'] === '') {
            $errors[] = 'Your name is required.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }
        if ($data['message'] === '') {
            $errors[] = 'Please enter a message.';
        }
        return $errors;
    }

    private function quoteErrors(array $data): array
    {
        $errors = $this->contactErrors([
            'name' => $data['name'], 'email' => $data['email'],
            'message' => $data['message'] !== '' ? $data['message'] : 'Event details supplied',
        ]);
        if (!in_array($data['event_type'], ['Wedding', 'Corporate Event', 'Private Celebration', 'Drop-Off Catering', 'Holiday Event', 'Other'], true)) {
            $errors[] = 'Please choose a supported event type.';
        }
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $data['event_date']);
        if ($date === false || $date->format('Y-m-d') !== $data['event_date']) {
            $errors[] = 'Please provide a valid event date.';
        }
        if ($data['event_time'] !== '' && !preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D', $data['event_time'])) {
            $errors[] = 'Please provide a valid event time.';
        }
        if (!in_array($data['service_style'], ['', 'Plated dinner', 'Buffet', 'Food stations', 'Cocktail reception', 'Family style', 'Drop-off or pickup'], true)) {
            $errors[] = 'Please choose a supported service style.';
        }
        if ((int) $data['guest_count'] < 1 || (int) $data['guest_count'] > 10000) {
            $errors[] = 'Guest count must be between 1 and 10,000.';
        }
        return $errors;
    }

    private function menuData(bool $visibleOnly = true): array
    {
        $where = $visibleOnly ? ' WHERE visible = 1' : '';
        return [
            $this->db()->fetchAll('SELECT * FROM `' . $this->db()->table('catering_menu_categories') . '`' . $where . ' ORDER BY sort_order, name'),
            $this->db()->fetchAll('SELECT * FROM `' . $this->db()->table('catering_menu_items') . '`' . $where . ' ORDER BY category_id, sort_order, name'),
        ];
    }

    private function visiblePackages(?int $limit = null): array
    {
        return $this->db()->fetchAll(
            'SELECT * FROM `' . $this->db()->table('catering_packages')
            . '` WHERE visible = 1 ORDER BY sort_order, name' . ($limit !== null ? ' LIMIT ' . $limit : '')
        );
    }

    private function uniqueSlug(string $table, string $name): string
    {
        $slug = strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '-', $name), '-')) ?: 'item';
        $candidate = $slug;
        $suffix = 2;
        while ($this->db()->fetchColumn(
            'SELECT COUNT(*) FROM `' . $this->db()->table($table) . '` WHERE slug = ?',
            [$candidate]
        )) {
            $candidate = $slug . '-' . $suffix++;
        }
        return $candidate;
    }

    private function guard(string $capability): ?Response
    {
        $auth = $this->ctx->service(AuthService::class);
        $user = $auth->user();
        if ($user === null || !$auth->has($user, $capability)) {
            return Response::html('<h1>403 — Forbidden</h1>', 403);
        }
        return null;
    }

    private function db(): Connection
    {
        return $this->ctx->service(Connection::class);
    }

    private function settings(): SettingsRepository
    {
        return $this->ctx->service(SettingsRepository::class);
    }

    private function plannerCatalog(): array
    {
        $db = $this->db();
        [$categories] = $this->menuData();
        $items = $db->fetchAll('SELECT i.* FROM `' . $db->table('catering_menu_items') . '` i INNER JOIN `'
            . $db->table('catering_menu_categories') . '` c ON c.id = i.category_id WHERE i.visible = 1 AND c.visible = 1 ORDER BY c.sort_order, i.sort_order, i.name');
        return [$categories, $items, $this->visiblePackages()];
    }

    private function pricingInput(Request $request): array
    {
        $basis = $request->string('pricing_basis', 'manual');
        if (!isset(MenuPlanner::PRICING[$basis])) throw new \InvalidArgumentException('Choose a supported pricing method.');
        return [
            'pricing_basis' => $basis, 'price_cents' => MenuPlanner::cents($request->string('price', '0')),
            'servings_per_tray' => $basis === 'per_tray' ? $this->boundedInt($request->string('servings_per_tray'), 1, 10000, 'Servings per tray') : 1,
            'minimum_quantity' => in_array($basis, ['per_guest', 'per_tray'], true) ? $this->boundedInt($request->string('minimum_quantity', '1'), 1, 10000, 'Minimum quantity') : 1,
            'guest_tiers' => json_encode($this->guestTiers($request), JSON_THROW_ON_ERROR),
        ];
    }

    private function guestTiers(Request $request): array
    {
        $input = $request->input('guest_tiers', []);
        if (!is_array($input) || count($input) > 5) throw new \InvalidArgumentException('Use up to five guest-count discount tiers.');
        $tiers = [];
        foreach ($input as $row) {
            if (!is_array($row)) throw new \InvalidArgumentException('Invalid guest-count discount tier.');
            $guests = is_string($row['minimum_guests'] ?? null) ? trim($row['minimum_guests']) : '';
            $percent = is_string($row['discount_percent'] ?? null) ? trim($row['discount_percent']) : '';
            if ($guests === '' && $percent === '') continue;
            $minimum = $this->boundedInt($guests, 1, 10000, 'Discount guest threshold');
            if (!preg_match('/^\d{1,3}(?:\.\d{1,2})?$/D', $percent) || MenuPlanner::cents($percent) > 10000) {
                throw new \InvalidArgumentException('Discount must be between 0 and 100 percent with at most two decimal places.');
            }
            if (isset($tiers[$minimum])) throw new \InvalidArgumentException('Each discount guest threshold must be unique.');
            $tiers[$minimum] = ['minimum_guests' => $minimum, 'discount_bps' => MenuPlanner::cents($percent)];
        }
        ksort($tiers, SORT_NUMERIC);
        return array_values($tiers);
    }

    private function boundedInt(string $raw, int $min, int $max, string $label): int
    {
        if (!preg_match('/^(0|[1-9][0-9]{0,8})$/D', $raw) || (int) $raw < $min || (int) $raw > $max) {
            throw new \InvalidArgumentException($label . ' must be a whole number between ' . $min . ' and ' . $max . '.');
        }
        return (int) $raw;
    }

    private function validatedTags(mixed $value, array $allowed): array
    {
        if (!is_array($value) || count($value) > count($allowed)) throw new \InvalidArgumentException('Choose supported dietary or allergen labels.');
        foreach ($value as $tag) if (!is_string($tag) || !isset($allowed[$tag])) throw new \InvalidArgumentException('Choose supported dietary or allergen labels.');
        return array_values(array_unique($value));
    }

    private function packageRules(Request $request): array
    {
        $input = $request->input('choices', []);
        if (!is_array($input) || count($input) > 100) throw new \InvalidArgumentException('Invalid package choice rules.');
        [$categories, $items] = $this->menuData();
        $available = [];
        foreach ($items as $item) $available[$item['category_id']] = ($available[$item['category_id']] ?? 0) + 1;
        $byId = array_column($categories, null, 'id');
        $rules = [];
        foreach ($input as $id => $rule) {
            if (!is_array($rule)) throw new \InvalidArgumentException('Invalid package choice rule.');
            if (($rule['enabled'] ?? '') !== '1') continue;
            if (!isset($byId[$id])) throw new \InvalidArgumentException('Package choices must use a visible category.');
            $min = $this->boundedInt(is_string($rule['min'] ?? null) ? $rule['min'] : '', 0, 100, 'Minimum choices');
            $max = $this->boundedInt(is_string($rule['max'] ?? null) ? $rule['max'] : '', max(1, $min), 100, 'Maximum choices');
            if ($min > ($available[$id] ?? 0)) throw new \InvalidArgumentException('There are not enough visible dishes to meet the minimum choices for ' . $byId[$id]['name'] . '.');
            $rules[(string) $id] = ['label' => $byId[$id]['name'], 'min' => $min, 'max' => $max];
        }
        return $rules;
    }

    private function existingId(Request $request, string $table): int
    {
        $id = $this->boundedInt($request->string('id'), 1, 999999999, 'Record ID');
        if (!$this->db()->fetchColumn('SELECT id FROM `' . $this->db()->table($table) . '` WHERE id = ?', [$id])) {
            throw new \InvalidArgumentException('This record no longer exists.');
        }
        return $id;
    }

    private function audit(string $event, int $id): void
    {
        $user = $this->ctx->service(AuthService::class)->user();
        $this->ctx->service(AuditLog::class)->record($event, $user?->id, ['record_id' => $id]);
    }

    private function serviceData(): array
    {
        return [
            'weddings' => [
                'title' => 'Wedding Catering', 'short' => 'Weddings',
                'summary' => 'A celebration designed around your story, from the first toast to the final sweet bite.',
                'kicker' => 'Your table, your story', 'image' => 'wedding-table.webp',
                'intro' => 'We shape the menu, service rhythm, and finishing details around the way you want your wedding to feel—warm, polished, and entirely personal.',
                'features' => ['Private menu consultation', 'Tasting experience', 'Cocktail-hour bites', 'Plated, buffet, or station service', 'Professional service team', 'Rental and timeline coordination'],
                'process' => ['Share your vision and venue', 'Taste and refine your menu', 'Finalize service and timeline', 'Celebrate while we handle the details'],
            ],
            'corporate-events' => [
                'title' => 'Corporate Catering', 'short' => 'Corporate Events',
                'summary' => 'Reliable, polished food service for meetings, conferences, launches, and company celebrations.',
                'kicker' => 'Business, beautifully hosted', 'image' => 'corporate-service.webp',
                'intro' => 'From an executive breakfast to a multi-day conference, our corporate menus are designed for timely service, clear planning, and an excellent guest experience.',
                'features' => ['Breakfast and lunch service', 'Boxed and individually packaged meals', 'Conference buffets', 'Cocktail receptions', 'Dietary meal labeling', 'Recurring-event planning'],
                'process' => ['Tell us the schedule', 'Choose service and menu', 'Confirm delivery details', 'Host with confidence'],
            ],
            'private-celebrations' => [
                'title' => 'Private Celebrations', 'short' => 'Private Celebrations',
                'summary' => 'Birthdays, anniversaries, showers, graduations, and gatherings made effortless.',
                'kicker' => 'Make the moment yours', 'image' => 'hero.webp',
                'intro' => 'Whether the guest list is intimate or expansive, we create a relaxed hospitality experience with food that fits the people and the occasion.',
                'features' => ['Flexible menu formats', 'Cocktail and dinner service', 'Family-style options', 'Celebration desserts', 'Bartending coordination', 'Home and venue service'],
                'process' => ['Choose the occasion', 'Build the menu', 'Add service details', 'Gather and enjoy'],
            ],
            'drop-off-catering' => [
                'title' => 'Drop-Off Catering', 'short' => 'Drop-Off Catering',
                'summary' => 'Freshly prepared, ready-to-serve catering for offices, family gatherings, and casual events.',
                'kicker' => 'Easy to serve, hard to forget', 'image' => 'plated-dinner.webp',
                'intro' => 'Choose your menu and delivery window, and we will arrive with labeled dishes, serving guidance, and everything arranged for a smooth meal.',
                'features' => ['Scheduled delivery or pickup', 'Disposable service ware', 'Hot and cold packaging', 'Clear dish labels', 'Dietary accommodations', 'Optional setup service'],
                'process' => ['Select a menu', 'Confirm headcount', 'Choose delivery or pickup', 'Unpack and serve'],
            ],
        ];
    }

    private function faqData(): array
    {
        return [
            ['How far in advance should we book?', 'For full-service events, begin the conversation as early as possible. Smaller drop-off orders may require less lead time, depending on the date and menu.'],
            ['Can you accommodate dietary needs?', 'Yes. Share allergies and dietary requirements during planning so the kitchen can recommend appropriate selections and handling.'],
            ['Do you provide tastings?', 'Tastings can be scheduled for qualifying full-service events after an initial consultation and menu direction are established.'],
            ['When is the final guest count due?', 'Your proposal and agreement will state the final-count deadline. This allows the culinary and service teams to prepare accurately.'],
            ['Do you provide staff and rentals?', 'Staffing, service ware, linens, china, glassware, and rental coordination can be included according to the selected service level.'],
            ['Is the website pricing a final quote?', 'No. Displayed prices are starting points. Venue requirements, staffing, travel, rentals, taxes, and menu changes affect the final proposal.'],
        ];
    }
}
