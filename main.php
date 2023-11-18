<?php

function total($path) {
    $size = 0;
    $items = scandir($path);
    foreach ($items as $item) {
        if ($item != '.' && $item != '..') {
            if (is_file($item)) {
                $size += filesize($item);
            } elseif (is_dir($item)) {
                $size += total($path . '\\' . $item);
            }
        }
    }
    return $size;
}

function remove($real) {
    $files = $size = 0;
    $items = scandir($real);
    foreach ($items as $item) {
        if ($item != '.' && $item != '..') {
            $path = realpath($real . '\\' . $item);
            if (is_file($path)) {
                $size += filesize($path);
                if (unlink($path)) {
                    $files++;
                    echo 'Removed >>> ' . $path . '<br>';
                } else {
                    $size -= filesize($path);
                    echo 'Unable to remove >>> ' . $path . '<br>';
                }
            } elseif (is_dir($path)) {
                $fls = scandir($path);
                if ($fls == 2) {
                    rmdir($path);
                    echo 'Removed >>> ' . $path . '<br>';
                    $files++;
                } else {
                    $size += remove($path);
                    rmdir($path);
                    echo 'Removed >>> ' . $path . '<br>';
                }
            }
        }
    }
    echo 'Removed >>> ' . $files . ' items<br>';
    return $size;
}

$temp = sys_get_temp_dir();
$items = scandir($temp);
$total = total($temp);
if (is_writable($temp)) {
    $size = remove($temp);
} else {
    die('ERROR ==> User does not have the necessary permissions to delete temp files.');
}

echo '<br><br>[✓] Successfully removed temp items.';

echo '<br><br>[+] Total size removed | ' . $size . '/' . $total;

?>