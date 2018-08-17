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

use Gobline\View\Helper\Asset\AbstractAsset;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Style extends AbstractAsset
{
    public function __construct($path)
    {
        parent::__construct($path, 'head');
    }
}
