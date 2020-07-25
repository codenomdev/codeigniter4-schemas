<?php

namespace Tatter\Schemas\Collectors;

/**
 * Auth collector
 */
class Schema extends \CodeIgniter\Debug\Toolbar\Collectors\BaseCollector
{
    /**
     * Whether this collector has data that can
     * be displayed in the Timeline.
     *
     * @var boolean
     */
    protected $hasTimeline = false;

    /**
     * Whether this collector needs to display
     * content in a tab or not.
     *
     * @var boolean
     */
    protected $hasTabContent = true;

    /**
     * Whether this collector has data that
     * should be shown in the Vars tab.
     *
     * @var boolean
     */
    protected $hasVarData = false;

    /**
     * The 'title' of this Collector.
     * Used to name things in the toolbar HTML.
     *
     * @var string
     */
    protected $title = 'Schema';

    /**
     * Copy of the schema to display
     *
     * @var Schema
     */
    protected $schema;

    //--------------------------------------------------------------------


    /**
     * Load a copy of the schema.
     */
    public function __construct()
    {
        $this->schema = service('schemas')->get();
    }

    /**
     * Returns any information that should be shown next to the title.
     *
     * @return string
     */
    public function getTitleDetails(): string
    {
        if (empty($this->schema)) {
            return '(Failure)';
        } elseif (empty($this->schema->tables)) {
            return '(No tables)';
        }

        return '(' . count($this->schema->tables) . ' tables)';
    }

    //--------------------------------------------------------------------

    /**
     * Returns the data of this collector to be formatted in the toolbar
     *
     * @return string
     */
    public function display(): string
    {
        if (empty($this->schema)) {
            return '<p><em>Schema failed to load.</em></p>';
        } elseif (empty($this->schema->tables)) {
            return '<p><em>No tables found.</em></p>';
        }

        $html = '';
        foreach ($this->schema->tables as $table) {
            $html .= '<h4>' . $table->name . '</h4>' . PHP_EOL;
            $html .= '<pre style="color: gray;"><code>' . print_r($table, true) . '</pre></code>' . PHP_EOL;
        }

        return $html;
    }

    //--------------------------------------------------------------------

    /**
     * Gets the "badge" value for the button.
     *
     * @return integer
     */
    public function getBadgeValue(): int
    {
        return empty($this->schema->tables) ? 0 : count($this->schema->tables);
    }

    //--------------------------------------------------------------------

    /**
     * Display the icon.
     *
     * Icon from https://icons8.com - 1em package
     * https://icons8.com/icon/pack/data/p1em
     *
     * @return string
     */
    public function icon(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAAzUlEQVQ4ja3SvWoCQRTF8d+aoGA+Cgt9gzyI2KQ1dZ4n72AgphQLG41YCAkBn0UMa5MmqTSFu7AYnR3EPwwzcLnnHs4d/rPGFumBWhTbvTtIJeBgfaqDs1C0H8zkMlIkcSSTQxkUmxInZhK1gTIHOfsZXGGKFTohB8UDn6jjHT20sQwJ5KRYoIk5XuzCf8ZbjABUMcEge/czRzcxAlWMMUQNr/jANcf/QR7cE75wgcdscgP3+GG35zJG+EYLGzzgNy+WrTHBHW4xQ7fYDH94jDaEJNHsRQAAAABJRU5ErkJggg==';
    }
}

/** End of file src/Collectors/Schema.php */
