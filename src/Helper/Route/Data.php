<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Route;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Data
{
    private $name;
    private $params = [];
    private $queryArray = [];
    private $helper;

    public function __construct($name, Route $helper)
    {
        $this->name = $name;
        $this->helper = $helper;
    }

    public function __call($name, array $arguments)
    {
        return $this->helper->$name(...$arguments);
    }

    public function __toString()
    {
        $this->helper->__toString();
    }

    public function params($params)
    {
        if (!$params) {
            return;
        }

        if (!is_array($params)) {
            $params = explode('/', $params);
            $params = $this->makeKeyValuePairs($params);
        }

        $this->params = $params;

        return $this;
    }

    public function query($query)
    {
        if (!$query) {
            return;
        }

        $queryArray = [];

        if (!is_array($query)) {
            $query = parse_url('?'.$query);
            if (array_key_exists('query', $query)) {
                parse_str($query['query'], $queryArray);
            }
        }

        $this->queryArray = $queryArray;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getQueryArray()
    {
        return $this->queryArray;
    }

    public function getQueryString()
    {
        if (!$this->queryArray) {
            return '';
        }

        return '?'.http_build_query($this->queryArray);
    }

    private function makeKeyValuePairs(array $array)
    {
        $pairs = [];
        $nb = count($array);
        for ($i = 0; $i < $nb - 1; $i += 2) {
            $pairs[$array[$i]] = $array[$i+1];
        }
        if ($i < $nb) {
            $pairs[$array[$i]] = '';
        }

        return $pairs;
    }
}
