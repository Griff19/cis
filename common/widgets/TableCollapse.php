<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 19.05.2016
 * Time: 0:00
 */

namespace common\widgets;

use yii\grid\GridView;

class TableCollapse extends GridView
{
    protected $openTable = false;
    protected $par = 0;

    public function renderTableBody()
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows)) {
            $colspan = count($this->columns);

            return "<tbody>\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        } else {
            return "<tbody>\n" . implode("\n", $rows) . "\n</tbody>";
        }
    }

    public function renderTableRow($model, $key, $index){
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }
        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;
        if (array_key_exists('collapse', $options))
            if ($options['collapse'] == 'parrent') {
                $this->par++;
                $options[] = ['data-toggle' => 'collapse', 'data-target' => '.rows' . $this->par];
            }

        return Html::tag('tr', implode('', $cells), $options);
    }
}