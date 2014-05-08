<?php
// src/Apu/UserBundle/Entity/User.php

namespace Apu\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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

	public function __construct()
	{
		parent::__construct();
		// your own logic
		$this->roles = array('ROLE_USER');
		$this->apuntes = new ArrayCollection();
		$this->comentarios = new ArrayCollection();
	}
	
	/**
	 * @ORM\Column(type="integer",options={"default":30})
	 */
	protected $puntos = 30;

	/**
	 * @ORM\OneToMany(targetEntity="Apu\ApuntesBundle\Entity\Apunte", mappedBy="user")
	 */
	protected $apuntes;
	
	/**
	 * @ORM\OneToMany(targetEntity="Apu\ApuntesBundle\Entity\Comentario", mappedBy="user")
	 */
	protected $comentarios;

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
     * Set puntos
     *
     * @param integer $puntos
     * @return User
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;

        return $this;
    }

    /**
     * Get puntos
     *
     * @return integer 
     */
    public function getPuntos()
    {
        return $this->puntos;
    }

    /**
     * Add apuntes
     *
     * @param \Apu\ApuntesBundle\Entity\Apunte $apuntes
     * @return User
     */
    public function addApunte(\Apu\ApuntesBundle\Entity\Apunte $apuntes)
    {
        $this->apuntes[] = $apuntes;

        return $this;
    }

    /**
     * Remove apuntes
     *
     * @param \Apu\ApuntesBundle\Entity\Apunte $apuntes
     */
    public function removeApunte(\Apu\ApuntesBundle\Entity\Apunte $apuntes)
    {
        $this->apuntes->removeElement($apuntes);
    }

    /**
     * Get apuntes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApuntes()
    {
        return $this->apuntes;
    }

    /**
     * Add comentarios
     *
     * @param \Apu\ApuntesBundle\Entity\Comentario $comentarios
     * @return User
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
}
