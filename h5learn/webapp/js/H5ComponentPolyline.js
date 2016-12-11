var H5ComponentPolyline = function(name, cfg) {
	var component = new H5ComponentBase(name, cfg);

	// 绘制网格线
	var width = cfg.width;
	var height = cfg.height;

	// 加入一个画布(网格线背景) - 背景层
	var cns = document.createElement('canvas');
	var ctx = cns.getContext('2d');
	cns.width = ctx.width = width;
	cns.height = ctx.height = height;
	component.append(cns);

	// 水平网格线 100份->10份
	var step = 10;
	ctx.beginPath();
	ctx.lineWidth = 1;
	ctx.strokeStyle = "#aaaaaa";

	window.ctx = ctx;
	for (var i=0;i<=step;i++) {
		var y = (height / step) * i;
		ctx.moveTo(0, y);
		ctx.lineTo(width, y);
	}

	// 垂直网格线 (根据项目的个数去分)
	step = cfg.data.length + 1;
	var text_width = width / step >> 0;
	for (var i=0;i<step + 1;i++) {
		var x = (width / step) * i;
		ctx.moveTo(x, 0);
		ctx.lineTo(x, height);

		if (cfg.data[i]) {
			var text = $('<div class="text">');
			text.text(cfg.data[i][0]);
			text.css('width', text_width / 2).css('left', (x/2 - text_width/4) + text_width/2);

			component.append(text);
		}
	}

	ctx.stroke();

	// 加入画布 - 数据层
	var cns = document.createElement('canvas');
	var ctx = cns.getContext('2d');
	cns.width = ctx.width = width;
	cns.height = ctx.height = height;
	component.append(cns);

	// 绘制折现以及对应的数据和阴影
	var draw = function(per) {
		// 清空画布
		ctx.clearRect(0, 0, width, height);

		// 绘制折线数据
		ctx.beginPath();
		ctx.lineWidth = 3;
		ctx.strokeStyle = "#ff8878";

		var x = 0;
		var y = 0;
		var row_width = (width / (cfg.data.length + 1));
		// 画点
		for (i in cfg.data) {
			var item = cfg.data[i];
			x = row_width * i + row_width;
			y = height - (item[1] * height * per);
			ctx.moveTo(x, y);
			ctx.arc(x, y, 5, 0, 2 * Math.PI);
		}

		// 连线
		// 移动画笔到第一个数据的点位置
		ctx.moveTo(row_width, height - (cfg.data[0][1] * height * per));
		for (i in cfg.data) {
			var item = cfg.data[i];
			x = row_width * i + row_width;
			y = height - (item[1] * height * per);
			ctx.lineTo(x, y);
		}
		ctx.stroke();

		ctx.lineWidth = 1;
		ctx.strokeStyle = 'rgba(255, 136, 120, 0)';

		// 绘制阴影
		ctx.lineTo(x, height);
		ctx.lineTo(row_width, height);
		ctx.fillStyle = 'rgba(255, 136, 120, 0.2)';
		ctx.fill();

		// 写文字数据
		for (i in cfg.data) {
			var item = cfg.data[i];
			x = row_width * i + row_width;
			y = height - (item[1] * height * per);
			ctx.fillStyle = item[2] ? item[2] : '#595959';
			ctx.fillText(((item[1] * 100) >> 0) + '%', x - 10, y - 10);
		}
		ctx.stroke();
	}

	component.on('onLoad', function() {
		// 折现图生长动画
		var s = 0;
		for (i=0;i<100;i++) {
			setTimeout(function() {
				s += 0.01;
				draw(s);
			}, i * 10 + 500);
		}
	});

	component.on('onLeave', function() {
		// 折现图退场动画
		var s = 1;
		for (i=0;i<100;i++) {
			setTimeout(function() {
				s -= 0.01;
				draw(s);
			}, i * 10);
		}
	});

	return component;
}