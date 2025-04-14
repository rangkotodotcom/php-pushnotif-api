<?php

namespace Rangkotodotcom\Pushnotif\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Rangkotodotcom\Pushnotif connect()
 * @method static \Rangkotodotcom\Pushnotif registerToken(array $data)
 * @method static \Rangkotodotcom\Pushnotif getMasterNotification(string $id = null)
 * @method static \Rangkotodotcom\Pushnotif postMasterNotification(array $data)
 * @method static \Rangkotodotcom\Pushnotif putMasterNotification(string $id, array $data)
 * @method static \Rangkotodotcom\Pushnotif deleteMasterNotification(string $id)
 * @method static \Rangkotodotcom\Pushnotif getNews(string $id = null, int $limit = 10)
 * @method static \Rangkotodotcom\Pushnotif postNews(array $data)
 * @method static \Rangkotodotcom\Pushnotif putNews(string $id, array $data)
 * @method static \Rangkotodotcom\Pushnotif deleteNews(string $id)
 * @method static \Rangkotodotcom\Pushnotif getInformation()
 * @method static \Rangkotodotcom\Pushnotif postInformation(array $data)
 * @method static \Rangkotodotcom\Pushnotif putInformation(string $id, array $data)
 * @method static \Rangkotodotcom\Pushnotif deleteInformation(string $id)
 * @method static \Rangkotodotcom\Pushnotif countNotification(string $user_id, bool $withClient = true)
 * @method static \Rangkotodotcom\Pushnotif getNotification(string $user_id, bool $withClient = true, int $limit = 10)
 * @method static \Rangkotodotcom\Pushnotif getNotificationById(string $id)
 * @method static \Rangkotodotcom\Pushnotif postNotification(array $data, int $typePostNotification)
 * @method static \Rangkotodotcom\Pushnotif readNotification(string $id)
 * @method static \Rangkotodotcom\Pushnotif deleteJobNotification(array $jobIds)
 *
 * @see \Rangkotodotcom\Pushnotif
 */
class Pushnotif extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pushnotif';
    }
}
