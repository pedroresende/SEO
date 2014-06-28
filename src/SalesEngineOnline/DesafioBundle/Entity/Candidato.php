<?php

namespace SalesEngineOnline\DesafioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Candidato
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Candidato
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=255)
     */
    private $telefone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nascimento", type="date")
     */
    private $nascimento;

    /**
     * @var integer
     *
     * @ORM\Column(name="localidade", type="integer")
     */
    private $localidade;

    /**
     * @var File
     *
     * @ORM\Column(name="fotografia", type="string", length=255, nullable=false)
     */
    private $fotografia;

    /**
     * @var string
     *
     * @ORM\Column(name="curriculum", type="string", length=255, nullable=false)
     */
    private $curriculum;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_amigo", type="string", length=255, nullable=true)
     */
    private $emailAmigo;


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
     * Set nome
     *
     * @param string $nome
     * @return Candidato
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set telefone
     *
     * @param integer $telefone
     * @return Candidato
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get telefone
     *
     * @return integer 
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set nascimento
     *
     * @param \DateTime $nascimento
     * @return Candidato
     */
    public function setNascimento($nascimento)
    {
        $this->nascimento = $nascimento;

        return $this;
    }

    /**
     * Get nascimento
     *
     * @return \DateTime 
     */
    public function getNascimento()
    {
        return $this->nascimento;
    }

    /**
     * Set localidade
     *
     * @param integer $localidade
     * @return Candidato
     */
    public function setLocalidade($localidade)
    {
        $this->localidade = $localidade;

        return $this;
    }

    /**
     * Get localidade
     *
     * @return integer 
     */
    public function getLocalidade()
    {
        return $this->localidade;
    }

    /**
     * Set fotografia
     *
     * @param string $fotografia
     * @return Candidato
     */
    public function setFotografia($fotografia)
    {
        $this->fotografia = $fotografia;

        return $this;
    }

    /**
     * Get fotografia
     *
     * @return string 
     */
    public function getFotografia()
    {
        return $this->fotografia;
    }

    /**
     * Set curriculum
     *
     * @param string $curriculum
     * @return Candidato
     */
    public function setCurriculum($curriculum)
    {
        $this->curriculum = $curriculum;

        return $this;
    }

    /**
     * Get curriculum
     *
     * @return string 
     */
    public function getCurriculum()
    {
        return $this->curriculum;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Candidato
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailAmigo
     *
     * @param string $emailAmigo
     * @return Candidato
     */
    public function setEmailAmigo($emailAmigo)
    {
        $this->emailAmigo = $emailAmigo;

        return $this;
    }

    /**
     * Get emailAmigo
     *
     * @return string 
     */
    public function getEmailAmigo()
    {
        return $this->emailAmigo;
    }
}
