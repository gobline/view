<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Meta;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Meta extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $environment;
    private $metas = [];

    public function __construct(
        Environment $environment,
        ViewEventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'meta';
    }

    public function __invoke(array $attributes)
    {
        if ($this->environment->isSubRequest()) {
            return;
        }

        if (!$this->metas) {
            $this->eventDispatcher->addSubscriber($this);
        }
        $this->metas[] = $attributes;
    }

    public function onMeta()
    {
        foreach ($this->metas as $meta) {
            echo '<meta';
            foreach ($meta as $attributeName => $attributeValue) {
                echo ' '.$attributeName.'="'.$attributeValue.'"';
            }
            echo ">\n";
        }
    }

    public function getSubscribedEvents()
    {
        return [
            'meta' => 'onMeta',
        ];
    }
}
