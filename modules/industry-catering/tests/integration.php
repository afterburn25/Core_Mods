<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
session_save_path(sys_get_temp_dir());
session_start();
require __DIR__ . '/bootstrap.php';
set_error_handler(static function (int $level, string $message, string $file, int $line): never {
    throw new ErrorException($message, 0, $level, $file, $line);
});
$checks = 0;
function verify(bool $condition, string $description): void {
    global $checks;
    if (!$condition) throw new RuntimeException($description);
    $checks++;
}
$f = cateringFixture();
$db = $f['db']; $csrf = $f['csrf']; $ctl = $f['controller']; $router = $f['router'];
$token = $csrf->token();
$categories = $db->fetchAll('SELECT * FROM `' . $db->table('catering_menu_categories') . '` ORDER BY id');
$items = $db->fetchAll('SELECT * FROM `' . $db->table('catering_menu_items') . '` ORDER BY id');
verify(count($items) === 10, 'All starter dishes survive the additive migration');
verify($items[0]['pricing_basis'] === 'per_guest', 'Legacy guest prices are inferred once');
$db->update('catering_menu_items', ['pricing_basis' => 'manual', 'name' => 'Administrator dish', 'dietary' => 'Custom dietary note'], 'id = ?', [(int) $items[0]['id']]);
$migration = require $moduleRoot . '/migrations/0005_menu_planner.php';
$migration->up($db); $migration->up($db);
$custom = $db->fetch('SELECT * FROM `' . $db->table('catering_menu_items') . '` WHERE id = ?', [$items[0]['id']]);
verify($custom['pricing_basis'] === 'manual' && $custom['dietary'] === 'Custom dietary note', 'Repeated upgrade preserves administrator pricing and content');
verify((new Core\Database\Migrator($db, $moduleRoot . '/migrations'))->pending() === [], 'Core migration journal records the module migrations');
verify($router->dispatch(cateringRequest('POST', '/catering/estimate', ['guest_count' => '25']))->status() === 419, 'Estimate endpoint requires CSRF');
verify($ctl->adminMenusSave(cateringRequest('POST', '/admin/catering/menus', ['_csrf' => $token, 'action' => 'delete_item', 'id' => $items[0]['id']]))->status() === 403, 'Direct admin action denies unauthenticated callers');
cateringTestIdentity($f, 'editor');
verify($ctl->adminPackagesSave(cateringRequest('POST', '/admin/catering/packages', ['_csrf' => $token, 'action' => 'add_package']))->status() === 403, 'Core editor cannot modify catering packages');
cateringTestIdentity($f, 'owner');
verify($ctl->adminMenusSave(cateringRequest('POST', '/admin/catering/menus', ['action' => 'add_item']))->status() === 419, 'Direct admin action also requires CSRF');
$input = ['_csrf' => $token, 'action' => 'update_item', 'id' => (string) $items[0]['id'], 'category_id' => (string) $categories[0]['id'],
    'name' => '<script>alert(1)</script> Special', 'description' => 'A test dish', 'price' => '125.25', 'pricing_basis' => 'per_tray',
    'servings_per_tray' => '12', 'minimum_quantity' => '1', 'price_unit' => 'per tray', 'dietary_tags' => ['vegetarian'],
    'allergens' => ['milk', 'wheat'], 'allergen_notes' => 'Shared kitchen', 'dietary' => 'Recipe verified', 'image' => 'boudin-balls.webp', 'sort_order' => '10'];
verify($router->dispatch(cateringRequest('POST', '/admin/catering/menus', $input))->status() === 302, 'Owner can update catering pricing and labels through protected route');
$updated = $db->fetch('SELECT * FROM `' . $db->table('catering_menu_items') . '` WHERE id = ?', [$items[0]['id']]);
verify((int) $updated['price_cents'] === 12525 && $updated['pricing_basis'] === 'per_tray', 'Exact price and serving configuration persisted');
verify(json_decode($updated['allergens'], true) === ['milk', 'wheat'], 'Structured allergens persisted');
verify($db->fetchColumn('SELECT actor_id FROM `' . $db->table('audit_log') . '` WHERE event = ?', ['catering.menu.update_item']) !== false, 'Menu changes create a Core audit event');
$bad = $input; $bad['price'] = '1.999';
$response = $router->dispatch(cateringRequest('POST', '/admin/catering/menus', $bad));
verify($response->status() === 200 && str_contains($response->body(), 'at most two decimal'), 'Invalid price produces a retained-input error');
verify(str_contains($response->body(), 'value="1.999"'), 'Failed editor preserves input');
verify(!str_contains($response->body(), '<script>alert(1)</script>'), 'Admin display escapes dish names');
$package = $db->fetch('SELECT * FROM `' . $db->table('catering_packages') . '` ORDER BY id LIMIT 1');
$packageInput = ['_csrf' => $token, 'action' => 'update_package', 'id' => (string) $package['id'], 'name' => $package['name'],
    'price' => '39.00', 'pricing_basis' => 'per_guest', 'minimum_quantity' => '1', 'guest_min' => '20', 'guest_max' => '100',
    'choices' => [(string) $categories[0]['id'] => ['enabled' => '1', 'min' => '1', 'max' => '1']]];
