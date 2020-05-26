<?php

namespace App\Entity;

use App\Repository\LikeNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeNotificationRepository::class)
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $likedBy;

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    /**
     * @param mixed $likedBy
     */
    public function setLikedBy($likedBy): void
    {
        $this->likedBy = $likedBy;
    }
}
