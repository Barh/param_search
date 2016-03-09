<?php
    if (!empty($response['data'])):?>
        <div class="ps-point-list">
        <?php foreach ($response['data'] as $k=>$v):?>
            <div class="ps-point-list-block">
                <h2><a href="?page=<?php echo $data['page']; ?>&id=<?php echo $k; ?>"><?php echo $v['name']; ?></a></h2>
            </div>
        <?php endforeach;?>
        </div>
    <?php else: ?>
        <div><?php echo Language::get($data['page'], 'list_empty'); ?></div>
    <?php endif;