window.onload = function() {

	var chart = new CanvasJS.Chart("quota", {
		animationEnabled: true,
		title: {
			text: title,
			fontColor: '#2A6794',
			fontFamily: 'Share,Verdana,Arial,Helvetica,sans-serif'
		},
		data: [{
			type: "doughnut",
			startAngle: 0,
			yValueFormatString: "##0.00\"%\"",
			indexLabel: "{label} {y}",
			dataPoints: quotas
		}]
	});
	chart.render();
};

$('.btn-loader').on('click', function () {
	var $btn = $(this).button('loading')
	// business logic...
	$btn.button('reset')
});
