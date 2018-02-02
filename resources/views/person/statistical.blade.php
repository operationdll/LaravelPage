<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Client</title>
    <!-- Bootstrap  CSS file -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script src="{{asset('/resources/js/jquery.js')}}"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            data.addRows([
                ['difficult', {{$r12->dnum}}],
                ['normal', {{$r13->nnum}}]
            ]);

            // Set chart options
            var options = {'title':'完成练习的difficult/normal的错题率',
                'width':400,
                'height':300};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Statistical data</h2>
    <table class="table">
        <thead>
        <tr>
            <th>练习题总数</th>
            <th>完成的练习总数</th>
            <th>未完成的练习数</th>
            <th>完成练习的平均分</th>
            <th>完成练习的平均时间</th>
            <th>完成练习的最好分数</th>
            <th>完成练习的最差分数</th>
            <th>完成练习的错误题数</th>
            <th>完成练习的未答题数</th>
            <th>完成练习的错误题类型</th>
            <th>完成练习的未答题类型</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                    {{$r1->total}}
            </td>
            <td>
                    {{$r2->total}}
            </td>
            <td>
                    {{$r3->total}}
            </td>
            <td>
                    {{$r4->score}}
            </td>
            <td>
                    {{$r5}}
            </td>
            <td>
                    {{$r6->bscore}}
            </td>
            <td>
                    {{$r7->wscore}}
            </td>
            <td>
                    {{$r8->enum}}
            </td>
            <td>
                    {{$r9->noAnswer}}
            </td>
            <td>
                @foreach($r10 as $p)
                    {{$p->etype}}<br/>
                @endforeach
            </td>
            <td>
                @foreach($r11 as $p)
                    {{$p->ntype}}<br/>
                @endforeach
            </td>
        </tr>
        <tr>
            <td align="center" colspan="11">
                <div id="chart_div"></div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
