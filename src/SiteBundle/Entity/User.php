<?php

namespace SiteBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     * 
     */
    private $comments;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="SeriesRating", mappedBy="user")
     * 
     */
    private $seriesRatings;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="CommentPreference", mappedBy="user")
     * 
     */
    private $commentsPreferences;

    /**
     * @var string
     * @ORM\ManyToMany(targetEntity="Series", inversedBy="followedBy")
     * 
     */
    private $seriesFollowed;

    /**
     * @var string
     * @ORM\ManyToMany(targetEntity="Episode", inversedBy="viewedBy")
     * 
     */
    private $episodesViewed;

    /**
     * @var string
     * @ORM\Column(name="notifications", type="text", length=255)
     * 
     */
    private $notifications;

    /**
     * @var boolean
     * @ORM\Column(name="flagged", type="text", length=255)
     * 
     */
    private $flagged;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set notifications
     *
     * @param string $notifications
     *
     * @return User
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;

        return $this;
    }

    /**
     * Get notifications
     *
     * @return string
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set flagged
     *
     * @param string $flagged
     *
     * @return User
     */
    public function setFlagged($flagged)
    {
        $this->flagged = $flagged;

        return $this;
    }

    /**
     * Get flagged
     *
     * @return string
     */
    public function getFlagged()
    {
        return $this->flagged;
    }

    /**
     * Add comment
     *
     * @param \SiteBundle\Entity\Comment $comment
     *
     * @return User
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

    /**
     * Add seriesRating
     *
     * @param \SiteBundle\Entity\SeriesRating $seriesRating
     *
     * @return User
     */
    public function addSeriesRating(\SiteBundle\Entity\SeriesRating $seriesRating)
    {
        $this->seriesRatings[] = $seriesRating;

        return $this;
    }

    /**
     * Remove seriesRating
     *
     * @param \SiteBundle\Entity\SeriesRating $seriesRating
     */
    public function removeSeriesRating(\SiteBundle\Entity\SeriesRating $seriesRating)
    {
        $this->seriesRatings->removeElement($seriesRating);
    }

    /**
     * Get seriesRatings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeriesRatings()
    {
        return $this->seriesRatings;
    }

    /**
     * Add commentsPreference
     *
     * @param \SiteBundle\Entity\CommentPreference $commentsPreference
     *
     * @return User
     */
    public function addCommentsPreference(\SiteBundle\Entity\CommentPreference $commentsPreference)
    {
        $this->commentsPreferences[] = $commentsPreference;

        return $this;
    }

    /**
     * Remove commentsPreference
     *
     * @param \SiteBundle\Entity\CommentPreference $commentsPreference
     */
    public function removeCommentsPreference(\SiteBundle\Entity\CommentPreference $commentsPreference)
    {
        $this->commentsPreferences->removeElement($commentsPreference);
    }

    /**
     * Get commentsPreferences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentsPreferences()
    {
        return $this->commentsPreferences;
    }

    /**
     * Add seriesFollowed
     *
     * @param \SiteBundle\Entity\Series $seriesFollowed
     *
     * @return User
     */
    public function addSeriesFollowed(\SiteBundle\Entity\Series $seriesFollowed)
    {
        $this->seriesFollowed[] = $seriesFollowed;

        return $this;
    }

    /**
     * Remove seriesFollowed
     *
     * @param \SiteBundle\Entity\Series $seriesFollowed
     */
    public function removeSeriesFollowed(\SiteBundle\Entity\Series $seriesFollowed)
    {
        $this->seriesFollowed->removeElement($seriesFollowed);
    }

    /**
     * Get seriesFollowed
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeriesFollowed()
    {
        return $this->seriesFollowed;
    }

    /**
     * Add episodesViewed
     *
     * @param \SiteBundle\Entity\Episode $episodesViewed
     *
     * @return User
     */
    public function addEpisodesViewed(\SiteBundle\Entity\Episode $episodesViewed)
    {
        $this->episodesViewed[] = $episodesViewed;

        return $this;
    }

    /**
     * Remove episodesViewed
     *
     * @param \SiteBundle\Entity\Episode $episodesViewed
     */
    public function removeEpisodesViewed(\SiteBundle\Entity\Episode $episodesViewed)
    {
        $this->episodesViewed->removeElement($episodesViewed);
    }

    /**
     * Get episodesViewed
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodesViewed()
    {
        return $this->episodesViewed;
    }
}
