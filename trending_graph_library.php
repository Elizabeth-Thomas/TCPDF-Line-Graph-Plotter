<?php

require 'tcpdf.php';
require 'tcpdf_extend.php';

class TrendingLineGraph extends TCPDF {

    function getRangeAndExtremeTrendingValues($trendingGraphConfig, $range, $arrData) {
        if ($trendingGraphConfig['isCustomYAxisNeeded']) {    
            $trendingValues = array_column($arrData['data'], 'value');
            //todo array pointer
            $input = $range[$arrData['title']]['range'];
           //test it with different inputs
            if (in_array($arrData['title'], array_keys($range))) {
                $input = preg_split("/[><-]/", $input); //handled hyphen,greater,lesser sign
            } 
            $minTrendingValue = min($trendingValues);
            $maxTrendingValue = max($trendingValues);
            
            if (empty($input[0]) && (!empty($input[2]))) {
                $lowerYScale = round(min($minTrendingValue, $input[2])) - $trendingGraphConfig['minimumPointYAxis']; //minimum point         
                $upperYScale = round(max($maxTrendingValue, $input[2])) + $trendingGraphConfig['maximumPointYAxis']; //maximum point
            } elseif (empty($input[0])) { 
                $lowerYScale = round(min($minTrendingValue, $input[1])) - $trendingGraphConfig['minimumPointYAxis']; //minimum point         
                $upperYScale = round(max($maxTrendingValue, $input[1])) + $trendingGraphConfig['maximumPointYAxis']; //maximum point
            } else {
                $lowerYScale = round(min($minTrendingValue, $input[0])) - $trendingGraphConfig['minimumPointYAxis']; //minimum point         
                $upperYScale = round(max($maxTrendingValue, $input[1])) + $trendingGraphConfig['maximumPointYAxis']; //maximum point
            }     
        } else {
            $lowerYScale = $trendingGraphConfig['minTrendingValue'];
            $upperYScale = $trendingGraphConfig['maxTrendingValue'];
        }
        
        $yScale = [];
        $yScale['lowerYScale'] = $lowerYScale;
        $yScale['upperYScale'] = $upperYScale;
        return $yScale;
    }