verify($router->dispatch(cateringRequest('POST', '/admin/catering/packages', $packageInput))->status() === 302, 'Owner can configure included category choices');
$tierInput = $input;
$tierInput['guest_tiers'] = [['minimum_guests' => '50', 'discount_percent' => '5.00']];
verify($router->dispatch(cateringRequest('POST', '/admin/catering/menus', $tierInput))->status() === 302, 'Guest-count tiers can be saved through admin');
$tierEstimate = $estimateInput ?? ['_csrf' => $token, 'guest_count' => '50', 'menu_items' => [(string) $items[0]['id']]];
$tierEstimate['guest_count'] = '50';
$discounted = json_decode($router->dispatch(cateringRequest('POST', '/catering/estimate', $tierEstimate))->body(), true);
verify($discounted['lines'][0]['discount_bps'] === 500 && $discounted['estimate_cents'] === 59495, 'Saved discount is applied to the rounded tray quantity');
$badTier = $input; $badTier['guest_tiers'] = [['minimum_guests' => '50', 'discount_percent' => '101']];
verify(str_contains($router->dispatch(cateringRequest('POST', '/admin/catering/menus', $badTier))->body(), 'between 0 and 100'), 'Invalid discount rejected without saving');
$badPackage = $packageInput; $badPackage['guest_max'] = '10';
verify(str_contains($router->dispatch(cateringRequest('POST', '/admin/catering/packages', $badPackage))->body(), 'Maximum guests must'), 'Impossible guest range is rejected');
$badPackage = $packageInput; $badPackage['choices'][(string) $categories[0]['id']]['min'] = '99'; $badPackage['choices'][(string) $categories[0]['id']]['max'] = '100';
verify(str_contains($router->dispatch(cateringRequest('POST', '/admin/catering/packages', $badPackage))->body(), 'not enough visible dishes'), 'Unsatisfiable package choices rejected');
$estimateInput = ['_csrf' => $token, 'guest_count' => '25', 'menu_items' => [(string) $items[0]['id']], 'estimate_cents' => '1'];
$estimate = $router->dispatch(cateringRequest('POST', '/catering/estimate', $estimateInput));
verify($estimate->status() === 200 && json_decode($estimate->body(), true)['estimate_cents'] === 37575, 'Estimate route rounds trays and ignores submitted totals');
verify(($estimate->headers()['Cache-Control'] ?? '') === 'private, no-store', 'Estimate responses cannot enter shared caches');
$db->update('catering_menu_categories', ['visible' => 0], 'id = ?', [$categories[0]['id']]);
verify($router->dispatch(cateringRequest('POST', '/catering/estimate', $estimateInput))->status() === 422, 'Dishes in hidden categories cannot be selected');
$db->update('catering_menu_categories', ['visible' => 1], 'id = ?', [$categories[0]['id']]);
$before = (int) $db->fetchColumn('SELECT COUNT(*) FROM `' . $db->table('catering_inquiries') . '`');
$preview = $router->dispatch(cateringRequest('POST', '/request-a-quote', $estimateInput + ['preview_plan' => '1']));
verify($preview->status() === 200 && str_contains($preview->body(), '375.75'), 'No-JavaScript preview calculates the server estimate');
verify((int) $db->fetchColumn('SELECT COUNT(*) FROM `' . $db->table('catering_inquiries') . '`') === $before, 'Preview does not create an inquiry');
$quote = $estimateInput + ['event_type' => 'Wedding', 'event_date' => '2027-06-15', 'name' => 'Menu Planner Test', 'email' => 'guest@example.test',
    'dietary' => 'Please discuss milk allergy', 'acknowledged' => '1', 'package_id' => (string) $package['id']];
