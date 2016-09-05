<?php

namespace FOS\ElasticaBundle\Serializer;

use JMS\Serializer\SerializationContext;
use Symfony\Component\Serializer\SerializerInterface;
use JMS\Serializer\SerializerInterface as JMSSerializer;

class Callback
{
    protected $serializer;
    protected $groups = array();
    protected $version;
    protected $serializeNull;

    /**
     * @param $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
        if (!method_exists($this->serializer, 'serialize')) {
            throw new \RuntimeException('The serializer must have a "serialize" method.');
        }
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        if (!empty($this->groups) && !$this->serializer instanceof SerializerInterface && !$this->serializer instanceof JMSSerializer) {
            throw new \RuntimeException('Setting serialization groups requires using "JMS\Serializer\Serializer" or "Symfony\Component\Serializer\Serializer"');
        }
    }

    /**
     * @param $version
     */
    public function setVersion($version)
    {
        $this->version = $version;

        if ($this->version && !$this->serializer instanceof JMSSerializer) {
            throw new \RuntimeException('Setting serialization version requires using "JMS\Serializer\Serializer".');
        }
    }

    /**
     * @param $serializeNull
     */
    public function setSerializeNull($serializeNull)
    {
        $this->serializeNull = $serializeNull;

        if (true === $this->serializeNull && !$this->serializer instanceof JMSSerializer) {
            throw new \RuntimeException('Setting null value serialization option requires using "JMS\Serializer\Serializer".');
        }
    }

    /**
     * @param $object
     *
     * @return mixed
     */
    public function serialize($object)
    {
        $context = $this->serializer instanceof JMSSerializer ? SerializationContext::create()->enableMaxDepthChecks() : array();

        if (!empty($this->groups)) {
            if ($context instanceof SerializationContext) {
                $context->setGroups($this->groups);
            } else {
                $context['groups'] = $this->groups;
            }
        }

        if ($this->version) {
            $context->setVersion($this->version);
        }

        if (!is_array($context)) {
          $context->setSerializeNull($this->serializeNull);
        }

        return $this->serializer->serialize($object, 'json', $context);
    }
}
