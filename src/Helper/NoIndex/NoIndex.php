<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\NoIndex;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class NoIndex extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;

    public function __construct(ViewEventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getName()
    {
        return 'noIndex';
    }

    public function __invoke($noIndex = true)
    {
        if ($noIndex) {
            $this->eventDispatcher->addSubscriber($this);
        } else {
            $this->eventDispatcher->removeSubscriber($this);
        }
    }

    public function onMeta()
    {
        echo "<meta name=\"robots\" content=\"noindex, nofollow\">\n";
    }

    public function getSubscribedEvents()
    {
        return [
            'meta' => 'onMeta',
        ];
    }
}
