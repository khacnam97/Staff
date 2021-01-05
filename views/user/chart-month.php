<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chart-month';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php

$series = [
    [
        'name' => 'Project',
        'data' => [
            ['2019-12-05', 1],
            ['2020-10-04', 1],
            ['2020-10-10', 1],
            ['2020-11-05', 2],
            ['2020-12-05', 1],
        ],
    ],

];

echo \onmotion\apexcharts\ApexchartsWidget::widget([
    'type' => 'area', // default area
    'height' => '400', // default 350
    'width' => '100%', // default 100%
    'chartOptions' => [
        'chart' => [
            'toolbar' => [
                'show' => true,
                'autoSelected' => 'zoom'
            ],
        ],
        'xaxis' => [
            'type' => 'datetime',
            // 'categories' => $categories,
        ],
        'plotOptions' => [
            'bar' => [
                'horizontal' => false,
                'endingShape' => 'rounded'
            ],
        ],
        'dataLabels' => [
            'enabled' => false
        ],
        'stroke' => [
            'show' => true,
            'colors' => ['transparent']
        ],
        'legend' => [
            'verticalAlign' => 'bottom',
            'horizontalAlign' => 'left',
        ],
    ],
    'series' => $series
]);
?>


