<?php
require_once __DIR__ . '/../assets/vendor/autoload.php';
require_once 'settings.php';
require_once 'functions.php';

$responseData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $formName = $_POST["name-form"];
    $body = <<<EOD
<!DOCTYPE html>
<html lang="ru">
<head>
<title>$formName</title>
</head>
<body>
<div style="background-color: #ffffff; padding-top: 30px; padding-bottom: 25px;">
<div style="max-width: 640px; margin: 0 auto; box-shadow: 0px 1px 5px rgba(0,0,0,0.1); overflow: hidden; border-top: 4px solid #139401; background: #fff;">
<table style="width: 640px;" role="presentation" border="0" width="640" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td style="padding: 20px;">
<span style="font-size: 14pt;"><strong><span style="color: #333232;">$formName</span></strong></span>
<p><span style="font-family: &rsquo;proxima_nova_rgregular&rsquo;, Helvetica; font-weight: normal; font-size: 12pt; color: #424242;"><span style="color: #52b505;"><strong>Детали отправителя:</strong></span><br /><span style="color: #262626;">Имя</span>: <strong>$name</strong><br />Телефон: <strong><a href="tel:$phone">$phone</a></strong></span></p>
<p><span style="font-family: &rsquo;proxima_nova_rgregular&rsquo;, Helvetica; font-weight: normal; font-size: 12pt; color: #424242;"><span style="color: #52b505;">
</td>
</tr>
</tbody>
</table>
</div>
</div>
</body>
</html>
EOD;
    if (!empty($name) && !empty($phone)) {

        if (send_mail($mail_settings_prod, 'Заголовок письма', $body)) {
            $responseData['msg'] = "Письмо отправлено";
            $responseData['msg_type'] = "success";
        } else {
            $responseData['msg'] = "Ошибка: письмо не отправлено";
            $responseData['msg_type'] = "error";
        }
    } else {
        $responseData = [
            'msg' => "Ошибка: Недостаточно данных",
            'msg_type' => "error",
            'data' => [
                'name' => $name,
                'phone' => $phone,
            ],
        ];
    }
} else {
    $responseData['msg'] = "Ошибка: Неверный метод запроса";
    $responseData['msg_type'] = "error";
}

// Отправляем данные в формате JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
