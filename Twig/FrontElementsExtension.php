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
            new \Twig_SimpleFunction('title', array($this, 'title'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('link', array($this, 'link'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('button', array($this, 'button'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('quotation', array($this, 'quotation'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('liste', array($this, 'liste'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('accordion', array($this, 'accordion')), array('is_safe' => array('all')),
            new \Twig_SimpleFunction('highlight', array($this, 'highlight'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('table', array($this, 'table'), array('is_safe' => array('all'))),
            new \Twig_SimpleFunction('image', array($this, 'image'), array('is_safe' => array('all'))),
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

    /**
     * @param string $quote
     * @param string $author
     * @return string
     */
    public function quotation($quote, $author)
    {
        $quotation = '<div class="article_quotation">';

        $quotation .= "<p class=\"article_quotation_text\">$quote</p>";
        $quotation .= "<p class=\"article_quotation_author\">$author</p>";

        $quotation .= '</div>';

        return $quotation;
    }

    /**
     * @param string $type
     * @param array $elements
     * @return string
     */
    public function liste($type = 'ul', $elements)
    {
        $liste = "<$type>";

        foreach($elements as $element) {
            $liste .= "<li>$element</li>";
        }

        $liste .= "</$type>";

        return $liste;
    }

    /**
     * @param string $title
     * @param string $content
     * @return string
     */
    public function accordion($title, $content)
    {
        $accordion = '<div class="article_accordion">';

        $accordion .= "<div class=\"article_accordion_title\">$title</div>";
        $accordion .= "<div class=\"article_accordion_content\">$content</div>";

        $accordion .= '</div>';

        return $accordion;
    }

    /**
     * @param string $title
     * @param string $content
     * @return string
     */
    public function highlight($title, $content)
    {
        $highlight = '<div class="article_highlight">';

        $highlight .= "<div class=\"article_highlight_title\">$title</div>";
        $highlight .= "<div class=\"article_highlight_content\">$content</div>";

        $highlight .= '</div>';

        return $highlight;
    }


    /**
     * @param array $headLines
     * @param array $bodyLines
     * @param string $class
     * @return string
     */
    public function table($headLines, $bodyLines, $class = '')
    {
        $table = "<table class=\"$class\">";

        if( !empty($headLines) )
        {
            $table .= '<thead>';
            $table .= '<tr>';

            foreach($headLines as $line)
            {
                $table .= "<td>$line</td>";
            }

            $table .= '</tr>';
            $table .= '</thead>';
        }

        $table .= '<tbody>';
        foreach($bodyLines as $line)
        {
            $table .= '<tr>';

            foreach($line as $cell)
            {
                $table .= "<td>$cell</td>";
            }

            $table .= '</tr>';
        }
        $table .= '</tbody>';

        $table .= '</table>';

        return $table;
    }

    public function image($url, $alt, $legend)
    {
        $image = '<div class="article_image">';

        $image .= "<img src='$url' alt='$alt'/>";

        if( !empty($legend) )
        {
            $image .= "<div class=\"article_image_legend\">$legend</div>";
        }

        $image .= '</div>';

        return $image;
    }
}