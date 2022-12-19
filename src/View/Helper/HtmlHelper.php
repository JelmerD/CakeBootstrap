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
     * Holds all the currently opened tags from the open() method
     *
     * @var array
     * @see HtmlHelper::open()
     */
    protected $_openTags = [];

    /**
     * Opens an HTML tag. Use close() to close a tag.
     *
     * @param string $name tag to open
     * @param array<string, mixed> $options Additional HTML attributes of the DIV tag, see above.
     * @return string
     * @see HtmlHelper::close()
     */
    public function open(string $name, array $options = []): string
    {
        # add the tag that is being opened to the list
        $this->_openTags[] = $name;

        return $this->tag($name, null, $options);
    }

    /**
     * Closes an HTML tag.
     * Will trigger a notice when the provided $name does not match the last opened tag.
     *
     * @param string|null $name tag to close, leave blank to close the last opened one
     * @param bool $check Set to false if you do not want to check the currently opened tags by open(). ie. Can be used to close a div or para without content.
     * @return string|null
     * @see HtmlHelper::open()
     */
    public function close(string $name = null, bool $check = true): ?string
    {
        if ($check || is_null($name)) {
            $last = array_pop($this->_openTags);

            if (!$last) return null;

            # check if the proved $name matches the $last opened tag
            if ($name && $last != $name) {
                trigger_error('HtmlHelper::close() closed a tag that was not opened last. Resulting in a HTML fault.', E_USER_NOTICE);
            }

            # if no $name is provided, use the last opened tag
            if (!$name) $name = $last;
        }

        return $this->tag('/' . $name, null);
    }

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
