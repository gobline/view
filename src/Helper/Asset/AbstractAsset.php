<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Asset;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
abstract class AbstractAsset
{
    protected $path;
    protected $location;
    protected $isExternal;

    public function __construct($path, $location)
    {
        $this->path = $path;
        $this->location = $location;
        $this->checkIsExternal();
    }

    public function checkIsExternal()
    {
        $this->isExternal = false;
        foreach (['http://', 'https://', '//'] as $prefix) {
            if (strpos($this->path, $prefix) === 0) {
                $this->isExternal = true;
                break;
            }
        }
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        $this->checkIsExternal();
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function isExternal()
    {
        return $this->isExternal;
    }
}
