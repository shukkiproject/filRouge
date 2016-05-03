<?php

namespace SiteBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\UserRepository") @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="fos_user")
 * @Vich\Uploadable
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="firstname", type="text", length=255)
     * 
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="lastname", type="text", length=255)
     * 
     */
    private $lastname;

    /**
     * @var datetime
     *
     * @ORM\Column(name="birthday", type="date")
     */
    private $birthday;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user", cascade={"remove"})
     * 
     */
    private $comments;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="SeriesRating", mappedBy="user", cascade={"remove"})
     * 
     */
    private $seriesRatings;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="CommentPreference", mappedBy="user", cascade={"remove"})
     * 
     */
    private $commentsPreferences;

    /**
     * @var string
     * @ORM\ManyToMany(targetEntity="Series", inversedBy="followedBy", cascade={"remove"})
     * 
     */
    private $seriesFollowed;

    /**
     * @var string
     * @ORM\ManyToMany(targetEntity="Episode", inversedBy="viewedBy", cascade={"persist"})
     * 
     */
    private $episodesViewed;

    /**
     * @var string
     * @ORM\Column(name="notifications", type="text", length=255, nullable=true)
     * 
     */
    private $notifications;

    /**
     * @var boolean
     * @ORM\Column(name="flagged", type="text", length=255, nullable=true)
     * 
     */
    private $flagged;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     */
    private $friendsWithMe;

    /**
     * @var string
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     */
    private $myFriends;

    //Begin Entities VichUploaderBundle ------------------------------------------------------------------------------------------
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="imageName")
     * @ORM\Column(name="image_file", type="string", length=255, nullable=true)
     *    @Assert\Image(
     *     minWidth = 200,
     *     minHeight = 200,
     *     mimeTypes = "image/*",
     *     maxSize = "2M",
     *     maxSizeMessage = " The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.",
     *     mimeTypesMessage = "This file is not a valid image.",
     *     disallowEmptyMessage = "An empty file is not allowed.",
     *     notFoundMessage =  "The file could not be found.",
     *     notReadableMessage = "The file is not readable.",
     *     uploadIniSizeErrorMessage = "The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.",
     *     uploadErrorMessage = "The file could not be uploaded.", 
     * )
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(name="update_at", type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    //End Entities VichUploaderBundle ------------------------------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
        $this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
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
        $episodesViewed->addViewedBy($this);
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

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    //Begin Methode VichUploaderBundle------------------------------------------------------------------------------------------

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return User
     */
    public function setImageFile(File $image)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Series
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    //End Methode VichUploaderBundle------------------------------------------------------------------------------------------

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add friendsWithMe
     *
     * @param \SiteBundle\Entity\User $friendsWithMe
     *
     * @return User
     */
    public function addFriendsWithMe(\SiteBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \SiteBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\SiteBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriend
     *
     * @param \SiteBundle\Entity\User $myFriend
     *
     * @return User
     */
    public function addMyFriend(\SiteBundle\Entity\User $myFriend)
    {
        $this->myFriends[] = $myFriend;

        return $this;
    }

    /**
     * Remove myFriend
     *
     * @param \SiteBundle\Entity\User $myFriend
     */
    public function removeMyFriend(\SiteBundle\Entity\User $myFriend)
    {
        $this->myFriends->removeElement($myFriend);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyFriends()
    {
        return $this->myFriends;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return User
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
}
