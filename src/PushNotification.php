<?php

namespace App\Src;

use App\Src\Utils\Environment;
use Exception;

class PushNotification
{
    protected string $title;
    protected string $body;
    protected string $alert = 'New message in app';
    protected string $sound = 'default';
    protected string $priority = 'high';
    protected bool $content_available = true;

    protected $url = 'https://fcm.googleapis.com/fcm/send';

    public function __construct(string $title, string $body)
    {
        try {
            (new Environment(''))->load();

            $this->url = getenv('FCM_URL');
        } catch (Exception $exception) {
            throw $exception;
        }

        $this->title = $title;
        $this->body = $body;
    }

    public function getNotification(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'alert' => $this->alert,
            'sound' => $this->sound,
        ];
    }

    public function getData(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'priority' => $this->priority,
            'content_available' => true,
        ];
    }

    public function getHeaders(): array
    {
        return [
            'Authorization: key=' . getenv('SERVER_KEY'),
            'Content-Type: application/json'
        ];
    }

    public function setAlert(string $alert): PushNotification
    {
        $this->alert = $alert;
        return $this;
    }
    
    public function setBody(): string
    {
        return json_encode([
            'to' => 'f07855o3FWROSyxSvI5rWr:APA91bF5_rYnk-AaeuatRfMnH3kLtOHLs9-KWnYHhb0BU5pbsLI2Hs4I4J5TgatkXVi9rxqSgq2a_y_HdO5CfOynAP6F5EQJLahIuD1Bu5LZpQRLjy5yeo6CxmjP7c0K2Q5A0hX48MSj',
            'notification' => $this->getNotification(),
            'data' => $this->getData(),
            'priority' => 10,
        ]);
    }

    public function send(): string
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $this->url);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_PROXY_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_POSTFIELDS, $this->setBody());

        $response = curl_exec($request);

        curl_close($request);

        return $response;
    }
}
