<?php

namespace Rangkotodotcom\Pushnotif;

use Illuminate\Support\Collection;
use Rangkotodotcom\Pushnotif\Networks\HttpClient;
use Rangkotodotcom\Pushnotif\Validators\NewsFormValidation;
use Rangkotodotcom\Pushnotif\Validators\InformationFormValidation;
use Rangkotodotcom\Pushnotif\Validators\RegisterTokenFormValidation;
use Rangkotodotcom\Pushnotif\Exceptions\InvalidPostNotificationException;
use Rangkotodotcom\Pushnotif\Validators\MasterNotificationFormValidation;
use Rangkotodotcom\Pushnotif\Validators\NotificationJobDeleteFormValidation;
use Rangkotodotcom\Pushnotif\Services\PostNotifications\PostNotificationNews;
use Rangkotodotcom\Pushnotif\Services\PostNotifications\PostNotificationInformation;
use Rangkotodotcom\Pushnotif\Services\PostNotifications\PostNotificationTransaction;


class Pushnotif
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function connect(): bool
    {
        return $this->httpClient->checkConnection();
    }

    public function registerToken(array $data): Collection
    {
        $validated = RegisterTokenFormValidation::validate($data);

        $result = $this->httpClient->sendRequest('POST', '/fcm/token', $validated);

        return collect($result);
    }

    public function getMasterNotification(string $id = null): Collection
    {
        $endpoint = '/notification/master-notification';

        if ($id != null) {
            $endpoint .= "/$id";
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function postMasterNotification(array $data): Collection
    {
        $validated = MasterNotificationFormValidation::validate($data);

        $endpoint = '/notification/master-notification';
        $result = $this->httpClient->sendRequest('POST', $endpoint, $validated);

        return collect($result);
    }

    public function putMasterNotification(string $id, array $data): Collection
    {
        $validated = MasterNotificationFormValidation::validate($data);

        $endpoint = "/notification/master-notification/$id";
        $result = $this->httpClient->sendRequest('PUT', $endpoint, $validated);

        return collect($result);
    }

    public function deleteMasterNotification(string $id): Collection
    {
        $endpoint = "/notification/master-notification/$id";
        $result = $this->httpClient->sendRequest('DELETE', $endpoint);

        return collect($result);
    }

    public function getNews(string $id = null, bool $withClient = true, int $limit = 10, int $offset = 0): Collection
    {
        $endpoint = '/news';

        if ($id != null) {
            $endpoint .= "/$id";
        }

        $params = [
            'client' => 1
        ];

        if ($limit > 0) {
            $params['limit'] = $limit;
            $params['offset'] = $offset;
        }

        if (!$withClient) {
            $params['client'] = 0;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $params);

        return collect($result);
    }

    public function postNews(array $data): Collection
    {
        $validated = NewsFormValidation::validate($data);

        $endpoint = '/news';
        $result = $this->httpClient->sendRequest('POST', $endpoint, $validated);

        return collect($result);
    }

    public function putNews(string $id, array $data): Collection
    {
        $validated = NewsFormValidation::validate($data, 'updated');

        $endpoint = "/news/$id";
        $result = $this->httpClient->sendRequest('PUT', $endpoint, $validated);

        return collect($result);
    }

    public function deleteNews(string $id): Collection
    {
        $endpoint = "/news/$id";
        $result = $this->httpClient->sendRequest('DELETE', $endpoint);

        return collect($result);
    }

    public function getInformation(string $id = null, bool $withClient = true, int $limit = 10, int $offset = 0): Collection
    {
        $endpoint = '/info';

        if ($id != null) {
            $endpoint .= "/$id";
        }

        $params = [
            'client' => 1
        ];

        if ($limit > 0) {
            $params['limit'] = $limit;
            $params['offset'] = $offset;
        }

        if (!$withClient) {
            $params['client'] = 0;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $params);

        return collect($result);
    }

    public function postInformation(array $data): Collection
    {
        $validated = InformationFormValidation::validate($data);

        $endpoint = '/info';
        $result = $this->httpClient->sendRequest('POST', $endpoint, $validated);

        return collect($result);
    }

    public function putInformation(string $id, array $data): Collection
    {
        $validated = InformationFormValidation::validate($data, 'updated');

        $endpoint = "/info/$id";
        $result = $this->httpClient->sendRequest('PUT', $endpoint, $validated);

        return collect($result);
    }

    public function deleteInformation(string $id): Collection
    {
        $endpoint = "/info/$id";
        $result = $this->httpClient->sendRequest('DELETE', $endpoint);

        return collect($result);
    }

    public function countNotification(string $user_id, bool $withClient = true): Collection
    {
        $endpoint = '/notification/count';

        $params = [
            'client'    => 0,
            'user_id'   => $user_id,
        ];

        if ($withClient) {
            $params['client'] = 1;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $params);

        return collect($result);
    }

    public function getNotification(string $user_id, bool $withClient = true, int $limit = 10): Collection
    {
        $endpoint = '/notification';

        $params = [
            'client'    => 0,
            'user_id'   => $user_id,
        ];

        if ($limit > 0) {
            $params['limit'] = $limit;
        }

        if ($withClient) {
            $params['client'] = 1;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $params);

        return collect($result);
    }

    public function getNotificationById(string $id): Collection
    {
        $endpoint = "/info/$id";

        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function postNotification(array $data, int $typePostNotification): Collection
    {
        if ($typePostNotification == 1) {
            $handler = new PostNotificationTransaction($this->httpClient);
            $handler->storePostNotification($data);
            return $handler->getResponse();
        } else if ($typePostNotification == 2) {
            $handler = new PostNotificationNews($this->httpClient);
            $handler->storePostNotification($data);
            return $handler->getResponse();
        } else if ($typePostNotification == 3) {
            $handler = new PostNotificationInformation($this->httpClient);
            $handler->storePostNotification($data);
            return $handler->getResponse();
        }

        throw new InvalidPostNotificationException("Invalid Type Post Notification");
    }

    public function readNotification(string $id): Collection
    {
        $endpoint = "/notification/$id/read";
        $result = $this->httpClient->sendRequest('PUT', $endpoint);

        return collect($result);
    }

    public function deleteJobNotification(array $jobIds): Collection
    {
        $validated = NotificationJobDeleteFormValidation::validate($jobIds);

        $endpoint = '/notification/job/delete';
        $result = $this->httpClient->sendRequest('POST', $endpoint, $validated);

        return collect($result);
    }
}
