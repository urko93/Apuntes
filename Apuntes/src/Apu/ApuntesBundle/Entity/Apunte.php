<?php
// src/Apu/ApuntesBundle/Entity/Apunte.php
namespace Apu\ApuntesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="apunte")
 * @ORM\HasLifecycleCallbacks
 */
class Apunte
{
	public function __construct()
	{
		$this->tags = new ArrayCollection();
		$this->comentarios = new ArrayCollection();
	}
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $descripcion;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $nombre;

	/**
	 * @ORM\Column(type="array", length=100)
	 */
	protected $tags;

	/**
	 * @ORM\ManyToOne(targetEntity="Apu\UserBundle\Entity\User",inversedBy="apuntes")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;
   
	
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $contra="asdasdasdasdasd";
	
	/**
	 * @ORM\OneToMany(targetEntity="Apu\ApuntesBundle\Entity\Comentario", mappedBy="apunte")
	 */
	protected $comentarios;
   
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $path;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $originalName;
	
	/**
	 * @Assert\File(maxSize="6000000")
	 */
	protected $file;
	
	protected $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }
	
	/**
	 * Get file.
	 *
	 * @return UploadedFile
	 */
	public function getFile()
	{
		return $this->file;
	}
	
	public function getAbsolutePath()
	{
		return null === $this->path
		? null
		: $this->getUploadRootDir().'/'.$this->path;
	}
	
	public function getWebPath()
	{
		return null === $this->path
		? null
		: $this->getUploadDir().'/'.$this->path;
	}
	
	protected function getUploadRootDir()
	{
		// the absolute directory path where uploaded
		// documents should be saved
		return __DIR__.'/../../../../'.$this->getUploadDir();
	}
	
	protected function getUploadDir()
	{
		// get rid of the __DIR__ so it doesn't screw up
		// when displaying uploaded doc/image in the view.
		return 'uploads/documents';
	}
	
/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
        	$this->originalName = basename($this->getFile()->getClientOriginalName());
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            
            $contra = sha1(uniqid(mt_rand(), true));
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
    	if ($file = $this->getAbsolutePath()) {
    		unlink($file);
    	}
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Apunte
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Apunte
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tags
     *
     * @param array $tags
     * @return Apunte
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Apunte
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set user
     *
     * @param \Apu\UserBundle\Entity\User $user
     * @return Apunte
     */
    public function setUser(\Apu\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Apu\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add comentarios
     *
     * @param \Apu\ApuntesBundle\Entity\Comentario $comentarios
     * @return Apunte
     */
    public function addComentario(\Apu\ApuntesBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \Apu\ApuntesBundle\Entity\Comentario $comentarios
     */
    public function removeComentario(\Apu\ApuntesBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     * @return Apunte
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string 
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set contra
     *
     * @param string $contra
     * @return Apunte
     */
    public function setContra($contra)
    {
        $this->contra = $contra;

        return $this;
    }

    /**
     * Get contra
     *
     * @return string 
     */
    public function getContra()
    {
        return $this->contra;
    }
}
