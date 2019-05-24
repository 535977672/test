<?php
$result = apache_request_headers();
//$result['Rrrr'] = urldecode($result['Rrrr']);
$result['Rrrr'] = base64_decode($result['Rrrr']);
echo json_encode($result, JSON_UNESCAPED_UNICODE);
exit;