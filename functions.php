<?php
/**
 * 生成唯一ID
 */
function generateUniqueId() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $id = '';
    
    for ($i = 0; $i < 8; $i++) {
        $id .= $characters[random_int(0, strlen($characters) - 1)];
    }
    
    return $id;
}
?>    