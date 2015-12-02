<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Description;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\Mediator\EventDispatcherInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Description extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $description;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getName()
    {
        return 'description';
    }

    public function __invoke($description)
    {
        if (!$this->description) {
            $this->eventDispatcher->addSubscriber($this);
        }
        $this->description = (string) $description;
    }

    public function onMeta()
    {
        echo '<meta name="description" content="'.$this->description."\">\n";
    }

    public function getSubscribedEvents()
    {
        return [
            'meta' => 'onMeta',
        ];
    }
}
