<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Series
 *
 * @ORM\Table(name="series")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\SeriesRepository") @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @UniqueEntity(fields="name", message="This series already exists. ")
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
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
     * @ORM\Column(name="synopsis", type="text", length=65535)
     */
    private $synopsis;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", length=255)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="creator", type="string", length=255)
     */
    private $creator;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="series", cascade={"persist"})
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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="seriesFollowed")
     */
    private $followedBy;

    /**
     * @var text
     *
     * @ORM\Column(name="language", type="text", nullable=false)
     */
    private $language;

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

    //Begin Entities VichUploaderBundle ------------------------------------------------------------------------------------------
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="series_image", fileNameProperty="imageName")
     * @ORM\Column(name="image_file", type="string", length=255)
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
     *     
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
        $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Series
     */
    public function setImageFile(File $image=null)
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


    /**
     * Set language
     *
     * @param string $language
     *
     * @return Series
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Series
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
     * Add person
     *
     * @param \SiteBundle\Entity\Person $person
     *
     * @return Series
     */
    public function addPerson(\SiteBundle\Entity\Person $person)
    {
        $person->addSeries($this);
        $this->persons->add($person);

        return $this;
    }

    /**
     * Remove person
     *
     * @param \SiteBundle\Entity\Person $person
     */
    public function removePerson(\SiteBundle\Entity\Person $person)
    {
        $this->persons->removeElement($person);
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Series
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set creator
     *
     * @param string $creator
     *
     * @return Series
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return string
     */
    public function getCreator()
    {
        return $this->creator;
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
     * @return Series
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
     * @return Series
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
