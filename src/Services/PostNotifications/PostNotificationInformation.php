<?php

namespace Rangkotodotcom\Pushnotif\Services\PostNotifications;

use Illuminate\Support\Collection;
use Rangkotodotcom\Pushnotif\Networks\HttpClient;
use Rangkotodotcom\Pushnotif\Services\PostNotifications\PostNotification;
use Rangkotodotcom\Pushnotif\Validators\NotificationInformationFormValidation;

class PostNotificationInformation implements PostNotification
{

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var object
     */
    protected object $response;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }


    /**
     * @param array $data
     * @return PostNotification
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storePostNotification(array $data): PostNotification
    {
        $validated = NotificationInformationFormValidation::validate($data);

        $this->response = $this->httpClient->sendRequest('POST', '/notification', $validated);

        return $this;
    }


    /**
     * @return Collection
     */
    public function getResponse(): Collection
    {
        return collect($this->response);
    }
}
