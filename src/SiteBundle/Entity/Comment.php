<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\CommentRepository") @ORM\HasLifecycleCallbacks
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="comments")
     */
    private $series;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="comments")
     */
    private $season;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Episode", inversedBy="comments")
     */
    private $episode;

    /**
     * @var string
     * @ORM\Column(name="title", type="text")
     *
     * 
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string")
     *
     * 
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="CommentPreference", mappedBy="comment", cascade={"remove"})
     */
    private $usersPreferences;

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
     * Set user
     *
     * @param string $user
     *
     * @return Comment
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
     * @return Comment
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set ratings
     *
     * @param string $ratings
     *
     * @return Comment
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
     * Set date
     *
     * @param string $date
     *
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Constructor
     */
    public function __construct(\SiteBundle\Entity\Series $series)
    {
        $this->usersPreferences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->series = $series;
    }

    /**
     * Set episode
     *
     * @param \SiteBundle\Entity\Episode $episode
     *
     * @return Comment
     */
    public function setEpisode(\SiteBundle\Entity\Episode $episode = null)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return \SiteBundle\Entity\Episode
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Add usersPreference
     *
     * @param \SiteBundle\Entity\CommentPreference $usersPreference
     *
     * @return Comment
     */
    public function addUsersPreference(\SiteBundle\Entity\CommentPreference $usersPreference)
    {
        $this->usersPreferences[] = $usersPreference;

        return $this;
    }

    /**
     * Remove usersPreference
     *
     * @param \SiteBundle\Entity\CommentPreference $usersPreference
     */
    public function removeUsersPreference(\SiteBundle\Entity\CommentPreference $usersPreference)
    {
        $this->usersPreferences->removeElement($usersPreference);
    }

    /**
     * Get usersPreferences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersPreferences()
    {
        return $this->usersPreferences;
    }

    public function __toString() {
        return  $this->comment;
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
     * Set flagged
     *
     * @param boolean $flagged
     *
     * @return Comment
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
     * Set season
     *
     * @param \SiteBundle\Entity\Season $season
     *
     * @return Comment
     */
    public function setSeason(\SiteBundle\Entity\Season $season = null)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return \SiteBundle\Entity\Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Comment
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
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Comment
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
