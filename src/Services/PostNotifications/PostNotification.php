<?php

namespace Rangkotodotcom\Pushnotif\Services\PostNotifications;

use Illuminate\Support\Collection;

interface PostNotification
{
    /**
     * @param array $data
     * @return $this
     */
    public function storePostNotification(array $data): self;


    /**
     * @return Collection
     */
    public function getResponse(): Collection;
}
