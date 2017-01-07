$(document).ready(function() {
 
	// Balance
	initBalance();
});

// Initicialize the functions about the "Balance" section
function initBalance()
{
	google.charts.load('current', {packages: ['corechart']});
	google.charts.setOnLoadCallback(drawChart);

	// Reload Charts
	$('body').on('click', '#reload-charts', function(event) {
		event.preventDefault();
		reload_charts();
	});
}

// 
function drawChart()
{
	var data = google.visualization.arrayToDataTable([
		['Mes', 'Ingresos', 'Gastos'],
		['Enero', 1000, 400],
		['Febrero', 1170, 460],
		['Marzo', 660, 1120],
		['Abril', 1030, 540],
		['Mayo', 1030, 540],
		['Junio', 650, 1300],
		['Julio', 420, 150]
	]);

	var options = {
		title: 'Ingresos y Gastos',
		hAxis: {title: 'Mes',  titleTextStyle: {color: '#333'}},
		vAxis: {minValue: 0}
	};

	var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
	chart.draw(data, options);
}

// 
function reload_charts()
{
	//	Ajax Load data
	$.ajax({
		url : "panel/balance",
		type: "GET",
		dataType: "JSON",
		success: function(result)
		{
			var data = google.visualization.arrayToDataTable(
				result
			);

			var options = {
				title: 'Ingresos y Gastos',
				hAxis: {title: 'Mes',  titleTextStyle: {color: '#333'}},
				vAxis: {minValue: 0}
			};

			var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}