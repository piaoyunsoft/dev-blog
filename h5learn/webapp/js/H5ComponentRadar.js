// 雷达图组件资源

var H5ComponentRadar = function(name, cfg) {
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

	// 画圆
	var radius = width / 2;
	var step = cfg.data.length;

	// 计算一个圆周上的坐标 (计算多边形的顶点坐标)
	// 绘制网格背景 (分面绘制, 分为10份)
	var isBlue = false;
	for (var s=10;s>0;s--) {
		ctx.beginPath();
		for (var i=0;i<step;i++) {
			var rad = (2*Math.PI / 360) * (360 / step) * i;
			var x = radius + Math.sin(rad) * radius * (s/10);
			var y = radius + Math.cos(rad) * radius * (s/10);

			ctx.lineTo(x, y);
		}
		ctx.closePath();
		ctx.fillStyle = (isBlue = !isBlue) ? '#99c0ff' : '#f1f9ff';
		ctx.fill();
	}

	// 绘制伞骨
	for (var i=0;i<step;i++) {
		var rad = (2*Math.PI / 360) * (360 / step) * i;
		var x = radius + Math.sin(rad) * radius;
		var y = radius + Math.cos(rad) * radius;

		ctx.moveTo(radius, radius);
		ctx.lineTo(x, y);

		// 输出项目文字
		var text = $('<div class="text">');
		text.text(cfg.data[i][0]);
		text.css('transition', 'all .5s ' + i*0.1 + 's');
		
		if (x > width/2) {
			text.css('left', x/2 + 5);
		} else {
			text.css('right', (width-x)/2 + 5);
		}
		if (y > height/2) {
			text.css('top', y/2 + 5);
		} else {
			text.css('bottom', (height-y)/2 + 5);
		}
		if (cfg.data[i][2]) {
			text.css('color', cfg.data[i][2]);
		}
		text.css('opacity', 0);

		component.append(text);
	}
	ctx.strokeStyle = '#e0e0e0';
	ctx.stroke();

	// 数据层开发
	// 加入一个画布(网格线背景) - 数据层
	var cns = document.createElement('canvas');
	var ctx = cns.getContext('2d');
	cns.width = ctx.width = width;
	cns.height = ctx.height = height;
	component.append(cns);

	ctx.strokeStyle = '#f00';
	var draw = function(per) {
		if (per >= 0) {
			component.find('.text').css('opacity', 0);
		}
		if (per >= 1) {
			component.find('.text').css('opacity', 1);
		}

		ctx.clearRect(0, 0, width, height);
		// 输出数据的折线
		for (var i=0;i<step;i++) {
			var rad = (2*Math.PI / 360) * (360 / step) * i;
			var rate = cfg.data[i][1] * per;
			var x = radius + Math.sin(rad) * radius * rate;
			var y = radius + Math.cos(rad) * radius * rate;

			ctx.lineTo(x, y);
		}
		ctx.closePath();
		ctx.stroke();

		// 输出数据的点
		ctx.fillStyle = '#ff7676';
		for (var i=0;i<step;i++) {
			var rad = (2*Math.PI / 360) * (360 / step) * i;
			var rate = cfg.data[i][1] * per;
			var x = radius + Math.sin(rad) * radius * rate;
			var y = radius + Math.cos(rad) * radius * rate;

			ctx.beginPath();
			ctx.arc(x, y, 5, 0, 2*Math.PI);
			ctx.fill();
			ctx.closePath();
		}
	}

	component.on('onLoad', function() {
		// 雷达图生长动画
		var s = 0;
		for (i=0;i<100;i++) {
			setTimeout(function() {
				s += 0.01;
				draw(s);
			}, i * 10 + 500);
		}
	});

	component.on('onLeave', function() {
		// 雷达图退场动画
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