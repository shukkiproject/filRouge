<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Season
 *
 * @ORM\Table(name="season")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\SeasonRepository")
 */
class Season
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
     * @var text
     *
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="seasons")
     */
    private $series;

    /**
     * @var int
     *
     * @ORM\Column(name="season", type="integer", unique=true)
     */
    private $season;

    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="season", cascade={"remove"})
     */
    private $episodes;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="season", cascade={"remove"})
     */
    private $comments;

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
     * Set season
     *
     * @param integer $season
     *
     * @return Season
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return int
     */
    public function getSeason()
    {
        return $this->season;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->episodes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set series
     *
     * @param \SiteBundle\Entity\Series $series
     *
     * @return Season
     */
    public function setSeries(\SiteBundle\Entity\Series $series = null)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return \SiteBundle\Entity\Series
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Add episode
     *
     * @param \SiteBundle\Entity\Episode $episode
     *
     * @return Season
     */
    public function addEpisode(\SiteBundle\Entity\Episode $episode)
    {
        $this->episodes[] = $episode;

        return $this;
    }

    /**
     * Remove episode
     *
     * @param \SiteBundle\Entity\Episode $episode
     */
    public function removeEpisode(\SiteBundle\Entity\Episode $episode)
    {
        $this->episodes->removeElement($episode);
    }

    /**
     * Get episodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }

    public function __toString() {
        return strval($this->season);
    }
}
