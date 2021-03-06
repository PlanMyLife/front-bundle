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
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('words', array($this, 'words')),
            new \Twig_SimpleFunction('name', array($this, 'name')),
            new \Twig_SimpleFunction('email', array($this, 'email')),
            new \Twig_SimpleFunction('phone', array($this, 'phone')),
            new \Twig_SimpleFunction('address', array($this, 'address')),
            new \Twig_SimpleFunction('country', array($this, 'country')),
            new \Twig_SimpleFunction('city', array($this, 'city')),
            new \Twig_SimpleFunction('fake_image', array($this, 'images'))
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
    public function name($lang = 'fr_FR')
    {
        return $this->faker($lang)->firstName() . ' ' . $this->faker($lang)->lastName();
    }

    /**
     * @param $lang
     * @return string
     */
    public function email($lang = 'fr_FR')
    {
        return $this->faker($lang)->freeEmail();
    }

    /**
     * @param $lang
     * @return string
     */
    public function phone($lang = 'fr_FR')
    {
        return $this->faker($lang)->phoneNumber();
    }

    /**
     * @param $lang
     * @return string
     */
    public function address($lang = 'fr_FR')
    {
        return $this->faker($lang)->country() . ', ' . $this->faker($lang)->city();
    }

    /**
     * @param $lang
     * @return string
     */
    public function country($lang = 'fr_FR')
    {
        return $this->faker($lang)->country();
    }

    /**
     * @param $lang
     * @return string
     */
    public function city($lang = 'fr_FR')
    {
        return $this->faker($lang)->city();
    }

    /**
     * @param $width
     * @param $height
     * @param string $theme
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'planmylife_words_twig_extension';
    }
}
