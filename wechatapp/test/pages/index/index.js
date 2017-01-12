//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: '',
    userInfo: {}
  },
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    var that = this
    app.getUserInfo(function(userInfo){
      that.setData({
        userInfo:userInfo
      })
    })
    wx.request( {
      url:'https://wuxueying.xyz/wechatappserver/test/getData.php',
			data:{},
			header:{
				'Content-Type':'application/json'
			},
			success:function(res) {
				that.setData({
          motto: res.data.name
				})
        console.log('success');
        console.log(res);
			},fail:function(err) {
				console.log('fail');
			}
    });
  }
})
