<?php

namespace PlanMyLife\FrontBundle\Twig;

use Faker;

class FrontContentGeneratorExtension extends PlanMyLifeExtension
{
    /**
     * FrontContentGeneratorExtension constructor.
     * @param $wordsGenerator
     */
    public function __construct($wordsGenerator)
    {
        $this->$wordsGenerator = $wordsGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'planmylife_front_twig_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fake_contribution', array($this, 'fake_contribution'))
        );
    }

    public function fake_contribution($long = false)
    {

    }
}