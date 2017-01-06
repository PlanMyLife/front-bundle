<?php

namespace PlanMyLife\FrontBundle\Twig;

class FrontElementsExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'planmylife_front_elements_twig_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('title', array($this, 'title')),
            new \Twig_SimpleFunction('link', array($this, 'link')),
            new \Twig_SimpleFunction('button', array($this, 'button')),
        );
    }

    /**
     * @param string $content
     * @param int $level
     * @return string
     */
    public function title($content, $level = 1)
    {
        return '<h' . $level . ' class="h' . $level . '">' . $content . '</h' . $level . '>';
    }

    /**
     * @param string $link
     * @param string $content
     * @param array $class
     * @return string
     */
    public function link($link, $content, $class = false)
    {

        $classname = $class ? 'class="' . implode(' ', $class) . '"' : '';

        return '<a href="' . $link . '" ' . $classname . '>' . $content . '</a>';
    }

    /**
     * @param string $link
     * @param string $content
     * @return string
     */
    public function button($link, $content)
    {
        return $this->link($link, $content, ['btn']);
    }
}