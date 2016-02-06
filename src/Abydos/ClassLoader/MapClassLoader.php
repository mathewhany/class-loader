<?php

namespace Abydos\ClassLoader;

class MapClassLoader extends AbstractClassLoader
{
    protected $classMap = [];

    public function addClassMap(array $classMap) {
        $this->classMap = array_unique(array_merge($this->classMap, $classMap));

        return $this;
    }

    protected function loadClass($class)
    {
        if (isset($this->classMap[$class])) {
            return includeFile($this->classMap[$class]);
        } elseif (isset($this->classMap['\\' . $class])) {
            return includeFile($this->classMap['\\' . $class]);
        }
    }
}