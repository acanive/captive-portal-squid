<?php

namespace MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avatar
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity
 */
class Avatar
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255, nullable=false)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/avatars/';
    }

    public function upload($bar) {
// the file property can be empty if the field is not required
        if (null === $this->getImage()) {
            return;
        }
        $trozos = explode(".", $this->getImage()->getClientOriginalName());
        $extension = end($trozos);
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        /* $this->getUrlOriginal()->move(
          $this->getUploadDir(), $userID . '_' . $name
          ); */
        $this->getImage()->move(
            $this->getUploadDir(), $bar . '.' . $extension
        );

        // set the path property to the filename where you've saved the file
        //$this->urlOriginal = $this->getUploadDir() . $userID . '_' . $name;
        $this->image = $this->getUploadDir() . $bar . '.' . $extension;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload($file)
    {
        unlink($file);
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Avatar
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
     * Set image
     *
     * @param string $image
     *
     * @return Avatar
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
