<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Link;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Link extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $links = [];

    public function __construct(ViewEventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getName()
    {
        return 'link';
    }

    public function __invoke(array $attributes)
    {
        if (!$this->links) {
            $this->eventDispatcher->addSubscriber($this);
        }
        $this->links[] = $attributes;
    }

    public function onHeadLinks()
    {
        foreach ($this->links as $link) {
            echo '<link';
            foreach ($link as $attributeName => $attributeValue) {
                echo ' '.$attributeName.'="'.$attributeValue.'"';
            }
            echo ">\n";
        }
    }

    public function getSubscribedEvents()
    {
        return [
            'headLinks' => 'onHeadLinks',
        ];
    }
}
