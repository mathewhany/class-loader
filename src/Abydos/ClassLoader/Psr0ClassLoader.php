<?php

namespace Abydos\ClassLoader;

class Psr0ClassLoader extends AbstractClassLoader
{
    protected $prefixes = [];
    protected $fallbackDirs = [];

    public function getPrefixes()
    {
        return $this->prefixes;
    }

    public function setPrefixes(array $prefixes)
    {
        $this->prefixes = $prefixes;

        return $this;
    }

    public function addPrefix($prefix, $dirs)
    {
        $this->prefixes[$prefix] = array_unique(array_merge(
            $this->prefixes[$prefix] ?? [],
            (array) $dirs
        ));

        return $this;
    }

    public function addPrefixes(array $prefixes)
    {
        foreach ($prefixes as $prefix => $dir) {
            $this->addPrefix($prefix, $dir);
        }

        return $this;
    }

    public function getFallbackDirs()
    {
        return $this->fallbackDirs;
    }

    public function setFallbackDirs($fallbackDirs)
    {
        $this->fallbackDirs = $fallbackDirs;

        return $this;
    }

    public function addFallbackDir($dir)
    {
        $this->fallbackDirs[] = $dir;

        return $this;
    }

    public function addFallbackDirs(array $dirs)
    {
        foreach ($dirs as $dir) {
            $this->addFallbackDir($dir);
        }

        return $this;
    }

    protected function loadClass($class)
    {
        $classPath = str_replace(['\\', '_'], DIRECTORY_SEPARATOR, $class) . '.php';
        $findFile = function ($dir) use ($classPath) {
            if (is_file($file = $this->normalizeDir($dir) . DIRECTORY_SEPARATOR . $classPath)) {
                return $file;
            }
        };

        foreach ($this->prefixes as $prefix => $dirs) {
            if (strpos($class, $this->normalizeNamespace($prefix)) === 0) {
                foreach ($dirs as $dir) {
                    if ($file = $findFile($dir)) return includeFile($file);
                }
            }
        }

        foreach ($this->fallbackDirs as $dir) {
            if ($file = $findFile($dir)) return includeFile($file);
        }
    }
}