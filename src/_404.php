<?http_response_code(404);?>
<h4>
<?php
$s = $_SERVER['REDIRECT_URL'];
print_r("404 page not found $s");
?>
</h4>