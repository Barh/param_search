<!-- Item -->
<div class="ps-item">
    <!-- Form -->
    <form action="?api=items&type=update&id=<?php echo $data['id']; ?>" method="post">
        <table>
            <!-- Title -->
            <tr>
                <td colspan="2">
                    <div class="ps-item-title"><?php echo Language::get('properties', 'title'); ?></div>
                </td>
            </tr>
            <!-- Columns -->
            <tr>
                <td>
                    <label for="ps-item-name"><?php echo Language::get('properties', 'name'); ?>:</label>
                </td>
                <td>
                    <input type="text" value="<?php echo $response['name']; ?>" name="name" autocomplete="off" id="ps-item-name" />
                </td>
            </tr>
            <!-- Submit -->
            <tr>
                <td colspan="2">
                    <div class="ps-item-submit">
                        <input type="submit" name="edit" value="<?php echo Language::get('properties', 'update'); ?>" />
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>