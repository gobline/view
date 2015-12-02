<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Flash;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\Http\Request\HttpRequestInterface;
use Gobline\Flash\FlashInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Flash implements ViewHelperInterface
{
    private $flash;

    public function __construct(FlashInterface $flash)
    {
        $this->flash = $flash;
    }

    public static function getName()
    {
        return 'flash';
    }

    public function next($name, $value)
    {
        return $this->flash->next($name, $value);
    }

    public function has($name)
    {
        return $this->flash->has($name);
    }

    public function get(...$args)
    {
        if (count($args) === 1) {
            return $this->flash->get($args[0], '');
        }

        return $this->flash->get(...$args);
    }
}
