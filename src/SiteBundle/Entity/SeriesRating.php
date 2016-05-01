<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SeriesRating
 *
 * @ORM\Table(name="series_rating")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\SeriesRatingRepository") @ORM\HasLifecycleCallbacks
 */
class SeriesRating
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="seriesRatings")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="ratings")
     */
    private $series;

    /**
     * @var float
     *
     * @ORM\Column(name="ratings", type="integer", length=255)
     */
    private $ratings;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var datetime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * Constructor
     */
    public function __construct(\SiteBundle\Entity\Series $series)
    {
        $this->series = $series;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return SeriesRating
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set series
     *
     * @param string $series
     *
     * @return SeriesRating
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set ratings
     *
     * @param string $ratings
     *
     * @return SeriesRating
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;

        return $this;
    }

    /**
     * Get ratings
     *
     * @return string
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @ORM\PrePersist
     */
    public function initializeDate()
    {
        $date = new \DateTime('now');
        $this->setDate($date);
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $updateDate = new \DateTime('now');
        
        $this->setUpdateDate($updateDate);
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SeriesRating
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return SeriesRating
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
}
