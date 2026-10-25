<?php
/**
 * @var array $values
 * @var array $quoteFields
 * @var array $quoteFieldLabels
 * @var bool $saved
 * @var ?string $error
 * @var \Core\Security\Csrf $csrf
 * @var string $basePath
 */
?>
<?php if ($saved): ?><div class="flash-ok" role="status">Catering settings saved.</div><?php endif; ?>
<?php if ($error): ?><div class="flash-error" role="alert"><?= e($error) ?></div><?php endif; ?>

<div class="card">
    <div class="card-heading">
        <div><p class="section-kicker">Core-managed identity</p><h2>Business contact details</h2><p class="muted">Name, email, phone, address, hours, and service area are maintained once and shared across the website.</p></div>
        <a class="btn secondary" href="<?= e_attr($basePath) ?>/admin/business">Open business profile</a>
    </div>
</div>

<form method="post">
    <?= $csrf->field() ?>
    <div class="card">
        <div class="card-heading"><div><p class="section-kicker">Catering operations</p><h2>Planning defaults</h2><p class="muted">These values apply only to catering estimates and event planning.</p></div></div>
        <label>Travel and delivery note<textarea name="travel_note" rows="4" maxlength="1000"><?= e((string) $values['travel_note']) ?></textarea></label>
        <div class="form-grid">
            <label>Default minimum guests<input type="number" name="minimum_guests" min="1" max="10000" value="<?= (int) $values['minimum_guests'] ?>"></label>
            <label>Default lead time in days<input type="number" name="lead_time" min="0" max="3650" value="<?= (int) $values['lead_time'] ?>"></label>
            <label>Default quote expiration in days<input type="number" name="quote_expiry" min="1" max="365" value="<?= (int) $values['quote_expiry'] ?>"></label>
        </div>
    </div>

    <div class="card">
        <div class="card-heading"><div><p class="section-kicker">Quote planner</p><h2>Event choices</h2><p class="muted">Enter one public choice per line. Existing inquiry snapshots are never rewritten when these lists change.</p></div></div>
        <div class="form-grid">
            <label>Event types<textarea name="event_types" rows="8" required maxlength="2424"><?= e((string) $values['event_types']) ?></textarea></label>
            <label>Service styles<textarea name="service_styles" rows="8" required maxlength="2424"><?= e((string) $values['service_styles']) ?></textarea></label>
            <label>Budget ranges<textarea name="budget_ranges" rows="8" required maxlength="2424"><?= e((string) $values['budget_ranges']) ?></textarea></label>
        </div>
    </div>

    <div class="card">
        <div class="card-heading"><div><p class="section-kicker">Quote planner</p><h2>Optional questions</h2><p class="muted">Event type, date, guest count, menu selection, consent, name, and email remain required. Configure the Catering-specific planning questions below.</p></div></div>
        <div class="table-wrap"><table><thead><tr><th scope="col">Question</th><th scope="col">Show</th><th scope="col">Require</th></tr></thead><tbody>
        <?php foreach ($quoteFieldLabels as $key => $label): ?>
            <tr>
                <th scope="row"><?= e($label) ?></th>
                <td><label><input type="checkbox" name="quote_fields[<?= e_attr($key) ?>][enabled]" value="1"<?= !empty($quoteFields[$key]['enabled']) ? ' checked' : '' ?>> Show</label></td>
                <td><label><input type="checkbox" name="quote_fields[<?= e_attr($key) ?>][required]" value="1"<?= !empty($quoteFields[$key]['required']) ? ' checked' : '' ?>> Require when shown</label></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table></div>
        <div class="action-bar"><span>Generic form templates and notifications remain in Core.</span><button class="btn" type="submit">Save catering settings</button></div>
    </div>
</form>
