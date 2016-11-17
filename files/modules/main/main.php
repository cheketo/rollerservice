<?php
    include('../../includes/inc.main.php');
    $Head->setTitle($Menu->GetTitle());
    $Head->setHead();
    include('../../includes/inc.top.php');
 ?>

            <!--<div class="row">-->
            <!--  <div class="col-md-12 col-sm-12 col-xs-12">-->
            <!--    <div class="x_panel">-->
            <!--      <div class="x_title">-->
            <!--        <h2>Plain Page</h2>-->
            <!--        <ul class="nav navbar-right panel_toolbox">-->
            <!--          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>-->
            <!--          <li class="dropdown">-->
            <!--            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>-->
            <!--            <ul class="dropdown-menu" role="menu">-->
            <!--              <li><a href="#">Settings 1</a>-->
            <!--              </li>-->
            <!--              <li><a href="#">Settings 2</a>-->
            <!--              </li>-->
            <!--            </ul>-->
            <!--          </li>-->
            <!--          <li><a class="close-link"><i class="fa fa-close"></i></a></li>-->
            <!--        </ul>-->
            <!--        <div class="clearfix"></div>-->
            <!--      </div>-->
            <!--      <div class="x_content">-->
            <!--          Add content to the page ...-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->
            <!--Ejemplo-->
            <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Ventas del mes</span>
              <div class="count">28344</div>
              <span class="count_bottom"><i class="green">12% </i> Diciembre</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Promedio de horas</span>
              <div class="count">123.50</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> Semana pasada</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Ventas anuales</span>
              <div class="count green">485627</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> 2015</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-cube"></i> Stock</span>
              <div class="count">4,567</div>
              <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> Ayer</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
              <div class="count">2,315</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
              <div class="count">7,325</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
          </div>
        <!--/Ejemplo-->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-12">
                    <h3>Ganancia Total y Neta <small>Anual</small></h3>
                  </div>
                  
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                  <div style="width: 100%;">
                    <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height: 270px; padding: 0px; position: relative;"><canvas class="flot-base" width="743" height="270" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 743px; height: 270px;"></canvas><div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);"><div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 92px; top: 253px; left: 110px; text-align: center;">Jan 02</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 92px; top: 253px; left: 227px; text-align: center;">Jan 03</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 92px; top: 253px; left: 344px; text-align: center;">Jan 04</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 92px; top: 253px; left: 461px; text-align: center;">Jan 05</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 92px; top: 253px; left: 578px; text-align: center;">Jan 06</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 92px; top: 253px; left: 695px; text-align: center;">Jan 07</div></div><div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div class="flot-tick-label tickLabel" style="position: absolute; top: 240px; left: 13px; text-align: right;">0</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 222px; left: 7px; text-align: right;">10</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 203px; left: 7px; text-align: right;">20</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 185px; left: 7px; text-align: right;">30</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 166px; left: 7px; text-align: right;">40</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 148px; left: 7px; text-align: right;">50</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 129px; left: 7px; text-align: right;">60</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 111px; left: 7px; text-align: right;">70</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 92px; left: 7px; text-align: right;">80</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 74px; left: 7px; text-align: right;">90</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 55px; left: 1px; text-align: right;">100</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 37px; left: 1px; text-align: right;">110</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 18px; left: 1px; text-align: right;">120</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 0px; left: 1px; text-align: right;">130</div></div></div><canvas class="flot-overlay" width="743" height="270" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 743px; height: 270px;"></canvas></div>
                  </div>
                </div>
                

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <!-- Ejemplo -->
       

<?php
    include('../../includes/inc.bottom.php');
    $Foot->setScript('../../../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js');
    $Foot->setScript('../../../vendors/Chart.js/dist/Chart.min.js');
    $Foot->setScript('../../../vendors/Flot/jquery.flot.js');
    $Foot->setScript('../../../vendors/Flot/jquery.flot.time.js');
    $Foot->setScript('../../../vendors/Flot/jquery.flot.resize.js');
    $Foot->setScript('../../../vendors/flot.orderbars/js/jquery.flot.orderBars.js');
    $Foot->setScript('../../../vendors/flot-spline/js/jquery.flot.spline.min.js');
    $Foot->setScript('../../../vendors/flot.curvedlines/curvedLines.js');
    $Foot->setFoot();
?>
 <script>
      $(document).ready(function() {
        var data1 = [
          [gd(2012, 1, 1), 100000],
          [gd(2012, 2, 1), 120200],
          [gd(2012, 3, 1), 122031],
          [gd(2012, 4, 1), 182345],
          [gd(2012, 5, 1), 190234],
          [gd(2012, 6, 1), 198032],
          [gd(2012, 7, 1), 290123]
        ];

        var data2 = [
          [gd(2012, 1, 1), 54123],
          [gd(2012, 2, 1), 67242],
          [gd(2012, 3, 1), 70231],
          [gd(2012, 4, 1), 102030],
          [gd(2012, 5, 1), 109231],
          [gd(2012, 6, 1), 83023],
          [gd(2012, 7, 1), 170231]
        ];
        $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
          data1, data2
        ], {
          series: {
            lines: {
              show: false,
              fill: true
            },
            splines: {
              show: true,
              tension: 0.4,
              lineWidth: 1,
              fill: 0.4
            },
            points: {
              radius: 0,
              show: true
            },
            shadowSize: 2
          },
          grid: {
            verticalLines: true,
            hoverable: true,
            clickable: true,
            tickColor: "#d5d5d5",
            borderWidth: 1,
            color: '#fff'
          },
          colors: ["rgba(154, 185, 38, 0.38)", "rgba(3, 88, 106, 0.38)"],
          xaxis: {
            tickColor: "rgba(51, 51, 51, 0.06)",
            mode: "time",
            tickSize: [1, "month"],
            //tickLength: 10,
            axisLabel: "Date",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 10
          },
          yaxis: {
            ticks: 8,
            tickColor: "rgba(51, 51, 51, 0.06)",
          },
          tooltip: false
        });

        function gd(year, month, day) {
          return new Date(year, month - 1, day).getTime();
        }
      });
    </script>