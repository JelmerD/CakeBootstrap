<?php
declare(strict_types=1);

namespace JelmerD\Bootstrap\View\Helper;

use Cake\View\Helper;

/**
 * Html helper
 */
class HtmlHelper extends Helper\HtmlHelper
{
    /**
     * @param $title
     * @param $url
     * @param string|null $type
     * @param array $options
     * @return string
     */
    public function button($title, $url = null, string $type = null, array $options = []): string
    {
        if (!isset($options['class'])) $options['class'] = null;
        $options['class'] =
            'btn btn-' .                //base
            ($type ?? 'primary') .      //default type
            ($options['class'] ? ' ' . $options['class'] : null);  //class defined by user
        return $this->link($title, $url, $options);
    }

}
