<?php

namespace PlanMyLife\FrontBundle\Twig;

use Faker;

class FakerGeneratorExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $fakers = array();

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'planmylife_words_twig_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('words', array($this, 'words')),
            new \Twig_SimpleFunction('name', array($this, 'name')),
            new \Twig_SimpleFunction('address', array($this, 'address')),
            new \Twig_SimpleFunction('country', array($this, 'country')),
            new \Twig_SimpleFunction('city', array($this, 'city')),
            new \Twig_SimpleFunction('image', array($this, 'images'))
        );
    }

    /**
     * @param $lang
     * @param $nb_word_min
     * @param bool $nb_word_max
     * @return string
     */
    public function words($nb_word_min, $nb_word_max = false, $lang = 'fr_FR')
    {
        $numbers_of_word = $nb_word_max ? mt_rand($nb_word_min, $nb_word_max) : $nb_word_min;

        return $this->faker($lang)->realText($numbers_of_word);
    }

    /**
     * @param $lang
     * @return string
     */
    public function name($lang)
    {
        return $this->faker($lang)->firstName() . ' ' . $this->faker($lang)->lastName();
    }

    /**
     * @param $lang
     * @return string
     */
    public function address($lang)
    {
        return $this->faker($lang)->country() . ', ' . $this->faker($lang)->city();
    }

    /**
     * @param $lang
     * @return string
     */
    public function country($lang)
    {
        return $this->faker($lang)->country();
    }

    /**
     * @param $lang
     * @return string
     */
    public function city($lang)
    {
        return $this->faker($lang)->city();
    }

    /**
     * @param $width
     * @param $height
     * @param bool $theme
     * @return mixed
     */
    public function images($width, $height, $theme = 'cats')
    {
        return $this->faker()->imageUrl($width, $height, $theme);
    }

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