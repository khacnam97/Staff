<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProject[]  */
/* @var $dataCountProject[]  */
/* @var $dataNameUser[]  */

$this->title = 'Chart-month';
$this->params['breadcrumbs'][] = $this->title;
?>
 
<?php

$series = [
    [
        'name' => 'Project',
        'data' => $dataProject,
    ],

];
echo '<h1>Month</h1>';
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
$seriesUser = [
    [
        'name' => 'Project',
        'data' =>$dataCountProject,
    ],

];
echo '<h1>User</h1>';
echo \onmotion\apexcharts\ApexchartsWidget::widget([
    'type' => 'bar', // default area
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
             'categories' => $dataNameUser,
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
    'series' => $seriesUser
]);
?>



