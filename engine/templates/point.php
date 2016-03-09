<!-- Item -->
<div class="ps-item ps-point">

    <!-- Form -->
    <form method="post">
        <table>
            <!-- Title -->
            <tr>
                <td colspan="2">
                    <div class="ps-point-title"><?php echo Language::get($data['page'], 'title'); ?></div>
                </td>
            </tr>
            <!-- Columns -->
            <tr>
                <td>
                    <label for="ps-point-name"><?php echo Language::get($data['page'], 'name'); ?>:</label>
                </td>
                <td>
                    <input type="text" value="<?php echo $response['data']['name']; ?>" name="data[name]" autocomplete="off" id="ps-point-name" />
                </td>
            </tr>
            <?php if($data['page'] == 'items' && !empty($data['properties'])): ?>
            <tr>
                <td colspan="2">
                    <h2 class="ps-point-parameters ps-point-title">
                        Параметры
                    </h2>
                </td>
            </tr>
            <?php foreach ($data['properties'] as $k=>$v): ?>
            <!-- Properties -->
            <tr>
                <td>
                    <label for="ps-point-properties-<?php echo $k; ?>"><?php echo $v['name']; ?>:</label>
                </td>
                <td>
                    <input type="text" value="<?php echo isset($response['data']['properties'][$k]['name']) ? $response['data']['properties'][$k]['name'] : ''; ?>" name="data[properties][<?php echo $k; ?>]" id="ps-point-properties-<?php echo $k; ?>" />
                </td>
            </tr>
            <?php endforeach; endif; ?>
            <!-- Submit -->
            <tr>
                <td colspan="2">
                    <div class="ps-point-submit">
                        <?php if ($data['type'] != 'insert'): ?>
                        <input type="submit" name="update" value="<?php echo Language::get('main', 'update'); ?>" />
                        <input type="submit" name="delete" value="<?php echo Language::get('main', 'delete'); ?>" />
                        <?php else: ?>
                        <input type="submit" name="insert" value="<?php echo Language::get('main', 'insert'); ?>" />
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
