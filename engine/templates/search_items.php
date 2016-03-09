<?php if ( isset($response['data']) ): ?>
<!-- Search items -->
<div class="ps-search-main">
    <h1><?php echo Language::get('items', 'search_items'); ?>:</h1>
    <div class="ps-search">
        <?php foreach ($response['data'] as $v): ?>
        <div class="ps-search-block">
            <div class="ps-search-block-name"><?php echo $v['name']; ?></div>
            <table>
                <?php if ($v['properties']) foreach ($v['properties'] as $p_id=>$p_v): ?>
                <tr>
                    <td><?php echo $data['properties'][$p_id]['name']; ?>:</td>
                    <td><?php echo $p_v['name']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="ps-search-main">
    <h1><?php echo Language::get('items', 'search_items_empty'); ?>.</h1>
</div>
<?php endif; ?>