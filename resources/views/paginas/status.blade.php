<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server status</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script>
	
		/*****************************************
		            Configurations
		*****************************************/
		
		var serverName = document.location.hostname;
		var serverStatusURL = 'http://localhost/server-status';
		var refreshInterval = 5; //seconds
		var itensInLineCharts = 60;
		
		/****************************************/
	
		var chartCPU, optionsChartCPU = {
			width: 150, height: 150,
			redFrom: 90, redTo: 100,
			yellowFrom:75, yellowTo: 90,
			minorTicks: 5
		};
		var chartWorkers, optionsChartWorkers = {
			width: 150, height: 150
		};
		var chartRequests, dataRequests, optionsChartRequests = {
			height: 200,
			vAxis: { title: 'Req/s' },
			legend: { position: 'none' },
			hAxis: { format: 'HH:mm' }
		};
		var chartBytes, dataBytes, optionsChartBytes = {
			height: 200,
			vAxis: { title: 'Kb/s' },
			legend: { position: 'none' },
			hAxis: { format: 'HH:mm' }
		};
		var chartScore,optionsChartScore = {
			height: 100,
			legend: { position: 'none' },
			bar: { groupWidth: '90%' },
			isStacked: true
		};
		
		function initData(){
			$("#serverName").html(serverName);
		
			chartCPU = new google.visualization.Gauge($("#cpu").get(0));
			chartWorkers = new google.visualization.Gauge($("#workers").get(0));
			chartRequests = new google.visualization.LineChart($("#requests").get(0));
			chartBytes = new google.visualization.LineChart($("#bytes").get(0));
			chartScore = new google.visualization.BarChart($("#score").get(0));
			
			dataRequests = new google.visualization.DataTable();
			dataRequests.addColumn('datetime', 'X');
			dataRequests.addColumn('number', 'Req/s');
			
			dataBytes = new google.visualization.DataTable();
			dataBytes.addColumn('datetime', 'X');
			dataBytes.addColumn('number', 'Kb/s');
			
			loadData();
		}
		function loadData(){
			$.get(serverStatusURL+"?auto&"+(new Date().getTime()), function(status){
				status = status.split("\n");
				var vars = {};
				for(var i in status){
					status[i] = status[i].match(/([^:]+):\s*(.+)/);
					if (status[i] != null)
						vars[status[i][1].replace(/\s/g, '')] = status[i][2];
				}
				
				$('#TotalAccesses').html( parseInt(vars.TotalAccesses/1000) + 'M');
				$('#TotalkBytes').html(formatSizeUnits(parseInt(vars.TotalkBytes)*1024));
				$('#Uptime').html(secondsToHms(parseInt(vars.Uptime)));
				
				chartCPU.draw(google.visualization.arrayToDataTable([
					['Label', 'Value'],
					['CPU %', parseFloat((parseFloat(vars.CPULoad||0)).toFixed(1))]
				]), optionsChartCPU);

				var workersBusy = parseInt(vars.BusyWorkers);
				optionsChartWorkers.max = workersBusy + parseInt(vars.IdleWorkers);
				chartWorkers.draw(google.visualization.arrayToDataTable([
					['Label', 'Value'],
					['Workers', workersBusy]
				]), optionsChartWorkers);

				if (dataRequests.getNumberOfRows() > itensInLineCharts)
					dataRequests.removeRow(0);
				dataRequests.addRow([new Date(), parseFloat((parseFloat(vars.ReqPerSec)).toFixed(1))]);
				optionsChartRequests.width = $("#requests").innerWidth();
				chartRequests.draw(dataRequests, optionsChartRequests);

				if (dataBytes.getNumberOfRows() > itensInLineCharts)
					dataBytes.removeRow(0);
				dataBytes.addRow([new Date(), parseInt(vars.BytesPerSec/1024)]);
				optionsChartBytes.width = $("#bytes").innerWidth();
				chartBytes.draw(dataBytes, optionsChartBytes);
				
				var dataScore = google.visualization.arrayToDataTable([
					[
						'Item',
						'Waiting for connection',
						'Starting up',
						'Reading Request',
						'Sending Reply',
						'Keepalive (read)',
						'DNS Lookup',
						'Closing connection',
						'Logging',
						'Gracefully finishing',
						'Idle cleanup of worker'
					],
					[
						'Score',
						vars.Scoreboard.replace(/[^_]/g, '').length,
						vars.Scoreboard.replace(/[^S]/g, '').length,
						vars.Scoreboard.replace(/[^R]/g, '').length,
						vars.Scoreboard.replace(/[^W]/g, '').length,
						vars.Scoreboard.replace(/[^K]/g, '').length,
						vars.Scoreboard.replace(/[^D]/g, '').length,
						vars.Scoreboard.replace(/[^C]/g, '').length,
						vars.Scoreboard.replace(/[^L]/g, '').length,
						vars.Scoreboard.replace(/[^G]/g, '').length,
						vars.Scoreboard.replace(/[^I]/g, '').length
					]
				]);
				optionsChartScore.width = $("#score").innerWidth();
				optionsChartScore.max = vars.Scoreboard.length;
				chartScore.draw(dataScore, optionsChartScore);

				setTimeout(loadData, refreshInterval * 1000);
				
			}, 'text');
		}
		
		function formatSizeUnits(bytes){
			if (bytes >= 1073741824)
				bytes = (bytes/1073741824).toFixed(2) + ' GiB';
			else if (bytes >= 1048576)
				bytes = (bytes/1048576).toFixed(2) + ' MiB';
			else if (bytes >= 1024)
				bytes = (bytes/1024).toFixed(2) + ' KiB';
			return bytes;
		}
		
		function secondsToHms(d){
			var h = Math.floor(d / 3600);
			var m = Math.floor(d % 3600 / 60);
			var s = Math.floor(d % 3600 % 60);
			return ((h > 0 ? h + ":" + (m < 10 ? "0" : "") : "") + m + ":" + (s < 10 ? "0" : "") + s);
		}
		
		google.load("visualization", "1", {packages:['gauge', 'corechart', 'line', 'bar']});
		google.setOnLoadCallback(initData);
	</script>
	<style>
	.gauge{width:200px;display:inline-block}
	.copy{padding-top:35px}
	</style>
  </head>
  <body>
  
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 id="serverName" class="text-center"></h1>
				<div class="row">
				  <div class="col-md-4 text-center">
					<h3><small>Total accesses</small><div id="TotalAccesses"></div></h3>
				  </div>
				  <div class="col-md-4 text-center">
					<h3><small>Total traffic</small><div id="TotalkBytes"></div></h3>
				  </div>
				  <div class="col-md-4 text-center">
					<h3><small>Uptime</small><div id="Uptime"></div></h3>
				  </div>
				</div>				
				<div class="row">
					<div class="col-md-6 text-center">
						<div id="cpu" class="gauge"></div>
					</div>
					<div class="col-md-6 text-center">
						<div id="workers" class="gauge"></div>
					</div>
					<div class="col-md-6">
						<div id="requests" class="lines"></div>
					</div>
					<div class="col-md-6">
						<div id="bytes" class="lines"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div id="score"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center copy">
				<a href="https://github.com/dioubernardo/apacheServerStatusCharts" target="_blank"><small><span class="glyphicon glyphicon-grain"></span> apacheServerStatusCharts on github</small></a>
			</div>
		</div>
	</div>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
