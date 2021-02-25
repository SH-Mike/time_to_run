<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 */
class Outing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=OutingType::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $outingType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="float")
     */
    private $distance;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOutingType(): ?OutingType
    {
        return $this->outingType;
    }

    public function setOutingType(?OutingType $outingType): self
    {
        $this->outingType = $outingType;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Function called to get the duration of an Outing
     * Duration is a calculated data, so we won't store it in database
     * 
     * @return float
     */
    public function getDuration(): ?float
    {
        $dateDifference = $this->getEndDate()->diff($this->getStartDate());
        $duration = number_format(($dateDifference->days * 24) + $dateDifference->h + ($dateDifference->i / 60), 2);
        return $duration;
    }

    /**
     * Function called to get the average speed of an Outing
     * Average speed is a calculated data, so we won't store it in database
     * @return float
     */
    public function getAverageSpeed(): ?float
    {
        return $this->getDistance() / $this->getDuration();
    }

    /**
     * Function called to get the average pace of an Outing
     * Average pace is a calculated data, so we won't store it in database
     * @return float
     */
    public function getAveragePace(): ?float
    {
        return round($this->getDuration() * 60 / $this->getDistance(), 2);
    }

    /**
     * Function called to get the duration in the format 00h00
     * @return String
     */
    public function getFormattedDuration(): ?String
    {
        $duration = $this->getDuration();
        return sprintf('%02dh%02d', (int) $duration, round(fmod($duration, 1) * 60));
    }

    /**
     * Function called to get the average pace in the format 00'00"
     * @return String
     */
    public function getFormattedAveragePace(): ?String
    {
        $averageSpeed = round($this->getDuration() * 60 / $this->getDistance(), 2);
        $arrayAverageSpeed = explode(".", $averageSpeed);
        $minutes = $arrayAverageSpeed[0];
        $seconds = (int) round($arrayAverageSpeed[1] * 60 / 100);
        return $minutes . "'" . $seconds . "\"";
    }
}
