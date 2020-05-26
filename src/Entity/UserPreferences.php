<?php

namespace App\Entity;

use App\Repository\UserPreferencesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserPreferencesRepository::class)
 */
class UserPreferences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $local;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param mixed $local
     */
    public function setLocal($local): void
    {
        $this->local = $local;
    }
}
