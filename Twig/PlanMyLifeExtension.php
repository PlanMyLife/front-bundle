<?php

namespace PlanMyLife\FrontBundle\Twig;

use Faker;

class PlanMyLifeExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $fakers = array();

    /**
     * @param null $lang
     * @return mixed
     */
    protected function faker($lang = null)
    {
        if (!$lang) {
            $lang = Faker\Factory::DEFAULT_LOCALE;
        }

        if (!isset($this->fakers[$lang])) {
            $this->fakers[$lang] = Faker\Factory::create($lang);
        }

        return $this->fakers[$lang];
    }
}