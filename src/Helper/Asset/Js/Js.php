<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Asset\Js;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\Asset\AbstractAssetHelper;
use Gobline\View\Helper\Asset\MinifierInterface;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Js extends AbstractAssetHelper implements ViewHelperInterface
{
    private $eventDispatcher;

    public function __construct(
        ViewEventDispatcher $eventDispatcher,
        Environment $environment,
        MinifierInterface $minifier
    ) {
        parent::__construct($environment, $minifier);
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getName()
    {
        return 'js';
    }

    public function __invoke($path, $location = 'body', $attributes = [])
    {
        $this->asset = new Script($path, $location, $attributes);
        $this->eventDispatcher->addSubscriber($this);

        return $this;
    }

    protected function printReference($path, $attributes)
    {
        echo '<script src="'.$path."\"".($attributes ?: "")."></script>\n";
    }

    protected function printInternalContent($data)
    {
        echo '<script>'.$data."</script>\n";
    }

    public function onHeadScripts()
    {
        if ('head' === $this->asset->getLocation()) {
            $this->printAsset();
        }
    }

    public function onBodyScripts()
    {
        if ('body' === $this->asset->getLocation()) {
            $this->printAsset();
        }
    }

    public function getSubscribedEvents()
    {
        return [
            'headScripts' => 'onHeadScripts',
            'bodyScripts' => 'onBodyScripts',
        ];
    }
}
