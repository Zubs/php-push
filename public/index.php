<?php

use App\Src\PushNotification;

require_once __DIR__ . '/../vendor/autoload.php';

$notificationsManager = new PushNotification('New post', 'Ajani Alade has added a new post.');
$notificationsManager->setAlert('Testing this');
echo $notificationsManager->send();
