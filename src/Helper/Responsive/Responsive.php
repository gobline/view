<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Responsive;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Responsive extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $environment;

    public function __construct(
        Environment $environment,
        ViewEventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'responsive';
    }

    public function __invoke($responsive = true)
    {
        if ($this->environment->isSubRequest()) {
            return;
        }

        if ($responsive) {
            $this->eventDispatcher->addSubscriber($this);
        } else {
            $this->eventDispatcher->removeSubscriber($this);
        }
    }

    public function onMeta()
    {
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
    }

    public function getSubscribedEvents()
    {
        return [
            'meta' => 'onMeta',
        ];
    }
}
