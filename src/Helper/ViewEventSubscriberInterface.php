<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper;

use Gobline\View\View;
use Gobline\Mediator\EventSubscriberInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
interface ViewEventSubscriberInterface extends EventSubscriberInterface
{
    public function onHtmlAttributes();

    public function onHeadOpened();

    public function onHeadStylesheets();

    public function onHeadScripts();

    public function onBodyAttributes();

    public function onBodyOpened();

    public function onBodyScripts();
}
