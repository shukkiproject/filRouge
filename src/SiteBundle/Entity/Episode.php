<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Episode
 *
 * @ORM\Table(name="episode")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\EpisodeRepository")
 */
class Episode
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
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="episodes")
     */
    private $series;

    /**
     * @var int
     *
     * @ORM\Column(name="season", type="integer")
     */
    private $season;

    /**
     * @var int
     *
     * @ORM\Column(name="episode", type="integer")
     */
    private $episode;

    /**
     * @var text
     *
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var text
     *
     * @ORM\Column(name="synopsis", type="text", length=255)
     */
    private $synopsis;

    /**
     * @var string
     * @ORM\ManyToMany(targetEntity="User", mappedBy="episodesViewed")
     * 
     */
    private $viewedBy;


    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="episode", cascade={"remove"})
     */
    private $comments;

    /**
     * @var boolean
     *
     * @ORM\Column(name="flagged", type="boolean", nullable=true)
     */
    private $flagged;


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
     * Set series
     *
     * @param string $series
     *
     * @return EpisodeEn
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
     * Set season
     *
     * @param string $season
     *
     * @return EpisodeEn
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return string
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return EpisodeEn
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get synopsis
     *
     * @return string
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->viewedBy = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add viewedBy
     *
     * @param \SiteBundle\Entity\User $viewedBy
     *
     * @return Episode
     */
    public function addViewedBy(\SiteBundle\Entity\User $viewedBy)
    {
        $this->viewedBy[] = $viewedBy;

        return $this;
    }

    /**
     * Remove viewedBy
     *
     * @param \SiteBundle\Entity\User $viewedBy
     */
    public function removeViewedBy(\SiteBundle\Entity\User $viewedBy)
    {
        $this->viewedBy->removeElement($viewedBy);
    }

    /**
     * Get viewedBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getViewedBy()
    {
        return $this->viewedBy;
    }

    /**
     * Add comment
     *
     * @param \SiteBundle\Entity\Comment $comment
     *
     * @return Episode
     */
    public function addComment(\SiteBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \SiteBundle\Entity\Comment $comment
     */
    public function removeComment(\SiteBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Episode
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set flagged
     *
     * @param boolean $flagged
     *
     * @return Episode
     */
    public function setFlagged($flagged)
    {
        $this->flagged = $flagged;

        return $this;
    }

    /**
     * Get flagged
     *
     * @return boolean
     */
    public function getFlagged()
    {
        return $this->flagged;
    }

    /**
     * Set episode
     *
     * @param integer $episode
     *
     * @return Episode
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return integer
     */
    public function getEpisode()
    {
        return $this->episode;
    }
}
