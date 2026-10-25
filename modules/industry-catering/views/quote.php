<?php
use CajunWebPro\Catering\MenuPlanner;
$text = static fn (string $key, string $default = ''): string => is_scalar($old[$key] ?? $default) ? (string) ($old[$key] ?? $default) : $default;
$prefillEvent = $text('event_type');
$selectedIds = array_map('strval', array_filter(is_array($old['menu_items'] ?? null) ? $old['menu_items'] : [], 'is_scalar'));
$enabled = static fn (string $key): bool => !empty($quoteFields[$key]['enabled']);
$required = static fn (string $key): bool => $enabled($key) && !empty($quoteFields[$key]['required']);
$optional = static fn (string $key): string => $required($key) ? '' : ' <small>optional</small>';
?>
<section class="c-quote-page">
<div class="c-quote-aside"><a class="c-back-link" href="<?= e_attr($basePath) ?>/">← Back to site</a><div><p class="c-eyebrow c-eyebrow-light">Plan your gathering</p><h1>A menu made for your occasion.</h1><p>Choose your dishes, explore a package, and share your plans. We’ll confirm the details together.</p></div><ol data-step-list><li class="is-active"><span>1</span>Event</li><li><span>2</span>Your menu</li><li><span>3</span>Contact</li><li><span>4</span>Review</li></ol></div>
<div class="c-quote-main">
<?php if ($sent): ?><div class="c-quote-success" role="status"><span>✓</span><p class="c-eyebrow">Request received</p><h2>Your gathering is on our radar.</h2><p>The catering team will review your event, menu selections and dietary requests, then respond using the contact information you provided.</p><a class="c-button c-button-green" href="<?= e_attr($basePath) ?>/">Return home</a></div><?php else: ?>
<div class="c-quote-heading" data-quote-progress hidden><span data-step-label>Step 1 of 4</span><div class="c-progress"><i data-progress></i></div></div>
<?php if ($error): ?><div class="c-form-error" role="alert" tabindex="-1" data-quote-error><?= e($error) ?></div><?php endif; ?>
<form method="post" action="<?= e_attr($basePath) ?>/request-a-quote" class="c-quote-form" data-quote-form data-estimate-url="<?= e_attr($estimateUrl) ?>">
<?= $csrf->field() ?>
<fieldset data-quote-step><legend><span>Event details</span>Let’s begin with the occasion.</legend><div class="c-option-grid">
<?php foreach ($eventTypes as $type): ?><label class="c-option-card"><input type="radio" name="event_type" value="<?= e_attr($type) ?>"<?= $prefillEvent === $type ? ' checked' : '' ?> required><span><?= e($type) ?></span></label><?php endforeach; ?>
</div><div class="c-form-grid c-form-grid-three">
<label>Event date<input type="date" name="event_date" value="<?= e_attr($text('event_date')) ?>" required></label>
<?php if ($enabled('event_time')): ?><label>Start time<?= $optional('event_time') ?><input type="time" name="event_time" value="<?= e_attr($text('event_time')) ?>"<?= $required('event_time') ? ' required' : '' ?>></label><?php endif; ?>
<label>Estimated guests<input type="number" name="guest_count" min="<?= (int) $minimumGuests ?>" max="10000" step="1" value="<?= e_attr($text('guest_count')) ?>" required></label>
</div><?php if ($enabled('venue')): ?><label>Venue or event location<?= $optional('venue') ?><input name="venue" maxlength="255" value="<?= e_attr($text('venue')) ?>" placeholder="Venue name, city, or undecided"<?= $required('venue') ? ' required' : '' ?>></label><?php endif; ?></fieldset>
<fieldset data-quote-step><legend><span>Your menu</span>Find the food that fits your gathering.</legend>
<label>Start with a package<select name="package_id" data-planner-package><option value="">Build my own menu / open to recommendations</option>
<?php foreach ($packages as $package): ?><option value="<?= (int) $package['id'] ?>" data-rules="<?= e_attr($package['choice_rules'] ?: '{}') ?>"<?= $text('package_id') === (string) $package['id'] ? ' selected' : '' ?>><?= e($package['name']) ?> · <?= (int) $package['guest_min'] ?><?= $package['guest_max'] ? '–' . (int) $package['guest_max'] : '+' ?> guests</option><?php endforeach; ?>
</select></label><p class="c-planner-rules" data-planner-rules>Choose individual dishes, or leave your menu open for recommendations.</p>
<div class="c-planner-filter" data-filter-controls hidden><label>Show dietary options<select data-dietary-filter><option value="">All dishes</option><?php foreach ($dietaryLabels as $key => $label): ?><option value="<?= e_attr($key) ?>"><?= e($label) ?></option><?php endforeach; ?></select></label><p class="c-disclaimer">Filters show business-supplied labels. They do not establish allergy safety; selected dishes stay visible.</p></div>
<?php foreach ($categories as $category): ?><section class="c-planner-category" data-planner-category><h2><?= e($category['name']) ?></h2><div class="c-planner-grid">
<?php foreach ($items as $item): if ((int) $item['category_id'] !== (int) $category['id']) continue;
$tags = MenuPlanner::tags($item['dietary_tags'], $dietaryLabels);
$allergens = MenuPlanner::tags($item['allergens'], $allergenLabels);
?>
<label class="c-planner-item" data-dietary-tags="<?= e_attr(implode(' ', $tags)) ?>" data-category-id="<?= (int) $category['id'] ?>">
<input type="checkbox" name="menu_items[]" value="<?= (int) $item['id'] ?>"<?= in_array((string) $item['id'], $selectedIds, true) ? ' checked' : '' ?>>
<?php if ($item['image']): ?><img src="<?= e_attr($assetBase . '/assets/images/menu/' . rawurlencode($item['image'])) ?>" alt="" loading="lazy" width="300" height="200"><?php endif; ?>
<span class="c-planner-copy"><strong><?= e($item['name']) ?></strong><span><?= e($item['description']) ?></span>
<span class="c-chips"><?php foreach ($tags as $tag): ?><span><?= e($dietaryLabels[$tag]) ?></span><?php endforeach; ?></span>
<span class="c-planner-price">$<?= e(number_format((int) $item['price_cents'] / 100, 2)) ?> · <?= e(MenuPlanner::PRICING[$item['pricing_basis']] ?? 'Team confirmation required') ?><?php if ($item['pricing_basis'] === 'per_tray'): ?> · serves <?= (int) $item['servings_per_tray'] ?> per tray<?php endif; ?></span>
<small>Known allergens: <?= e($allergens !== [] ? implode(', ', array_map(static fn ($tag) => $allergenLabels[$tag], $allergens)) : 'Not supplied — ask our team') ?></small>
<?php if ($item['allergen_notes']): ?><small><?= e($item['allergen_notes']) ?></small><?php endif; ?>
</span></label>
<?php endforeach; ?></div></section><?php endforeach; ?>
<?php if ($items === []): ?><p>No dishes are currently available for selection. Tell us your preferences below and the team will help.</p><?php endif; ?>
<div class="c-estimate" aria-live="polite" aria-atomic="true" data-menu-estimate>
<?php if ($initialPlan !== null): ?>
<h3>Preliminary food estimate</h3>
<?php if ($initialPlan['errors'] !== []): ?><p><?= e(implode(' ', $initialPlan['errors'])) ?></p><?php endif; ?>
<?php foreach ($initialPlan['lines'] as $line): ?><?php if (!empty($line['discount_bps'])): ?><p>Guest-count discount: <?= e(number_format($line['discount_bps'] / 100, 2)) ?>% applied.</p><?php endif; ?><p><?= e($line['name']) ?> · <?= $line['quantity'] !== null ? (int) $line['quantity'] . ' × $' . e(number_format($line['unit_cents'] / 100, 2)) : 'Team confirmation required' ?></p><?php endforeach; ?>
<strong><?= $initialPlan['estimate_cents'] !== null ? '$' . e(number_format($initialPlan['estimate_cents'] / 100, 2)) : 'Complete estimate needs team confirmation' ?></strong>
<?php foreach ($initialPlan['notes'] as $note): ?><p class="c-disclaimer"><?= e($note) ?></p><?php endforeach; ?>
<?php else: ?><h3>Preliminary food estimate</h3><p>Enter your guest count and choose dishes or a package to calculate a food estimate.</p><?php endif; ?>
</div>
<button type="submit" name="preview_plan" value="1" formnovalidate class="c-button c-button-quiet" data-preview-plan>Calculate food estimate</button>
<p class="c-disclaimer">This is an estimate and quote request, not checkout or a confirmed booking. Taxes, staffing, rentals, delivery and service charges are excluded. Each selected dish is calculated for the full headcount.</p>
<?php if ($enabled('service_style') || $enabled('budget')): ?><div class="c-form-grid">
<?php if ($enabled('service_style')): ?><label>Service style<?= $optional('service_style') ?><select name="service_style"<?= $required('service_style') ? ' required' : '' ?>><option value="">I’m not sure yet</option><?php foreach ($serviceStyles as $style): ?><option value="<?= e_attr($style) ?>"<?= $text('service_style') === $style ? ' selected' : '' ?>><?= e($style) ?></option><?php endforeach; ?></select></label><?php endif; ?>
<?php if ($enabled('budget')): ?><label>Estimated food and service budget<?= $optional('budget') ?><select name="budget"<?= $required('budget') ? ' required' : '' ?>><option value="">Prefer to discuss</option><?php foreach ($budgetRanges as $budget): ?><option value="<?= e_attr($budget) ?>"<?= $text('budget') === $budget ? ' selected' : '' ?>><?= e($budget) ?></option><?php endforeach; ?></select></label><?php endif; ?>
</div><?php endif; ?>
<?php if ($enabled('menu_interest')): ?><label>Menu preferences or other interests<?= $optional('menu_interest') ?><input name="menu_interest" maxlength="190" value="<?= e_attr($text('menu_interest')) ?>"<?= $required('menu_interest') ? ' required' : '' ?>></label><?php endif; ?>
<?php if ($enabled('dietary')): ?><label>Dietary needs, allergies and handling requests<?= $optional('dietary') ?><textarea name="dietary" rows="4" maxlength="3000"<?= $required('dietary') ? ' required' : '' ?>><?= e($text('dietary')) ?></textarea></label><p class="c-disclaimer">Listed allergens may be incomplete, and cross-contact may occur. The team must review allergy and dietary requests before confirming your menu.</p><?php endif; ?>
</fieldset>
<fieldset data-quote-step><legend><span>Your details</span>Where should we send the proposal?</legend><div class="c-form-grid"><label>Full name<input name="name" required maxlength="190" autocomplete="name" value="<?= e_attr($text('name')) ?>"></label><?php if ($enabled('company')): ?><label>Company<?= $optional('company') ?><input name="company" maxlength="190" autocomplete="organization" value="<?= e_attr($text('company')) ?>"<?= $required('company') ? ' required' : '' ?>></label><?php endif; ?><label>Email address<input name="email" type="email" required maxlength="190" autocomplete="email" value="<?= e_attr($text('email')) ?>"></label><?php if ($enabled('phone')): ?><label>Phone number<?= $optional('phone') ?><input name="phone" type="tel" maxlength="80" autocomplete="tel" value="<?= e_attr($text('phone')) ?>"<?= $required('phone') ? ' required' : '' ?>></label><?php endif; ?></div><?php if ($enabled('message')): ?><label>Anything else you’d like us to know?<?= $optional('message') ?><textarea name="message" rows="6" maxlength="5000"<?= $required('message') ? ' required' : '' ?>><?= e($text('message')) ?></textarea></label><?php endif; ?><label class="c-honeypot" aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label></fieldset>
<fieldset data-quote-step><legend><span>Review</span>Ready to start the conversation?</legend><div class="c-review-card"><p>Your event details and selected menu will be sent to the catering team for personal review.</p><div data-review-plan></div><ul><li>This is an inquiry, not a confirmed booking.</li><li>Estimates exclude taxes, staffing, rentals, delivery and service charges.</li><li>Availability and dietary accommodations require team confirmation.</li></ul></div><label class="c-consent"><input type="checkbox" name="acknowledged" value="1" required<?= $text('acknowledged') === '1' ? ' checked' : '' ?>><span>I understand that submitting this form does not reserve my event date or confirm a final price.</span></label></fieldset>
<div class="c-quote-controls"><button type="button" class="c-button c-button-quiet" data-quote-back hidden>Back</button><button type="button" class="c-button c-button-green" data-quote-next hidden>Continue</button><button type="submit" class="c-button c-button-gold" data-quote-submit>Send quote request</button></div>
</form><?php endif; ?>
</div></section>
