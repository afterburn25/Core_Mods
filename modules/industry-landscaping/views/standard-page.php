<?php /** @var array $page @var string $slug @var string $basePath */ ?>
<section class="l-page-hero"><p class="l-eyebrow">Landscaping Co.</p><h1><?= e($page['title']) ?></h1><p><?= e($page['meta_description']) ?></p></section>
<section class="l-section l-page-body">
<?php if ($slug === 'request-estimate'): ?>
<h2>Estimate workflow planned</h2><p>The production release will collect property type, address, requested services, photos, preferred schedule, and contact information through a secured, rate-limited form.</p>
<?php else: ?>
<h2>Ready for business content</h2><p>This foundation establishes the route and design surface. The next milestone adds editable industry records and administration for this section.</p>
<?php endif; ?>
<a class="l-btn l-btn-primary" href="<?= e_attr($basePath) ?>/">Return Home</a>
</section>
