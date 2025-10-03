<?php
function includeWithCheck($filePath) {
    if (file_exists($filePath)) {
        include_once $filePath;
        return true;
    } else {
        error_log("Error: No se puede encontrar el archivo: " . $filePath);
        echo "<div class='alert alert-danger'>Error: No se puede encontrar un archivo necesario.</div>";
        return false;
    }
}

function includeHeader() {
    $headerPath = __DIR__ . '/../layouts/header.php';
    return includeWithCheck($headerPath);
}

function includeFooter() {
    $footerPath = __DIR__ . '/../layouts/footer.php';
    return includeWithCheck($footerPath);
}
?>
