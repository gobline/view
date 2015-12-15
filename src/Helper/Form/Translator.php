<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Form;

use Gobline\Environment\Environment;
use Gobline\Translator\Translator as TranslatorComponent;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Translator
{
    private $language;
    private $translator;

    public function __construct(TranslatorComponent $translator, Environment $environment)
    {
        $this->translator = $translator;

        $this->language = $environment->getLanguage() ?: $environment->getDefaultLanguage() ?: null;
    }

    public function translate($str)
    {
        if (!$this->language) {
            return $str;
        }

        return $this->translator->translate($str, [], $language);
    }
}
