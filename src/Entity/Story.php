<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoryRepository")
 */
class Story
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "detail"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "detail"})
     */
    private $real_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list", "detail"})
     */
    private $frontend;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list", "detail"})
     */
    private $backend;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list", "detail"})
     */
    private $start_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"detail"})
     */
    private $outside_link;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"detail"})
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRealTitle(): ?string
    {
        return $this->real_title;
    }

    public function setRealTitle(string $real_title): self
    {
        $this->real_title = $real_title;

        return $this;
    }

    public function getFrontend(): ?string
    {
        return $this->frontend;
    }

    public function setFrontend(?string $frontend): self
    {
        $this->frontend = $frontend;

        return $this;
    }

    public function getBackend(): ?string
    {
        return $this->backend;
    }

    public function setBackend(?string $backend): self
    {
        $this->backend = $backend;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->start_at;
    }

    public function setStartAt(?\DateTimeInterface $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getOutsideLink(): ?string
    {
        return $this->outside_link;
    }

    public function setOutsideLink(?string $outside_link): self
    {
        $this->outside_link = $outside_link;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
