<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Asset\Collection;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\Asset\MinifierInterface;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class CssCollectionFactory implements ViewHelperInterface
{
    private $collections = [];
    private $eventDispatcher;
    private $minifier;
    private $environment;

    public function __construct(
        ViewEventDispatcher $eventDispatcher,
        Environment $environment,
        MinifierInterface $minifier
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->minifier = $minifier;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'cssCollection';
    }

    public function __invoke($path, $attributes = [])
    {
        if (!isset($this->collections[$path])) {
            $this->collections[$path] = new CssCollection(
                new Collection($path, 'head', $attributes),
                $this->eventDispatcher,
                $this->environment,
                $this->minifier);
        }

        return $this->collections[$path];
    }
}
