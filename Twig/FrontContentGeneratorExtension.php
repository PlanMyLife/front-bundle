<?php

namespace PlanMyLife\FrontBundle\Twig;

use Faker;

class FrontContentGeneratorExtension extends \Twig_Extension
{
    /**
     * @var FakerGeneratorExtension
     */
    private $fakerGenerator;

    /**
     * @var FrontElementsExtension
     */
    private $frontElements;

    /**
     * FrontContentGeneratorExtension constructor.
     * @param FakerGeneratorExtension $wordsGenerator
     * @param FrontElementsExtension $frontElements
     */
    public function __construct($wordsGenerator, $frontElements)
    {
        $this->fakerGenerator = $wordsGenerator;
        $this->frontElements = $frontElements;
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
        return array (
            new \Twig_SimpleFunction('fake_contribution', array($this, 'fake_contribution')),
        );
    }

    public function fake_contribution($long = false, $titleScope = [2, 6])
    {
        $contribution = '';

        foreach ($titleScope as $titleLevel) {
            $contribution .= $this->frontElements->title( 'H' . $titleLevel . ' - ' .$this->fakerGenerator->words(50, 150), $titleLevel );
            $contribution .= $this->fakerGenerator->words(100, 300);
        }

        $contribution = $this->fakerGenerator->words(50, 100);

        return $contribution;
    }
}