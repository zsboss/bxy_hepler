<?php

declare(strict_types=1);

namespace Bxy\Helper\YiiSoft;

class ObjectHelper
{
    /**
     * ObjectHelper constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            $this->configure($this, $config);
        }
        $this->init();
    }

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
    }

    /**
     * @param $object
     * @param $properties
     *
     * @return mixed
     */
    public function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        return $object;
    }

    /**
     * getter
     *
     * @param $name
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new \RuntimeException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }

        throw new \RuntimeException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * setter
     *
     * @param $name
     * @param $value
     *
     * @throws \RuntimeException
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new \RuntimeException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new \RuntimeException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }
}
