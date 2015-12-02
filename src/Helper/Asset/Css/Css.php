<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Asset\Css;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\Asset\AbstractAssetHelper;
use Gobline\View\Helper\Asset\MinifierInterface;
use Gobline\Mediator\EventDispatcherInterface;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Css extends AbstractAssetHelper implements ViewHelperInterface
{
    private $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        Environment $environment,
        MinifierInterface $minifier
    ) {
        parent::__construct($environment, $minifier);
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getName()
    {
        return 'css';
    }

    public function __invoke($path, $ieConditionalComment = null)
    {
        $this->asset = new Style($path, $ieConditionalComment);
        $this->eventDispatcher->addSubscriber($this);

        return $this;
    }

    protected function printReference($path)
    {
        echo '<link rel="stylesheet" href="'.$path."\">\n";
    }

    protected function printInternalContent($data)
    {
        echo '<style>'.$data."</style>\n";
    }

    public function onHeadStylesheets()
    {
        $this->printAsset();
    }

    public function getSubscribedEvents()
    {
        return [
            'headStylesheets' => 'onHeadStylesheets',
        ];
    }
}
