<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\ElasticaBundle\Configuration\Search;

/**
 * Code
 *
 * @ORM\Table(name="code")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CodeRepository")
 * @ORM\HasLifecycleCallbacks
 * @Search(repositoryClass="AppBundle\Entity\SearchRepository\CodeRepository")
 */
class Code
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="streetName", type="string", length=255)
     */
    private $streetName;

    /**
     * @var string
     *
     * @ORM\Column(name="cityName", type="string", length=255)
     */
    private $cityName;

    /**
     * @var string
     *
     * @ORM\Column(name="provinceName", type="string", length=255)
     */
    private $provinceName;

    /**
     * @var string
     *
     * @ORM\Column(name="regionName", type="string", length=255)
     */
    private $regionName;


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
     * Set code
     *
     * @param string $code
     *
     * @return Code
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set streetName
     *
     * @param string $streetName
     *
     * @return Code
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * Get streetName
     *
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * Get raw streetName
     * 
     * @return string
     */
    public function rawStreetName() 
    {
        return $this->streetName;
    }
    
    /**
     * Set cityName
     *
     * @param string $cityName
     *
     * @return Code
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName
     *
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }
    
    /**
     * Get raw city name
     * 
     * @return string
     */
    public function getRawCityName() {
        
        return $this->cityName;
        
    }
    

    /**
     * Set provinceName
     *
     * @param string $provinceName
     *
     * @return Code
     */
    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;

        return $this;
    }

    /**
     * Get provinceName
     *
     * @return string
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * Set regionName
     *
     * @param string $regionName
     *
     * @return Code
     */
    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;

        return $this;
    }

    /**
     * Get regionName
     *
     * @return string
     */
    public function getRegionName()
    {
        return $this->regionName;
    }
}

