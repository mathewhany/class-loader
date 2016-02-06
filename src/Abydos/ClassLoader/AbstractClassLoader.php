<?php

namespace Abydos\ClassLoader;

abstract class AbstractClassLoader
{
    abstract protected function loadClass($class);

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);

        return $this;
    }

    public function unregister()
    {
        spl_autoload_unregister([$this, 'loadClass']);

        return $this;
    }

    protected function normalizeNamespace($namespace)
    {
        return $namespace[0] == '\\'
            ? substr($namespace, 1)
            : $namespace;
    }

    protected function normalizeDir($dir)
    {
        return in_array(substr($dir, -1), ['\\', '/'])
            ? substr($dir, 0, -1)
            : $dir;
    }
}

function includeFile($file)
{
    return include $file;
}