<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
session_save_path(sys_get_temp_dir());
session_start();
require __DIR__ . '/bootstrap.php';
$f = cateringFixture();
$service = $f['ctx']->service(Core\Leads\InquiryService::class);
[$id, $error] = $service->submit('cajunwebpro/catering', [
    'name' => 'Core contract check', 'email' => 'contract@example.test', 'message' => 'Detail must open before notes exist.',
]);
if ($error !== null || !$id) throw new RuntimeException('Core inquiry submission failed.');
if (!$service->find($id, 'cajunwebpro/catering')) throw new RuntimeException('Core detail missing.');
$adminId = (int) $f['admins']->findByUsername('owner')['id'];
if ($service->addNote($id, 'cajunwebpro/catering', $adminId, 'Acceptance note') !== null) throw new RuntimeException('Core note save failed.');
$detail = $service->find($id, 'cajunwebpro/catering');
if (($detail['notes'][0]['admin_name'] ?? '') === '') throw new RuntimeException('Core note author is missing.');
if ($service->find($id, 'other/package') !== null) throw new RuntimeException('Core inquiry ownership leaked.');
echo "Core inquiry detail and notes contract passed.\n";
