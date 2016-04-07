<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\CommentRepository")
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
     * @ORM\ManyToOne(targetEntity="Episode", inversedBy="comments")
     */
    private $episode;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text")
     *
     * 
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="CommentPreference", mappedBy="comment")
     */
    private $usersPreferences;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * 
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
    public function __construct()
    {
        $this->usersPreferences = new \Doctrine\Common\Collections\ArrayCollection();
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
}
