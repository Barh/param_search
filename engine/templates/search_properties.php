<?php if (!empty($data['pv'])): ?>
<!-- Search Properties -->
<div class="ps-filters-main">
    <!-- Title -->
    <h1><?php echo Language::get('properties', 'search_title'); ?></h1>

    <!-- Form -->
    <form method="get">
        <input type="hidden" name="page" value="search" />
        <!-- Filters -->
        <div class="ps-filters">
            <?php foreach ($data['pv'] as $k=>$v): ?>
            <div class="ps-filters-block" data-property-id="<?php echo $k; ?>">
                <div class="ps-filters-key">
                    <span><?php echo $v['name']; ?>:</span>
                </div>
                <div class="ps-filters-values">
                    <?php if (isset($v['values'])): foreach ($v['values'] as $k_p => $v_p): ?>
                    <label class="<?php echo (empty($data['properties_id']) || isset($response['properties'][$k][$k_p])) ? '' : 'no-available'; ?>"><input type="checkbox" name="properties_id[<?php echo $k; ?>][<?php echo $k_p; ?>]" value="<?php echo $k_p; ?>" <?php echo isset($data['properties_id'][$k][$k_p]) ? 'checked="checked"' : ''; ?> /><?php echo $v_p; ?></label>
                    <?php endforeach; else : ?>
                        <?php echo Language::get('properties', 'values_empty'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Submit -->
        <input type="submit" value="<?php echo Language::get('properties', 'search_submit'); ?>" />
        <input type="reset" value="<?php echo Language::get('properties', 'search_reset'); ?>" />
    </form>
</div>
<?php else: ?>
<div>
    <h1><?php echo Language::get('properties', 'filter_empty'); ?></h1>
</div>
<?php endif;