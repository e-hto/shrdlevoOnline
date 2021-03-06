@extends('layouts.frontend')
@section('header-section')
    <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('frontend/images/icon/block.png') }}">

    <script src="{{ asset('frontend/js/block_script.js') }}"></script>
    <script src="{{ asset('frontend/js/block_action.js') }}"></script>
    <script src="{{ asset('frontend/js/config.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartTime);

        function drawChartTime() {
            var time = [
                ['Task', 'Seconds']
            ];
            var success = [
                ['Task', 'Result']
            ];
            var attempts = [
                ['Attempts', 'Tasks']
            ];

            taskSeconds = [];
            seconds = [5, 10, 20, 30, 40, 50, 60, 90, 120, 150, 180, 210, 240, 300, 600, 900, 1200, 99999];
            numSeconds = [];
            for(var i=0;i<seconds.length;i++){
                numSeconds.push(0);
                taskSeconds.push("");
            }

            numAttempts = [];
            taskAttempts = [];
            for(var i=0;i<numberAttempts;i++){
                numAttempts.push(0);
                taskAttempts.push("");
            }

            totalTime = 0;
            totalAttempts = 0;
            correct = 0;
            incorrect = 0;
            for(var i=0;i<answers.length;i++){
                for(var j=0;j<seconds.length;j++){
                    if(answers[i].time<=seconds[j]){
                        numSeconds[j]+=1;
                        if(taskSeconds[j]!="") taskSeconds[j]+=", ";
                        taskSeconds[j]+=(answers[i].task+1);
                        break;
                    }
                }
                totalTime+=answers[i].time;

                numAttempts[answers[i].attempts-1]+=1;
                if(taskAttempts[answers[i].attempts-1]!="") taskAttempts[answers[i].attempts-1]+=", ";
                taskAttempts[answers[i].attempts-1]+=(answers[i].task+1);
                totalAttempts+= answers[i].attempts;

                if(answers[i].result==1) correct++;
                else incorrect++;
            }

            lastSecond = 0;
            for(var i=0;i<numSeconds.length;i++){
                if(numSeconds[i]>0){
                    var one;
                    var two;
                    if(lastSecond<60) one = (lastSecond+1)+' seconds';
                    else one = Math.round(lastSecond/60+1)+' minutes';

                    if(seconds[i]<60) two = seconds[i]+' seconds';
                    else two = Math.round(seconds[i]/60)+' minutes';

                    if(i<numSeconds.length-1)
                        time.push(['Between '+one+' and '+ two+': Task '+taskSeconds[i],numSeconds[i]]);
                    else
                        time.push(['More than'+ one+': Task '+taskSeconds[i],numSeconds[i]]);
                }
                lastSecond = seconds[i];
            }

            for(var i=0;i<numAttempts.length;i++){
                attempts.push([(i+1)+' attempt: Task '+taskAttempts[i],numAttempts[i]]);
            }

            var duration = '';
            if(totalTime<60){
                duration = totalTime+' seconds';
            }else{
                duration = Math.round(totalTime/60)+' minutes';
            }
            success.push(["Correct",correct]);
            success.push(["Incorrect",incorrect]);

            var data = google.visualization.arrayToDataTable(time);
            var options = {
                title: 'Time per task / Total: '+duration,
                is3D: true,
                legend: 'none',
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechartTime'));
            chart.draw(data, options);

            var data1 = google.visualization.arrayToDataTable(success);
            var options1 = {
                title: 'Success tasks',
                is3D: true,
                legend: 'none',
                pieSliceText: 'label',
            };
            var chart1 = new google.visualization.PieChart(document.getElementById('piechartSuccess'));
            chart1.draw(data1, options1);

            var data2 = google.visualization.arrayToDataTable(attempts);
            var options2 = {
                title: 'Attempts per task / Total: '+totalAttempts,
                is3D: true,
                legend: 'none',
            };
            var chart2 = new google.visualization.PieChart(document.getElementById('piechartAttempts'));
            chart2.draw(data2, options2);
        }
    </script>
    <style>
        body {
            font-family: 'Droid Sans', sans-serif;
            background-image: url("{{ asset('frontend/css/mosaicgs.png') }}");
        }
    </style>
@endsection
@section('content')
    <script>
        getVariables();
    </script>
    <center>
        <div id="wrapper" align="left">
            <div id="fourth">
                <h1>Summary</h1>
                <center>
                    <h3 id="instruction">
                        Upload Json file.
                    </h3>
                    <input type="file" onchange="onFileSelected(event)">
                </center>

                <h3>Part 1 results</h3>
                <div style="display:block;">
                    <div id="piechartTime"  class="chart" style="width: 340px;height:370px;float:left; display:inline;"></div>
                    <div id="piechartAttempts" class="chart" style="width: 340px;height:370px;float:left; display:inline;"></div>
                    <div id="piechartSuccess"  class="chart" style="width: 270px;height:370px;float:left; display:inline;"></div>
                </div>
                <h3>Part 2 instructions</h3>
                <div id="instructions"></div>
                <h3>Survey answers:</h3>
                <div id="word_questions"></div>
                <br/>
                <script>
                    document.getElementById('word_questions').innerHTML="";
                    for(var i=0;i<survey.length;i++){
                        document.getElementById('word_questions').innerHTML += '<strong>'+survey[i].split(":")[0]+'</strong>: '+survey[i].split(":")[1]+'<br/>';
                    }

                    document.getElementById('instructions').innerHTML="";
                    for(var i=0;i<tasks[0].conf.length;i++){
                        document.getElementById('instructions').innerHTML += '<strong> [Task '+(i+1)+'] Part 	1:</strong> '+tasks[0].conf[i].old_instruction+' <strong>Part 2:</strong> '+tasks[0].conf[i].instruction+'<br/>';
                    }
                </script>
            </div>
        </div>
    </center>
@endsection
@section('footer-section')
@endsection
