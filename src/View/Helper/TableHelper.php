<?php
declare(strict_types=1);

/**
 * This helper adds the ability to make tables easier and faster
 *
 * @copyright Copyright (c) Jelmer Dröge (http://jelmerdroge.nl)
 * @link http://github.com/JelmerD Github JelmerD
 * @version 4.0.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @author Jelmer Dröge
 */

namespace JelmerD\Bootstrap\View\Helper;

use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Hash;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\StringTemplateTrait;

/**
 * Class TableHelper
 *
 * @property HtmlHelper $Html
 */
class TableHelper extends Helper
{

    use StringTemplateTrait;

    /**
     * Helpers we use
     *
     * @var array
     */
    public $helpers = array('Html');

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'partOptions' => [
            'table' => [],
            'head' => [],
            'headRow' => [],
            'headCell' => [
                'tag' => 'th'
            ],
            'body' => [],
            'bodyRow' => [],
            'bodyCell' => [
                'tag' => 'td'
            ],
            'foot' => [],
            'footRow' => [],
            'footCell' => [
                'tag' => 'td'
            ],
            'fallbackRow' => [],
        ],
    ];

    /**
     * Keep track of several counts
     *
     * - Rows (in all the sections)
     * - Columns
     *
     * @var array
     */
    protected $_count;

    /**
     * Keep track of all the open tags, that way the helper works more like magic
     *
     * @var null|bool
     */
    protected $_tags;

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * Create a new table
     *
     * @param string|null $caption Text to show in the caption tag of the table. {@see http://www.w3schools.com/tags/tag_caption.asp}
     * @param array $options Options to parse to the `table` tag
     * @return string The table opening
     */
    public function create(string $caption = null, array $options = []): string
    {
        if ($this->_tags['table']) {
            throw new InternalErrorException('You did not use the TableHelper::end() method on your previous table. Make sure to use it to prevent HTML bugs.');
        }
        $this->_reset();
        $options = Hash::merge($this->getConfig('partOptions.table'), $options);
        if (is_string($caption)) {
            $caption = $this->Html->tag('caption', $caption);
        }
        return $this->_tagStart('table', $options) . $caption;
    }

    /**
     * Placeholder for {@see TableHelper::row()} with the $context option set to 'head'
     *
     * If the thead tag is still open, it will append a new tr to that group
     *
     * If the group is still open, but you add headOptions, it will force a new thead tag
     *
     * @param array $cells The cell data for this tr
     * @param array $rowOptions Additional HTML attributes for the tr tag
     * @param array $headOptions Additional HTML attributes for the thead tag
     * @return string
     */
    public function head(array $cells = [], array $rowOptions = [], array $headOptions = []): ?string
    {
        return $this->row($cells, $rowOptions, 'head', $headOptions);
    }

    /**
     * Placeholder for {@see TableHelper::row()} with the $context option set to 'body'
     *
     * If the tbody tag is still open, it will append a new tr to that group
     *
     * If the group is still open, but you add bodyOptions, it will force a new tbody tag
     *
     * @param array $cells The cell data for this tr
     * @param array $rowOptions Additional HTML attributes for the tr tag
     * @param array $bodyOptions Additional HTML attributes for the tbody tag
     * @return string
     */
    public function body(array $cells = [], array $rowOptions = [], array $bodyOptions = []): ?string
    {
        return $this->row($cells, $rowOptions, 'body', $bodyOptions);
    }

    /**
     * Placeholder for {@see TableHelper::row()} with the $context option set to 'foot'
     *
     * If the tfoot tag is still open, it will append a new tr to that group
     *
     * If the group is still open, but you add footOptions, it will force a new tfoot tag
     *
     * @param array $cells The cell data for this tr
     * @param array $rowOptions Additional HTML attributes for the tr tag
     * @param array $footOptions Additional HTML attributes for the tfoot tag
     * @return string
     */
    public function foot(array $cells = [], array $rowOptions = [], array $footOptions = []): ?string
    {
        return $this->row($cells, $rowOptions, 'foot', $footOptions);
    }

    /**
     * Create a new row
     *
     * If the context tag is still open, it will append a new tr to that group.
     *
     * If the group is still open, but you add contextOptions, it will force a new context tag
     *
     * @param array $cells The cell data for this tr
     * @param array $rowOptions Additional HTML attributes for the tr tag
     * @param string|null $context Choose head, body or foot (or use the placeholder functions)
     * @param array $contextOptions Additional HTML attributes for the thead, tbody or tfoot tag
     * @return string|null
     * @see TableHelper::foot()
     *
     * @see TableHelper::head()
     * @see TableHelper::body()
     */
    public function row(array $cells = [], array $rowOptions = [], string $context = null, array $contextOptions = []): ?string
    {
        if (!$this->_tags['table']) {
            throw new InternalErrorException('Open the table first by calling TableHelper::create()');
        }
        $parts = ['head', 'body', 'foot'];
        if ($context === null) {
            $context = $this->_tags['thead'] ? 'head' : ($this->_tags['tfoot'] ? 'foot' : 'body');
        }

        if (!in_array($context, $parts)) {
            throw new InternalErrorException('The $context variable must be equal to one of the following: ' . implode(', ', $parts));
        }
        $tag = 't' . $context;

        $this->_checkHeadColumnCount();
        $this->_count['currentColumns'] = 0;


        $text = $this->_tagEnd('tr');
        if (!$this->_tags[$tag] || $contextOptions) {
            $contextOptions = Hash::merge($this->getConfig('partOptions.' . $context), $contextOptions);
            $text .= $this->_partEnds() . $this->_tagStart($tag, $contextOptions);
        }

        $rowOptions = Hash::merge($this->getConfig('partOptions.' . $context . 'Row'), $rowOptions);

        $text .= $this->_tagStart('tr', $rowOptions);
        $this->_count[$context . 'Rows']++;


        foreach ($cells as $cell) {
            if (is_array($cell)) {
                $text .= $this->cell($cell[0], $cell[1]);
            } else {
                $text .= $this->cell($cell);
            }
        }

        # do not end the tr tag, maybe the user wants to add extra cells dynamically
        return $text;
    }

    /**
     * Create a cell
     *
     * If a row is still open (which is the case, unless you start a new row, or end the table) you can keep adding cells.
     *
     * #Options#
     * - `tag` You can use the tag option to override to 'td' or 'th'
     * - {@see HtmlHelper::tag()}
     *
     * @param string $data The data to show in the cell
     * @param array $options Additional HTML attributes for the td/th tag
     * @return string
     */
    public function cell(string $data, array $options = []): string
    {
        if (!$this->_tags['tr']) {
            throw new InternalErrorException('You did not open a row. Use TableHelper::row() to open a row first');
        }

        $context = $this->_tags['thead'] ? 'head' : ($this->_tags['tfoot'] ? 'foot' : 'body');
        $options = Hash::merge($this->getConfig('partOptions.' . $context . 'Cell'), $options);
        if (!in_array($options['tag'], ['th', 'td'])) {
            throw new InternalErrorException('The $options[\'tag\'] must be: \'th\' or \'td\'');
        }
        $tag = $options['tag'];
        unset($options['tag']);

        $this->_count['currentColumns']++;
        return $this->Html->tag($tag, $data, $options);
    }

    /**
     * Close the table
     *
     * @return string
     */
    public function end(): string
    {
        return $this->_partEnds() . $this->_tagEnd('table');
    }

    /**
     * Show a fallback message when no body rows are present. Use it just before the TableHelper::end() method.
     *
     * @param array|string $data Data to show, when a string is used it will set the colspan automatically. Or use an array as {@see TableHelper::body()}
     * @param array $rowOptions Additional HTML attributes for the tr tag
     * @param array $bodyOptions Additional HTML attributes for the tbody tag
     * @return string
     */
    public function fallback($data, array $rowOptions = [], array $bodyOptions = []): ?string
    {
        if ($this->count('bodyRows') > 0) {
            return null;
        }
        $this->_checkHeadColumnCount();
        $rowOptions = Hash::merge($this->getConfig('partOptions.fallbackRow'), $rowOptions);
        if (is_array($data)) {
            return $this->row($data, $rowOptions, 'body', $bodyOptions);
        }
        if ($this->count('headColumns') === null) {
            throw new InternalErrorException('Unable to determine colspan. Create a head() or parse an array of $data');
        }
        return $this->row([
            [$data, [
                'colspan' => $this->count('headColumns')
            ]]
        ], $rowOptions, 'body', $bodyOptions);
    }

    /**
     * Get the count of several parts.
     *
     * @param string|null $part Filter the count list. If it does not exist, it will return all counts
     * @return mixed
     */
    public function count(string $part = null)
    {
        if (is_null($part)) {
            return $this->_count;
        } elseif (!array_key_exists($part, $this->_count)) {
            throw new InternalErrorException('This key does not exist in the count list. Choose: ' . implode(', ', array_keys($this->_count)));
        }
        return $this->_count[$part];
    }

    /**
     * Close all table tags except for the table tag.
     *
     * @return null|string
     */
    protected function _partEnds(): ?string
    {
        $text = null;
        foreach (['tr', 'thead', 'tbody', 'tfoot'] as $tag) {
            $text .= $this->_tagEnd($tag);
        }
        return $text;
    }

    /**
     * Open a tag, without content
     *
     * @param string $tag The tag to open
     * @param array $options HTML attributes
     * @return string
     */
    protected function _tagStart(string $tag, array $options = []): string
    {
        $this->_tags[$tag] = true;
        return $this->Html->tag($tag, null, $options);
    }

    /**
     * Close a tag
     *
     * @param string $tag Tag to close
     * @return null|string
     */
    protected function _tagEnd(string $tag): ?string
    {
        if ($this->_tags[$tag]) {
            $this->_tags[$tag] = false;
            return sprintf('</%s>', $tag);
        }
        return null;
    }

    /**
     * Check if a has been created once and use that amount of columns for the fixed column count.
     */
    protected function _checkHeadColumnCount()
    {
        if ($this->_tags['thead'] && $this->count('headColumns') === null) {
            $this->_count['headColumns'] = $this->_count['currentColumns'];
        }
    }

    /**
     * Reset the tracking variables
     */
    protected function _reset()
    {
        $this->_tags = [
            'table' => false,
            'thead' => false,
            'tbody' => false,
            'tfoot' => false,
            'tr' => false,
        ];
        $this->_count = [
            # first count of columns when creating a head, this will be used for catching errors by the user and the amount of colspan for the fallback.
            'headColumns' => null,
            # current amount of column, in this row
            'currentColumns' => 0,
            # total count of rows in the different sections
            'headRows' => 0,
            'bodyRows' => 0,
            'footRows' => 0,
        ];
    }

}
