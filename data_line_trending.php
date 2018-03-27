<?php

include('trending_graph_library.php');
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->Open();
$pdf->AddPage();

//array data is the array that contains the array of values and key(that shown in y axis)
$arrData = array();

// define old data
$arrData = array(
    "title" => "bmi", //slug name
    "data" => array(
        array(
            "key" => 2013,
            "value" => 21.5,
            "color" => array(
                72,
                74,
                71
            ),
        ),
        array(
            "key" => 2014,
            "value" => 18,
            "color" => array(
                72,
                74,
                71
            ),
        ),
        array(
            "key" => 2015,
            "value" => 16,
            "color" => array(
                72,
                74,
                71
            ),
        ),
        array(
            "key" => 2016,
            "value" => 20,
            "color" => array(
                72,
                74,
                71
            ),
        ),
        array(
            "key" => 2018,
            "value" => 24,
            "color" => array(
                0,
                175,
                80
            ),
        ),
    )
);
//we can give maximum and minimum point in y axis based on the range nad also based
// on values given in the $range array(maxTrendingValue,minTrendingValue)
$range = array(
    "bmi" => array('range' => "18.5 - 23"));
$trendingGraphConfig = array(
    //if isCustomYAxisNeeded is true maximum and minimum points 
    //are taken based on the range
    'isCustomYAxisNeeded' => false,
    //These value are read if isCustomYAxisNeeded is false, if true tthese are not read - start
    'maxTrendingValue' => 26,
    'minTrendingValue' => 15,
    //these value are read if isCustomYAxisNeeded is false , if true tthese are not read - end
    'widthToHeightRatio' => 2, //dividing graphWidth into 2 and that width is taken in Yaxis
    'graphWidth' => 85, //width of X axis
    'graphHeight' => 58, //height of graph from header endY position
    'distanceFromGraphHeight' => 2.5, //maximum value is located at a distance 2.5 below graph height
    'distanceFromCustomOrigin' => 7.5, //minimum value is located at a distance 7.5 from custom origin
    'circleRadius' => 0.5, //radius of each circle is taken as 0.5
    'labelXAxisValue' => 3, //marking values above circle X axis position
    'labelYAxisValue' => 5, //marking values above circle Y axis Position
    'horizontalTextPositionXAxis' => 2, //horizontal text X position(time)
    'horizontalTextPositionYAxis' => 3, //horizontal text Y position(time)
    'reducedWidthXAxis' => 10, //difference between two points based on width 80 
    'minimumPointYAxis' => 1, //minimum value increased by 1 point
    'maximumPointYAxis' => 1, //maximum value decreased by 1 point
    'verticalTextMinimumXPoint' => 7, //vertical line text is 7 point left to headerEndXPosition
    'verticalTextMinimumYPoint' => 9.5, //vertical line text is 9.5 point above custom origin
    'verticalTextMaximumXPoint' => 7, //vertical line text is 7 point left to headerEndXPosition
    'verticalTextMaximumYPoint' => 0.5, //vertical line text is 0.5 point below the y axis
    'lineWidthPointingToValues' => 2// line pointing to each values in X axis and Yaxis 2 points away from axis
);


$lineGraphObj = new TrendingLineGraph();
//In this function we will get the maximum and minimum values.if that values are taking
//on the basis of range then this function  handled hyphen,greater,lesser sign, lesser than and equalto
//and also greater than and equal to sign 
$yScale = $lineGraphObj->getRangeAndExtremeTrendingValues($trendingGraphConfig, $range, $arrData);

//This function is to plot a line graph with minimum and maximum values and also keys in  the array data
$lineGraphObj->dataTrendingGraph($pdf, $yScale['upperYScale'], $yScale['lowerYScale'], 15, 42, $arrData, $trendingGraphConfig);
// generate pdf file
$pdf->Output();
?>