    function dataTrendingGraph($pdf, $upperYScale, $lowerYScale, $graphStartXPosition = 15, $graphStartYPosition = 42, $arrData, $trendingGraphConfig) {
        $graphHeight = $trendingGraphConfig['graphWidth'] / $trendingGraphConfig['widthToHeightRatio'];

        $pdf->Line($graphStartXPosition, ($graphStartYPosition + $trendingGraphConfig['graphHeight']), $graphStartXPosition + $trendingGraphConfig['graphWidth'], ($graphStartYPosition + $trendingGraphConfig['graphHeight']), array(
            'width' => 0.5,
            'dash' => 0,
            'color' => 'black'
        )); //drawing x axis  
        $pdf->Line($graphStartXPosition, ($graphStartYPosition + $trendingGraphConfig['graphHeight']) - ($graphHeight), $graphStartXPosition, ($graphStartYPosition + $trendingGraphConfig['graphHeight']), array(
            'width' => 0.5,
            'dash' => 0,
            'color' => 'black'
        )); //drawing y axis   
        $pdf->SetFont('helvetica', '', 9);
        //vertical line text       
        $pdf->Line($graphStartXPosition, ($graphStartYPosition + $trendingGraphConfig['graphHeight']) - $trendingGraphConfig['distanceFromCustomOrigin'], $graphStartXPosition - $trendingGraphConfig['lineWidthPointingToValues'], ($graphStartYPosition + $trendingGraphConfig['graphHeight']) - $trendingGraphConfig['distanceFromCustomOrigin'], array(
            'width' => 0.5,
            'dash' => 0,
            'color' => 'black'
        )); //minimum point in y axis at 7.5 distance from xaxis
        $pdf->Line($graphStartXPosition, ($graphStartYPosition + $trendingGraphConfig['graphHeight']) - ($graphHeight) + $trendingGraphConfig['distanceFromGraphHeight'], $graphStartXPosition - $trendingGraphConfig['lineWidthPointingToValues'], ($graphStartYPosition + $trendingGraphConfig['graphHeight']) - ($graphHeight) + $trendingGraphConfig['distanceFromGraphHeight'], array(
            'width' => 0.5,
            'dash' => 0,
            'color' => 'black'
        )); //maximum point in y axis at 2.5 distance from above y axis     
        $pdf->Text($graphStartXPosition - $trendingGraphConfig['verticalTextMinimumXPoint'], ($graphStartYPosition + $trendingGraphConfig['graphHeight']) - $trendingGraphConfig['verticalTextMinimumYPoint'], $lowerYScale); //marking minimum point
        $pdf->Text($graphStartXPosition - $trendingGraphConfig['verticalTextMaximumXPoint'], ($graphStartYPosition + $trendingGraphConfig['graphHeight']) - ($graphHeight) + $trendingGraphConfig['verticalTextMaximumYPoint'], $upperYScale); //marking maximum point
        // get axis X points
        $timePointDifference = round(($trendingGraphConfig['graphWidth'] - $trendingGraphConfig['reducedWidthXAxis']) / (count($arrData['data'])), 2);
        //  define Zero Zone
        $customOrigin = $graphStartYPosition + $trendingGraphConfig['graphHeight'];
        //y axis division length between maximum and minimum point
        $yScaleRatio = (($graphHeight - ($trendingGraphConfig['distanceFromGraphHeight'] + $trendingGraphConfig['distanceFromCustomOrigin'])) / ($upperYScale - $lowerYScale));
        for ($i = 0; $i < count($arrData['data']); $i++) {
            // show horizontal text
            $pdf->Text(($graphStartXPosition + ($timePointDifference / $trendingGraphConfig['horizontalTextPositionXAxis']) + ($i * $timePointDifference)), ($graphStartYPosition + $trendingGraphConfig['graphHeight']) + $trendingGraphConfig['horizontalTextPositionYAxis'], $arrData['data'][$i]['key']);
            $pdf->Line(($graphStartXPosition + $timePointDifference + ($i * $timePointDifference)), ($graphStartYPosition + $trendingGraphConfig['graphHeight']), ($graphStartXPosition + $timePointDifference + ($i * $timePointDifference)), ($graphStartYPosition + $trendingGraphConfig['graphHeight'] + $trendingGraphConfig['lineWidthPointingToValues']), array(
                'width' => 0.5,
                'dash' => 0,
                'color' => 'black'
            ));
            // calculate each point
            $xpnt = ($graphStartXPosition + $timePointDifference + ($i * $timePointDifference));
            $ypnt = ($customOrigin - ($trendingGraphConfig['distanceFromCustomOrigin'])) - ($yScaleRatio * ($arrData['data'][$i]['value'] - $lowerYScale));
            // draw point
            $color = $arrData['data'][$i]['color'];
            $pdf->Circle($xpnt, $ypnt, $trendingGraphConfig['circleRadius'], 0, 360, null, array('width' => 1,
                'dash' => 0,
                'color' => $color), $color);
            $pdf->Text($xpnt - $trendingGraphConfig['labelXAxisValue'], $ypnt - $trendingGraphConfig['labelYAxisValue'], $arrData['data'][$i]['value']);
            // get next point
            if ($i < (count($arrData['data']) - 1)) {
                $xpnt2 = ($graphStartXPosition + $timePointDifference + (($i + 1) * $timePointDifference));
                $ypnt2 = ($customOrigin - ($trendingGraphConfig['distanceFromCustomOrigin'])) - ($yScaleRatio * ($arrData['data'][$i + 1]['value'] - $lowerYScale));
            } else {
                $xpnt2 = $xpnt;
                $ypnt2 = $ypnt;
            }
            // draw the line
            $pdf->Line($xpnt, $ypnt, $xpnt2, $ypnt2, array(
                'width' => 0.5,
                'dash' => 0,
                'color' => array(0, 0, 0)
            )); //drawing connecting lines
        }
    }

}
