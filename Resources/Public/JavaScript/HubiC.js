function graphe_camembert() {

	var canvas = document.getElementById('camembert');

	if (!canvas) return;
	var context = canvas.getContext('2d');

	var percentQuotaUsed = (100 / quotaTotal) * quotaUsed;

	// donnees en pourcentage
	var donnees = [percentQuotaUsed, 100 - percentQuotaUsed];

	var diametre = Math.min(canvas.height, canvas.width) - 10;
	var rayon = diametre / 2;

	// position du centre du camembert
	var position_x = canvas.width / 4;
	var position_y = canvas.height / 2;

	var angle_initial = 0;
	var stylecolors = ['#126998', '#EDEDED'];

	var largeur_rect = 15;

	for (var i = 0; i < donnees.length; i++) {
		var angles_degre = (donnees[i] / 100) * 360;// conversion pourcentage -> angle en degré
		DessinerAngle(context, position_x, position_y, rayon, angle_initial, angles_degre, stylecolors[i]);
		angle_initial += angles_degre;

		DessinerRectangle(
			context,
			canvas.width / 2 + 10,
			(largeur_rect + 3) * (i + 1),
			largeur_rect, largeur_rect,
			stylecolors[i]
		);
		context.font = '8pt \'OpenSans\',Helvetica,Arial,sans-serif';//legendes
		context.fillStyle = '#000';//legendes
		context.fillText(legendes[i] + ' (' + Math.round(donnees[i], 2) + ' %)', canvas.width /2 + 30, 18 * i + 30);//legendes
	}
}

// petit rectangle pour la légende
function DessinerRectangle(context, x0, y0, xl, yl, coloration) {
	context.beginPath();
	context.fillStyle = coloration;
	context.fillRect(x0, y0, xl, yl);
	context.closePath();
	context.fill();
}

function DessinerAngle(context, position_x, position_y, rayon, angle_initial, angles_degre, couleurs) {
	context.beginPath();
	context.fillStyle = couleurs;
	var angle_initial_radian = angle_initial / (180 / Math.PI);// conversion angle en degré -> angle en radian
	var angles_radian = angles_degre / (180 / Math.PI);// conversion angle en degré -> angle en radian
	context.arc(position_x, position_y, rayon, angle_initial_radian, angle_initial_radian + angles_radian, 0);
	context.lineTo(position_x, position_y);
	context.closePath();
	context.fill();
}

window.addEventListener("load", graphe_camembert, false);
