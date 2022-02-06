<?php

use Environment;

class PushNotification
{
    protected string $title;
    protected string $body;
    protected string $alert;
    protected string $sound = 'default';
    protected string $priority = 'high';
    protected bool $content_available = true;

    protected $url = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        (new Environment(''))->load();

        $this->url = getenv('FCM_URL');
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
    
    public function setBody(): string
    {
        return json_encode([
            'to' => '',
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
