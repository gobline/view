<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Escape;

use Gobline\View\Helper\ViewHelperInterface;
use Zend\Escaper\Escaper;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Escape implements ViewHelperInterface
{
    private $escaper;

    public function __construct(Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    public static function getName()
    {
        return 'escape';
    }

    public function __invoke($string)
    {
        return $this->html($string);
    }

    public function html($string)
    {
        return $this->escaper->escapeHtml($string);
    }

    public function url($string)
    {
        return $this->escaper->escapeUrl($string);
    }

    public function js($string)
    {
        return $this->escaper->escapeJs($string);
    }

    public function css($string)
    {
        return $this->escaper->escapeCss($string);
    }

    public function htmlAttr($string)
    {
        return $this->escaper->escapeHtml($string);
    }
}
