<?php
namespace Encore\TreeTable;

use Encore\Admin\Widgets\Widget;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;

class TreeTable extends Widget implements Renderable
{

    /**
     * @var string
     */
    protected $view = 'tree-table::index';

    /**
     * @var string
     */
    protected $urls = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $style = [];
    protected $options = [];

    protected $formats = [];

    protected $operates = [];

    /**
     * Table constructor.
     *
     * @param array $urls
     * @param array $headers
     * @param array $columns
     * @param array $rows
     * @param array $style
     * @param array $options
     * @param array $formats
     * @param array $operates
     */
    public function __construct($urls = [], $headers = [], $columns = [], $rows = [], $style = [], $options = [], $formats = [], $operates = [])
    {
        $global_options = (array)config('admin.extensions.tree-table.options');
        $options = array_merge($global_options, $options);
        $options = $this->loadLanguage($options);
        $this->setURIS($urls);
        $this->setHeaders($headers);
        $this->setColumns($columns);
        $this->setRows($rows);
        $this->setStyle($style);
        $this->setOptions($options);
        $this->setFormats($formats);
        $this->setOperates($operates);
        $this->class('table dataTable ' . implode(' ', $this->style));
    }

    /**
     * @param $urls
     * @return $this
     */
    public function setURIS($urls){
        $this->urls = $urls;
        return $this;
    }

    /**
     * Set table headers.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders($headers = [])
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Set table columns.
     *
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns($columns = [])
    {
        if (Arr::isAssoc($columns)) {
            foreach ($columns as $key => $item) {
                $this->columns[] = [$key, $item];
            }
            return $this;
        }
        $this->columns = $columns;
        return $this;
    }

    /**
     * Set table rows.
     *
     * @param array $rows
     *
     * @return $this
     */
    public function setRows($rows = [])
    {
        if (Arr::isAssoc($rows)) {
            foreach ($rows as $key => $item) {
                $this->rows[] = [$key, $item];
            }
            return $this;
        }
        $this->rows = $rows;
        return $this;
    }

    /**
     * Set table style.
     *
     * @param array $style
     *
     * @return $this
     */
    public function setStyle($style = [])
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Set table options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions($options = [])
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Set column format.
     *
     * @param array $formats
     *
     * @return $this
     */
    public function setFormats($formats = [])
    {
        $this->formats = $formats;
        return $this;
    }

    /**
     * Set operates.
     *
     * @param array $operates
     *
     * @return $this
     */
    public function setOperates($operates = [])
    {
        $this->operates = $operates;
        return $this;
    }

    /**
     * Render the table.
     *
     * @return mixed|string
     *
     * @throws \Throwable
     */
    public function render()
    {
        $vars = [
            'urls' => $this->urls,
            'headers' => $this->headers,
            'rows' => $this->rows,
            'columns' => $this->columns,
            'style' => $this->style,
            'attributes' => $this->formatAttributes(),
            'options' => json_encode($this->options),
            'formats' => $this->formats,
            'operates' => $this->operates
        ];
        return view($this->view, $vars)->render();
    }

    /**
     * @param $options
     * @return mixed
     */
    protected function loadLanguage($options)
    {
        if (isset($options['language'])) {
            $language = ucfirst($options['language']);
            $file = __DIR__ . "/../resources/assets/treeTables-1.10.19/plugins/i18n/{$language}.lang";
            if (file_exists($file)) {
                $content = file_get_contents($file);
                $content = substr($content, strpos($content, '{'));
                $language = json_decode($content, true);
                $options['language'] = $language;
            } else {
                unset($options['language']);
            }
        }
        return $options;
    }
}