var app = getApp();
Page({
	onLoad:function(){
		var that = this;
		wx.request( {
			url:'http://121.41.39.217/zhongcai/getIndexConfig.php',
			data:{},
			header:{
				'Content-Type':'application/json'
			},
			success:function(res) {
				that.setData({
					bannerImgs: res.data.bannerImgs,
					speakerText: res.data.speakerText,
					menu: res.data.menu
				})
			},fail:function(err) {
				that.setData({
					bannerImgs:[
						'http://static.gtimg.com/vd/act/ggimg/201611/c064ebd072a1d4d825449632d817ad86.png',
						'http://static.gtimg.com/vd/act/ggimg/201611/a73b56bcae373ae25d34a13bd844592b.png',
						'https://static.gtimg.com/vd/act/ggimg/201611/bb08daf0525390ec46f6ebc4b1b7083e.png'
					],
					speaker:{
						image: '../../images/xiaolaba.png',
						title: '恭喜[冰蓝***]投注双色球中奖100元',
					},
					suggestCode:{
						title: '[大乐透]3元可中1600万, 还不快行动!',
						subtitle: '每周一,三,六开奖',
						buttonText: '投注2元',
						ballnums:['07','09','24','28','30','06','10']
					},
					lotteryHeader: {
						title: '热门彩种',
					},
					lottery:{
						imgUrls:[
						'../../images/ssq.png',
				        '../../images/dlt.png',
				        '../../images/jczq.png',
				        '../../images/jclq.png',
				        '../../images/fc3d.png',
				        '../../images/dlc.png',
				        '../../images/gdx.png',
				        '../../images/syy.png',
				        'http://static.gtimg.com/vd/act/ggimg/201606/f950cf73a5f601dbc1aceea9367bb4de.png',
				        '../../images/qxc.png',
				        '../../images/r9.png',
				        '../../images/sfc.png',
				        '../../images/pl3.png',
				        '../../images/pl5.png',
				        'http://static.gtimg.com/vd/act/ggimg/201607/0797f14df17b31cb08f75fd8291f36f5.png',
				        'http://static.gtimg.com/vd/act/ggimg/201606/d2a3255344e620de16d1317c7f887d02.png',
				        'http://static.gtimg.com/vd/act/ggimg/201609/17a2e9ac4136008d8f99ad612a5817a6.png'
						],
						title:[
							'双色球',
							'大乐透',
							'竞彩足球',
							'竞彩篮球',
							'福彩3D',
							'赣11选5',
							'粤11选5',
							'鲁11选5',
							'焦点赛事',
							'七星彩',
							'任选9场',
							'胜负彩',
							'排列三',
							'排列五',
							'数字彩资讯',
							'竞技彩资讯',
							'赛单圈'
						],
						subtitle:[
							'二等奖加18888元',
							'会员追号揽19万',
							'热门赛事竞猜',
							'NBA新赛季开战',
							'会员倍投揽36万',
							'中640送60元现金',
							'开奖快中奖更快',
							'快开易中更好玩',
							'猜两场',
							'最高可中500万',
							'猜中9场分奖池',
							'2元可赢500万',
							'简单易中',
							'连爆5注10万大奖',
							'中大奖攻略',
							'赛事新闻预测',
							'晒晒来好运'
						]
					}
				})
			}
		});
	}
})