$response = $router->dispatch(cateringRequest('POST', '/request-a-quote', $quote));
verify($response->status() === 302 && str_contains($response->headers()['Location'], '/catering/index.php?p=/request-a-quote&sent=1'), 'Quote redirects correctly under subfolder/query routing');
$row = $db->fetch('SELECT * FROM `' . $db->table('catering_inquiries') . '` ORDER BY id DESC LIMIT 1');
$snapshot = json_decode($row['menu_plan'], true);
verify($snapshot['estimate_cents'] === 97500 && $snapshot['items'][0]['name'] === $input['name'], 'Inquiry stores authoritative package/menu/price snapshot');
verify($row['dietary'] === 'Please discuss milk allergy', 'Customer allergy requests remain separate from catalog labels');
$mail = $db->fetch('SELECT * FROM `' . $db->table('system_messages') . '` ORDER BY id DESC LIMIT 1');
verify(str_contains($mail['body'], 'Selected dishes:') && str_contains($mail['body'], '$975.00'), 'Core queued email includes the selected menu and estimate');
$db->update('catering_menu_items', ['name' => 'Renamed later'], 'id = ?', [$items[0]['id']]);
$inbox = $router->dispatch(cateringRequest('GET', '/admin/catering/inquiries'));
verify($inbox->status() === 302 && str_contains($inbox->headers()['Location'], '/admin/inquiries'), 'Legacy inbox redirects to Core');
$coreInquiry = $db->fetch('SELECT * FROM `' . $db->table('crm_inquiries') . '` WHERE id = ? AND owner_package = ?', [(int) $row['core_inquiry_id'], 'cajunwebpro/catering']);
verify($coreInquiry !== null && str_contains($coreInquiry['message'], 'Menu submission snapshot') && str_contains($coreInquiry['message'], 'Special') && str_contains($coreInquiry['message'], '$975.00'), 'Core inquiry retains original selected menu and estimate');
$coreCount = (int) $db->fetchColumn('SELECT COUNT(*) FROM `' . $db->table('crm_inquiries') . '`');
$ctl->synchronizeLegacyInquiries(); $ctl->synchronizeLegacyInquiries();
verify((int) $db->fetchColumn('SELECT COUNT(*) FROM `' . $db->table('crm_inquiries') . '`') === $coreCount, 'Repeated linking does not duplicate sales inquiries');
verify((int) $db->fetchColumn('SELECT COUNT(*) FROM `' . $db->table('system_messages') . '`') === 1, 'One notification is queued per submission');
$invalidQuote = $quote; $invalidQuote['event_date'] = '2027-02-30';
verify(str_contains($router->dispatch(cateringRequest('POST', '/request-a-quote', $invalidQuote))->body(), 'valid event date'), 'Impossible calendar date rejected server-side');
$invalidQuote = $quote; unset($invalidQuote['acknowledged']);
verify(str_contains($router->dispatch(cateringRequest('POST', '/request-a-quote', $invalidQuote))->body(), 'acknowledge'), 'Booking acknowledgement enforced server-side');
foreach (['/', '/menus', '/menus/' . $categories[0]['slug'], '/packages', '/packages/' . $package['slug'], '/request-a-quote', '/admin/catering/menus', '/admin/catering/packages'] as $path) {
    $response = $router->dispatch(cateringRequest('GET', $path));
    verify($response->status() === 200, 'Public/admin route renders: ' . $path);
}
$limited = false;
for ($i = 0; $i < 95; $i++) {
    if ($router->dispatch(cateringRequest('POST', '/catering/estimate', $estimateInput))->status() === 429) { $limited = true; break; }
}
verify($limited, 'Estimate endpoint is rate limited');
// Native test databases use an isolated random prefix; remove only this fixture's own tables.
if ($db->driver() === 'mysql') {
    $db->pdo()->exec('SET FOREIGN_KEY_CHECKS=0');
    $tables = $db->fetchAll('SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name LIKE ?', [$db->prefix() . '%']);
    foreach ($tables as $table) {
        $name = (string) array_values($table)[0];
        if (str_starts_with($name, $db->prefix())) $db->pdo()->exec('DROP TABLE `' . str_replace('`', '``', $name) . '`');
    }
    $db->pdo()->exec('SET FOREIGN_KEY_CHECKS=1');
}
echo "Module integration ({$db->driver()}): {$checks} checks passed.\n";
