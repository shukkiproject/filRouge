<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Series
 *
 * @ORM\Table(name="series")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\SeriesRepository")
 */
class Series
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="Season", mappedBy="series", cascade={"remove"})
     */
    private $seasons;

    /**
     * @var string
     *
     * @ORM\Column(name="synopsis", type="text", length=255)
     */
    private $synopsis;

    /**
     * @var string
     *
     * @ORM\Column(name="poster", type="string", length=255)
     */
    private $poster;

    /**
     * @var string
     *
     * @ORM\Column(name="persons", type="string", length=255)
     */
    private $persons;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="SeriesRating", mappedBy="series", cascade={"remove"})
     */
    private $ratings;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="series", cascade={"remove"})
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="seriesFollowed", cascade={"remove"})
     */
    private $followedBy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="validated", type="boolean")
     */
    private $validated;

    /**
     * @var int
     *
     * @ORM\Column(name="oldId", type="integer", nullable=true)
     */
    private $oldId;

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
     * Set name
     *
     * @param string $name
     *
     * @return SeriesFr
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set episode
     *
     * @param string $episode
     *
     * @return SeriesFr
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return string
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return SeriesFr
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
     * Set poster
     *
     * @param string $poster
     *
     * @return SeriesFr
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set persons
     *
     * @param string $persons
     *
     * @return SeriesFr
     */
    public function setPersons($persons)
    {
        $this->persons = $persons;

        return $this;
    }

    /**
     * Get persons
     *
     * @return string
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return SeriesFr
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set ratings
     *
     * @param string $ratings
     *
     * @return SeriesFr
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
     * Set followedBy
     *
     * @param string $followedBy
     *
     * @return Series
     */
    public function setFollowedBy($followedBy)
    {
        $this->followedBy = $followedBy;

        return $this;
    }

    /**
     * Get followedBy
     *
     * @return string
     */
    public function getFollowedBy()
    {
        return $this->followedBy;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->episodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ratings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->followedBy = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add episode
     *
     * @param \SiteBundle\Entity\Episode $episode
     *
     * @return Series
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

    /**
     * Add rating
     *
     * @param \SiteBundle\Entity\SeriesRating $rating
     *
     * @return Series
     */
    public function addRating(\SiteBundle\Entity\SeriesRating $rating)
    {
        $this->ratings[] = $rating;

        return $this;
    }

    /**
     * Remove rating
     *
     * @param \SiteBundle\Entity\SeriesRating $rating
     */
    public function removeRating(\SiteBundle\Entity\SeriesRating $rating)
    {
        $this->ratings->removeElement($rating);
    }

    /**
     * Add comment
     *
     * @param \SiteBundle\Entity\Comment $comment
     *
     * @return Series
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
     * Add followedBy
     *
     * @param \SiteBundle\Entity\User $followedBy
     *
     * @return Series
     */
    public function addFollowedBy(\SiteBundle\Entity\User $followedBy)
    {
        $this->followedBy[] = $followedBy;

        return $this;
    }

    /**
     * Remove followedBy
     *
     * @param \SiteBundle\Entity\User $followedBy
     */
    public function removeFollowedBy(\SiteBundle\Entity\User $followedBy)
    {
        $this->followedBy->removeElement($followedBy);
    }

    public function __toString() {
        return $this->name;
    }


    /**
     * Set validated
     *
     * @param boolean $validated
     *
     * @return Series
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set oldId
     *
     * @param integer $oldId
     *
     * @return Series
     */
    public function setOldId($oldId)
    {
        $this->oldId = $oldId;

        return $this;
    }

    /**
     * Get oldId
     *
     * @return integer
     */
    public function getOldId()
    {
        return $this->oldId;
    }

    /**
     * Set flagged
     *
     * @param boolean $flagged
     *
     * @return Series
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
     * Add season
     *
     * @param \SiteBundle\Entity\Season $season
     *
     * @return Series
     */
    public function addSeason(\SiteBundle\Entity\Season $season)
    {
        $this->seasons[] = $season;

        return $this;
    }

    /**
     * Remove season
     *
     * @param \SiteBundle\Entity\Season $season
     */
    public function removeSeason(\SiteBundle\Entity\Season $season)
    {
        $this->seasons->removeElement($season);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeasons()
    {
        return $this->seasons;
    }
}
