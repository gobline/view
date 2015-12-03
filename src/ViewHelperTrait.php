<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View;

use Gobline\View\Helper\ViewHelperContainer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
trait ViewHelperTrait
{
    private $helperContainer;

    public function setHelperContainer(ViewHelperContainer $helperContainer)
    {
        $this->helperContainer = $helperContainer;
    }

    public function getViewHelpers()
    {
        $array = [];

        if (!$this->helperContainer) {
            return [];
        }

        foreach ($this->helperContainer->keys() as $key) {
            $array[$key::getName()] = new ViewHelperCallable(
                function () use ($key) {
                    return $this->helperContainer->get($key);
                });
        }

        return $array;
    }
}
