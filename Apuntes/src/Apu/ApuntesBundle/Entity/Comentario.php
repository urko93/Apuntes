<?php
// src/Apu/ApuntesBundle/Entity/Comentario.php
namespace Apu\ApuntesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="comentario")
 */
class Comentario
{
	public function __construct()
	{

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
	protected $comentario;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $puntuacion;


	/**
	 * @ORM\ManyToOne(targetEntity="Apu\UserBundle\Entity\User", inversedBy="comentarios")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;

	/**
	 * @ORM\ManyToOne(targetEntity="Apu\ApuntesBundle\Entity\Apunte", inversedBy="comentarios")
	 * @ORM\JoinColumn(name="apunte_id", referencedColumnName="id")
	 */
	protected $apunte;

   

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
     * Set comentario
     *
     * @param string $comentario
     * @return Comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set puntuacion
     *
     * @param integer $puntuacion
     * @return Comentario
     */
    public function setPuntuacion($puntuacion)
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    /**
     * Get puntuacion
     *
     * @return integer 
     */
    public function getPuntuacion()
    {
        return $this->puntuacion;
    }

    /**
     * Set apunte
     *
     * @param \Apu\ApuntesBundle\Entity\Apunte $apunte
     * @return Comentario
     */
    public function setApunte(\Apu\ApuntesBundle\Entity\Apunte $apunte = null)
    {
        $this->apunte = $apunte;

        return $this;
    }

    /**
     * Get apunte
     *
     * @return \Apu\ApuntesBundle\Entity\Apunte 
     */
    public function getApunte()
    {
        return $this->apunte;
    }

    /**
     * Set user
     *
     * @param \Apu\UserBundle\Entity\User $user
     * @return Comentario
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
}
