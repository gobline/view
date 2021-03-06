<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Identity;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\Auth\CurrentUserInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Identity implements ViewHelperInterface
{
    private $user;

    public function __construct(CurrentUserInterface $user)
    {
        $this->user = $user;
    }

    public static function getName()
    {
        return 'identity';
    }

    public function __get($property)
    {
        return $this->getProperty($property);
    }

    public function isAuthenticated()
    {
        return $this->user->isAuthenticated();
    }

    public function getId()
    {
        return $this->user->getId();
    }

    public function getLogin()
    {
        return $this->user->getLogin();
    }

    public function getRole()
    {
        return $this->user->getRole();
    }

    public function hasProperty($name)
    {
        return $this->user->hasProperty($name);
    }

    public function getProperty(...$args)
    {
        return $this->user->getProperty(...$args);
    }

    public function getProperties()
    {
        return $this->user->getProperties();
    }

    public function __toString()
    {
        return $this->user->getLogin();
    }
}
