<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\BasePath;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class BasePath implements ViewHelperInterface
{
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'basePath';
    }

    public function __toString()
    {
        return $this->environment->getBasePath();
    }
}
