<?php

namespace TobiasOlry\Talkly\Doctrine;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Pimple;

/**
 * References Doctrine connections and entity/document managers.
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class ManagerRegistry extends AbstractManagerRegistry
{
    /**
     * @var Pimple
     */
    protected $container;

    /**
     *
     * @param string $name
     * @return mixed
     */
    protected function getService($name)
    {
        return $this->container[$name];
    }

    /**
     *
     * @param string $name
     */
    protected function resetService($name)
    {
        unset($this->container[$name]);
    }

    /**
     *
     * @param string $alias
     */
    public function getAliasNamespace($alias)
    {
        throw new \BadMethodCallException('Namespace aliases not supported.');
    }

    /**
     *
     * @param Pimple $container
     */
    public function setContainer(Pimple $container)
    {
        $this->container = $container;
    }
}
