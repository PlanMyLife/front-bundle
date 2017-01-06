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

    /**
     * @param bool $long
     * @param array $titleScope
     * @return string
     */
    public function fake_contribution($long = false, $titleScope = [1, 6])
    {
        $contribution = '';

        for ($i = $titleScope[0]; $i <= $titleScope[1]; $i++) {
            $contribution .= $this->frontElements->title( 'H' . $i . ' - ' .$this->fakerGenerator->words(50, 150), $i );

            $contribution .= '<p>' . $this->fakerGenerator->words(100, 200) . '</p>';

            $nextParagraph = [];

            array_push($nextParagraph, $this->fakerGenerator->words(30, 100));

            if(rand(1, 100) > 50) {
                array_push($nextParagraph, '<strong>' . $this->fakerGenerator->words(30, 100) . '</strong>');
                array_push($nextParagraph, $this->fakerGenerator->words(30, 100));
            }

            if(rand(1, 100) > 50) {
                array_push($nextParagraph, '<i>' . $this->fakerGenerator->words(30, 100) . '</i>');
                array_push($nextParagraph, $this->fakerGenerator->words(30, 100));
            }

            if(rand(1, 100) > 50) {
                array_push($nextParagraph, $this->frontElements->link('#', $this->fakerGenerator->words(30, 100)));
                array_push($nextParagraph, $this->fakerGenerator->words(30, 100));
            }

            $contribution .= '<p>';
            $contribution .= implode(' ', $nextParagraph);
            $contribution .= '</p>';
        }

        $contribution .= '<p>';
        $contribution .= $this->frontElements->button('#', $this->fakerGenerator->words(20, 50));
        $contribution .= $this->frontElements->button('#', $this->fakerGenerator->words(20, 50));
        $contribution .= '</p>';

        $contribution .= $this->fakerGenerator->words(50, 100);

        $contribution .= $this->frontElements->quotation($this->fakerGenerator->words(50, 150), $this->fakerGenerator->name());

        $liste = [];
        for ($i = 0; $i < rand(3, 6); $i++) {
            array_push($liste, $this->fakerGenerator->words(50, 100));
        }

        $contribution .= $this->frontElements->liste('ul', $liste);
        $contribution .= $this->frontElements->liste('ol', $liste);

        return $contribution;
    }
}