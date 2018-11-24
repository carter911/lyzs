<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:32:"template/lingyi/index/index.html";i:1541493326;s:77:"/Users/zhongwu/code/chenrj/fastadmin/public/template/lingyi/layout/index.html";i:1541746833;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
	<link href="/assets/css/frontend/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/frontend/style.css" rel="stylesheet">
	<link href="/assets/css/frontend/index.css" rel="stylesheet">
	<link href="/assets/css/frontend/highend.css" rel="stylesheet">
	<link href="/assets/css/frontend/personality.css" rel="stylesheet">
	<link href="/assets/css/frontend/detail.css" rel="stylesheet">
	<link href="/assets/css/frontend/contactselet.css" rel="stylesheet">
	<script type="text/javascript" src="/assets/js/frontend/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/frontend/current.js"></script>
	<script type="text/javascript" src="/assets/js/frontend/recordRoll.js"></script>
	<script src="/assets/js/frontend/bootstrap.min.js"></script>
</head>
<body style="min-width: 1200px;">
<!-------------------------------顶部导航条------------------------------------------->
<div class=" toparea" style="width: 1200px;margin: 0 auto;text-align: left">
	<div class="col-xs-3" style="padding: 0px;">
		<a style="padding: 0px;"  href="/index.html"> <img style="width: 245px;" height="55px" src="<?php echo $site['logo']; ?>" class="img-responsive"></a>
	</div>
	<div class="col-xs-6">
		<form class="form-inline nav_searcharea">
			<div class="form-group">
				<div class="input-group">
					<div style="padding: 0px 7px;"  class="input-group-addon padding_c addon_area addon_left ">
						<select class="nav_select" style="background: none">
							<option class="nav_option">案例</option>
							<option class="nav_option">施工直播</option>
							<option class="nav_option">设计师</option>
							<option class="nav_option">活动资讯</option>
							<option class="nav_option">最新活动</option>
						</select>
					</div>
					<input type="text" class="form-control" id="nav_serarch" placeholder="关键词">
					<div class="input-group-addon padding_c addon_area">
						<button type="submit" class="btn btn-primary nav_searchbtn">搜索</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	
	<div class="col-xs-3 telecom_area">
		<img src="/assets/img/frontend/telecom.jpg" class="img-responsive pull-left">
		<h1 class="pull-left margin_c"><?php echo $site['telphone']; ?></h1>
		<div class="clearfix"></div>
	</div>
	
	<div class="clearfix"></div>
</div>

<div class="top_nav">
	<div class="nav_content text-center" style="width: 1200px;margin: 0 auto;text-align: left">
		
		<ul class="list-inline margin_c">
			<?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?>
			<li ><a href="<?php echo $m['url']; ?>"><?php echo $m['name']; ?></a></li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			<!--<li><a href="/index/brandstr/index.html">品牌实力</a></li>-->
			<!--<li><a href="personality.html">岭艺个性家</a></li>-->
			<!--<li><a href="setmeal.html">套餐</a></li>-->
			<!--<li><a href="highend.html">高端定制</a></li>-->
			<!---->
			<!--<li class="dropdown">-->
				<!--<a href="realcase.html" class="dropdown-toggle" data-toggle="dropdown" role="button"-->
				   <!--aria-haspopup="true" aria-expanded="false">案例时光机</a>-->
				<!--<ul class="list-unstyled">-->
					<!--<li><a href="realcase.html">实景案例</a></li>-->
					<!--<li><a href="panorama.html">设计案例</a></li>-->
				<!--</ul>-->
			<!--</li>-->
			<!---->
			<!--<li><a href="construction.html">施工直播</a></li>-->
			<!--<li><a href="designgroup.html">设计团队</a></li>-->
			<!---->
			<!--<li class="dropdown">-->
				<!--<a href="activity.html" class="dropdown-toggle" data-toggle="dropdown" role="button"-->
				   <!--aria-haspopup="true" aria-expanded="false">岭艺动态</a>-->
				<!--<ul class="list-unstyled">-->
					<!--<li><a href="activity.html" class="innlink">最新活动</a></li>-->
					<!--<li><a href="install.html">家居资讯</a></li>-->
				<!--</ul>-->
			<!--</li>-->
			<!--<li><a href="quality.html">售后保障</a></li>-->
			<!--<li><a href="contact.html">联系我们</a></li>-->
		</ul>
	</div>

</div>

<!---------------------------------主体内容---------------------------------------------->

<div>
	<!---------------------------------主体内容---------------------------------------------->
<div style="overflow: hidden">
	<div style="width: 1200px;margin: 0 auto;position: relative">
		<div class="ban_quote">
			<div class="quote_area">
				<h4 class="quote_title text-center">家装快速报价</h4>
				<div class="quote_intro">
					<p class="text-center">报价结果</p>
					<p class="text-center">将以短信发送到您的手机</p>
				</div>
				
				<div class="quote_form">
					<form>
						<div class="form-group">
							<input type="text" class="form-control" id="quote_address" placeholder="请输入您的称呼">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="quote_phone" placeholder="收取报价手机号码">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="quote_space" placeholder="产证面积（单位：㎡）">
						</div>
					</form>
					<form class="form-inline">
						<div class="form-group quote_room col-xs-4 padding_c">
							<input type="text" class="form-control roomnumber" id="quote_chamber" placeholder="几"
								   style="width: 46px;">
							<label for="quote_chamber">室</label>
						</div>
						<div class="form-group quote_room col-xs-4 padding_c">
							<input type="text" class="form-control roomnumber" id="quote_parlour" placeholder="几"
								   style="width: 46px">
							<label for="quote_parlour">厅</label>
						</div>
						<div class="form-group quote_room col-xs-4 padding_c">
							<input type="text" class="form-control roomnumber" id="quote_toilet" placeholder="几"
								   style="width: 46px">
							<label for="quote_toilet">卫</label>
						</div>
					</form>
					<a href="add_customer()" class="btn btn-default quote_sub">免&nbsp;费&nbsp;获&nbsp;取</a>
				</div>
				
				<div class="quote_user">
					<p class="text-center">已有<span style="color: #E00024;">231</span>位获取报价</p>
					
					<div class="quote_roll">
						<div class="box">
							<div class="record_list">
								<p>t**&nbsp;185*******2提交预约信息<span class="app_date">[07/15]</span></p>
								<p>t**&nbsp;157*******0提交预约信息<span class="app_date">[07/15]</span></p>
								<p>刘**&nbsp;152*******1提交预约信息<span class="app_date">[07/15]</span></p>
								<p>t**&nbsp;138*******6提交预约信息<span class="app_date">[07/15]</span></p>
								<p>l**&nbsp;133*******6提交预约信息<span class="app_date">[07/15]</span></p>
								<p>t**&nbsp;185*******2提交预约信息<span class="app_date">[07/15]</span></p>
								<p>t**&nbsp;185*******2提交预约信息<span class="app_date">[07/15]</span></p>
								<p>t**&nbsp;185*******2提交预约信息<span class="app_date">[07/15]</span></p>
								<p>t**&nbsp;185*******2提交预约信息<span class="app_date">[07/15]</span></p>
								<p>t**&nbsp;185*******2提交预约信息<span class="app_date">[07/15]</span></p>
							</div>
						</div>
					</div>
				
				</div>
			
			</div>
		</div>
	</div>
	<img style="width: 100%" src="/assets/img/frontend/banner01.png" class="img-responsive center-block">
</div>
<div class="container" style="width: 1200px;margin: 0 auto">
	<div class="lis_personalink">
		<div class="personalink_title">
			<h2 class="pull-left lis_title" style=""><a href="personality.html">岭艺个性家&nbsp;/&nbsp;Lingyi Personalized
				house</a></h2>
			<a href="personality.html" class="pull-right">了解详情&nbsp;&gt;</a>
			<div class="clearfix"></div>
		</div>
		<a href="personality.html"><img src="/assets/img/frontend/personial.png"
										class="img-responsive center-block"></a>
	</div>
	
	<div class="lis_personalink">
		<div class="personalink_title">
			<h2 class="pull-left lis_title"><a href="setmeal.html">套餐&nbsp;/&nbsp;598乐享温馨家</a></h2>
			<a href="personality.html" class="pull-right">了解详情&nbsp;&gt;</a>
			<div class="clearfix"></div>
		</div>
		<a href="setmeal.html"><img src="/assets/img/frontend/setmeal.png" class="img-responsive center-block"></a>
	</div>
	
	<div class="lis_personalink">
		<div class="personalink_title">
			<h2 class="pull-left lis_title"><a href="livecontrust.html">施工直播&nbsp;/&nbsp;Construction of live</a></h2>
			<a href="personality.html" class="pull-right">了解详情&nbsp;&gt;</a>
			<div class="clearfix"></div>
		</div>
		
		<div class="cons_live">
			<img src="/assets/img/frontend/cons_process_top.gif" class="img-responsive center-block">
			<div class="cons_process_center">
				<img src="/assets/img/frontend/cons_process_center.jpg" class="img-responsive center-block">
				<a href="#" class="left_btn btn">了解更多</a>
				<a href="#" class="right_btn btn"></a>
			</div>
			<img src="/assets/img/frontend/cons_process_bot.gif" class="img-responsive center-block">
		</div>
	</div>
	
	<div class="lis_personalink">
		<div class="personalink_title">
			<h2 class="pull-left lis_title"><a href="designer.html">设计师及完工案例</a></h2>
			<a href="personality.html" class="pull-right">了解详情&nbsp;&gt;</a>
			<div class="clearfix"></div>
		</div>
		
		<div class="desinger_case row">
			<div class="col-xs-3 gold_team">
				<img src="/assets/img/frontend/gold_team.png" class="img-responsive center-block">
				
				<div id="carousel-gold_team" class="carousel slide" data-ride="carousel" style="margin-top: -1px;">
					<div class="carousel-inner" role="listbox" style="background-color: #000;">
						<div class="item active">
							<img src="/assets/img/frontend/gold_team01.png" class="img-responsive center-block">
						</div>
						<div class="item">
							<img src="/assets/img/frontend/gold_team02.png" class="img-responsive center-block">
						</div>
						<div class="item">
							<img src="/assets/img/frontend/gold_team03.png" class="img-responsive center-block">
						</div>
					</div>
					
					<a class="left carousel-control" href="#carousel-gold_team" role="button" data-slide="prev"
					   style="top: 10%">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-gold_team" role="button" data-slide="next"
					   style="right: 0;top: 10%">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
			
			<div class="col-xs-3">
				<div class="goldcase_area">
					<div class="gold_caselis text-center" style="margin-bottom: 30px">
						<img src="/assets/img/frontend/gold_case02.png">
						<div class="gold-content">
							<h5 class="title" style="padding-top: 0">汤泉&nbsp;·&nbsp;美地城</h5>
							<ul class="goldcase_info">
								<li class="text-left"><a href="#"><p>现代简约</p></a></li>
								<li class="text-left"><a href="#"><p>别墅&nbsp;/&nbsp;联排&nbsp;/&nbsp;300㎡</p></a></li>
								<li class="text-left"><a href="#"><p>半包总价xxx</p></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="goldcase_area">
					<div class="gold_caselis text-center">
						<img src="/assets/img/frontend/gold_case05.png">
						<div class="gold-content">
							<h5 class="title" style="padding-top: 0">汤泉&nbsp;·&nbsp;美地城</h5>
							<ul class="goldcase_info">
								<li class="text-left"><a href="#"><p>现代简约</p></a></li>
								<li class="text-left"><a href="#"><p>别墅&nbsp;/&nbsp;联排&nbsp;/&nbsp;300㎡</p></a></li>
								<li class="text-left"><a href="#"><p>半包总价xxx</p></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xs-3">
				<div class="goldcase_area">
					<div class="gold_caselis text-center" style="margin-bottom: 30px">
						<img src="/assets/img/frontend/gold_case03.png">
						<div class="gold-content">
							<h5 class="title" style="padding-top: 0">汤泉&nbsp;·&nbsp;美地城</h5>
							<ul class="goldcase_info">
								<li class="text-left"><a href="#"><p>现代简约</p></a></li>
								<li class="text-left"><a href="#"><p>别墅&nbsp;/&nbsp;联排&nbsp;/&nbsp;300㎡</p></a></li>
								<li class="text-left"><a href="#"><p>半包总价xxx</p></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="goldcase_area">
					<div class="gold_caselis text-center">
						<img src="/assets/img/frontend/gold_case06.png">
						<div class="gold-content">
							<h5 class="title" style="padding-top: 0">汤泉&nbsp;·&nbsp;美地城</h5>
							<ul class="goldcase_info">
								<li class="text-left"><a href="#"><p>现代简约</p></a></li>
								<li class="text-left"><a href="#"><p>别墅&nbsp;/&nbsp;联排&nbsp;/&nbsp;300㎡</p></a></li>
								<li class="text-left"><a href="#"><p>半包总价xxx</p></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xs-3">
				<div class="goldcase_area">
					<div class="gold_caselis text-center" style="margin-bottom: 30px">
						<img src="/assets/img/frontend/gold_case01.png">
						<div class="gold-content">
							<h5 class="title" style="padding-top: 0">汤泉&nbsp;·&nbsp;美地城</h5>
							<ul class="goldcase_info">
								<li class="text-left"><a href="#"><p>现代简约</p></a></li>
								<li class="text-left"><a href="#"><p>别墅&nbsp;/&nbsp;联排&nbsp;/&nbsp;300㎡</p></a></li>
								<li class="text-left"><a href="#"><p>半包总价xxx</p></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="goldcase_area">
					<div class="gold_caselis text-center">
						<img src="/assets/img/frontend/gold_case04.png">
						<div class="gold-content">
							<h5 class="title" style="padding-top: 0">汤泉&nbsp;·&nbsp;美地城</h5>
							<ul class="goldcase_info">
								<li class="text-left"><a href="#"><p>现代简约</p></a></li>
								<li class="text-left"><a href="#"><p>别墅&nbsp;/&nbsp;联排&nbsp;/&nbsp;300㎡</p></a></li>
								<li class="text-left"><a href="#"><p>半包总价xxx</p></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="lis_personalink">
		<div class="personalink_title">
			<h2 class="pull-left lis_title"><a href="activity.html">岭艺动态&nbsp;/&nbsp;Lingyi Dynamic</a></h2>
			<a href="personality.html" class="pull-right">了解详情&nbsp;&gt;</a>
			<div class="clearfix"></div>
		</div>
		
		<div class="dynamic_area">
			<div class="row">
				<div class="col-xs-5" style="padding-right: 0">
					<div class="dynamic_lis">
						<a href="#"><img src="/assets/img/frontend/dynamic_img.png" class="img-responsive center-block"></a>
					</div>
				</div>
				
				<div class="col-xs-7">
					<div class="dynamic_lis dynamic_art">
						<p>/&nbsp;2018.5.20</p>
						<h2 class="margin_c"><a href="#" class="dynamic_title">深度信息化，用互联网助发展，让口碑深入人心</a></h2>
					</div>
					<div class="dynamic_lis dynamic_art">
						<p>/&nbsp;2018.5.20</p>
						<h2 class="margin_c"><a href="#" class="dynamic_title">深度信息化，用互联网助发展，让口碑深入人心</a></h2>
					</div>
					<div class="dynamic_lis dynamic_art">
						<p>/&nbsp;2018.5.20</p>
						<h2 class="margin_c"><a href="#" class="dynamic_title">深度信息化，用互联网助发展，让口碑深入人心</a></h2>
					</div>
					<div class="dynamic_lis dynamic_art" style="margin-bottom: 0">
						<p>/&nbsp;2018.5.20</p>
						<h2 class="margin_c"><a href="#" class="dynamic_title">深度信息化，用互联网助发展，让口碑深入人心</a></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="lis_personalink margin_t">
		<div class="chuntcase_lis">
			<div class="case_list">
				<div class="case_villa">
					<div class="culture_link">
						<img src="/assets/img/frontend/culutre_link.png"
							 class="img-responsive center-block culture_icon">
						<h4 class="text-center"><a href="#">岭&nbsp;艺&nbsp;文&nbsp;化</a></h4>
					</div>
				</div>
			</div>
			
			<div class="case_list">
				<div class="case_villa">
					<div class="culture_link">
						<img src="/assets/img/frontend/competitive_link.png"
							 class="img-responsive center-block culture_icon">
						<h4 class="text-center"><a href="#">精&nbsp;品&nbsp;工&nbsp;地</a></h4>
					</div>
				</div>
			</div>
			
			<div class="case_list">
				<div class="case_villa">
					<div class="culture_link">
						<img src="/assets/img/frontend/team_link.png" class="img-responsive center-block culture_icon">
						<h4 class="text-center"><a href="#">设&nbsp;计&nbsp;团&nbsp;队</a></h4>
					</div>
				</div>
			</div>
			
			<div class="case_list">
				<div class="case_villa">
					<div class="culture_link">
						<img src="/assets/img/frontend/720_link.png" class="img-responsive center-block culture_icon">
						<h4 class="text-center"><a href="#">720&nbsp;全&nbsp;景</a></h4>
					</div>
				</div>
			</div>
			
			<div class="case_list">
				<div class="case_villa">
					<div class="culture_link">
						<img src="/assets/img/frontend/activity_link.png"
							 class="img-responsive center-block culture_icon">
						<h4 class="text-center"><a href="#">钜&nbsp;惠&nbsp;活&nbsp;动</a></h4>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		
		<img src="/assets/img/frontend/Phone.png" class="img-responsive center-block" style="margin-top: 50px">
		<h3 class="text-center"><b>联系电话</b></h3>
		<h1 class="text-center"><b>400-000-0000</b></h1>
	</div>
</div>

<div class="lis_cust">
	<div id="demo" align="center">
		<table border="0" align="center" cellpadding="0" cellspacing="0" cellspace="0">
			<tbody>
			<tr>
				<td valign="top" id="demo1">
					<table cellspacing="5" cellpadding="0">
						<tbody>
						<tr class="roll_area">
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq1.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq2.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq3.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq1.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq2.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq3.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq1.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq2.jpg"></td>
							<td class="roll_img text-center"><img src="/assets/img/frontend/jq3.jpg"></td>
						</tr>
						</tbody>
					</table>
				</td>
				<td id="demo2" valign="top"></td>
			</tr>
			</tbody>
		</table>
	</div>
	
	<script>
        var speed = 30
        demo2.innerHTML = demo1.innerHTML

        function Marquee() {
            if (demo2.offsetWidth - demo.scrollLeft <= 0)
                demo.scrollLeft -= demo1.offsetWidth
            else {
                demo.scrollLeft++
            }
        }

        var MyMar = setInterval(Marquee, speed)
        demo.onmouseover = function () {
            clearInterval(MyMar)
        }
        demo.onmouseout = function () {
            MyMar = setInterval(Marquee, speed)
        }
	</script>
</div>

<!----------------------------------固定区域--------------------------------------------->
<div class="bot_scroll navbar-inverse container-fluid navbar-fixed-bottom">
	<div class="container fix_content">
		<div class="row">
			<div class="col-xs-1">
				<img src="/assets/img/frontend/bot_scro1.gif" class="img-responsive">
			</div>
			<div class="col-xs-8">
				<form class="form-inline count_area row">
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="iptname" placeholder="请输入您的名字" style="width: 130px">
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="mobile" placeholder="手机号"
							   style="width: 130px;margin-left: 8px">
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control pull-right" id="space" placeholder="房产证面积"
							   style="width: 100px;margin-left: 10px;">
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="room" placeholder="输入数字"
							   style="width: 78px;margin-left: 5px">
						<label for="room" class="color_f" style="font-size: 18px">室</label>
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="toilet" placeholder="输入数字" style="width: 80px">
						<label for="toilet" class="color_f" style="font-size: 18px">卫</label>
					</div>
					<div class="col-xs-2">
						<button type="submit" class="btn search_btn sub_btn color_f"
								style="border-radius: 4px;background-color: #0099DD">开始计算报价
						</button>
					</div>
				
				</form>
			</div>
			<div class="col-xs-3">
				<img src="/assets/img/frontend/telimg.png" class="img-responsive telnum">
			</div>
		</div>
	
	</div>
</div>

<div class="rightfix_bar">
	<ul class="list-unstyled">
		<li><a href="#top"><img src="/assets/img/frontend/1.png" onmouseover="this.src='/assets/img/frontend/1_1.png'"
								onmouseout="this.src='/assets/img/frontend/1.png'" class="img-responsive center-block"></a>
		</li>
		<li><a href="#"><img src="/assets/img/frontend/2.png" onmouseover="this.src='/assets/img/frontend/2_2.png'"
							 onmouseout="this.src='/assets/img/frontend/2.png'" class="img-responsive center-block"></a>
		</li>
		<li><a href="#"><img src="/assets/img/frontend/3.png" onmouseover="this.src='/assets/img/frontend/3_3.png'"
							 onmouseout="this.src='/assets/img/frontend/3.png'" class="img-responsive center-block"></a>
		</li>
		<li><a href="#"><img src="/assets/img/frontend/4.png" onmouseover="this.src='/assets/img/frontend/4_4.png'"
							 onmouseout="this.src='/assets/img/frontend/4.png'" class="img-responsive center-block"></a>
		</li>
		<li><img style="z-index: 99999" width="100px" src="/assets/img/frontend/rightfix_ad.gif"
				 class="img-responsive center-block"></li>
	</ul>
</div>
</div>

<!----------------------------------固定区域--------------------------------------------->
<div class="bot_scroll navbar-inverse container-fluid navbar-fixed-bottom">
	<div class="container fix_content">
		<div class="row">
			<div class="col-xs-1">
				<img src="/assets/img/frontend/bot_scro1.gif" class="img-responsive">
			</div>
			<div class="col-xs-8">
				<form class="form-inline count_area row">
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="iptname" placeholder="请输入您的名字" style="width: 130px">
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="mobile" placeholder="手机号"
							   style="width: 130px;margin-left: 8px">
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control pull-right" id="space" placeholder="房产证面积"
							   style="width: 100px;margin-left: 10px;">
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="room" placeholder="输入数字"
							   style="width: 78px;margin-left: 5px">
						<label for="room" class="color_f" style="font-size: 18px">室</label>
					</div>
					<div class="form-group col-xs-2">
						<input type="text" class="form-control" id="toilet" placeholder="输入数字" style="width: 80px">
						<label for="toilet" class="color_f" style="font-size: 18px">卫</label>
					</div>
					<div class="col-xs-2">
						<button type="submit" class="btn search_btn sub_btn color_f"
								style="border-radius: 4px;background-color: #0099DD">开始计算报价
						</button>
					</div>
				
				</form>
			</div>
			<div class="col-xs-3">
				<span style="font-size: 20px;color: #fff;line-height: 50px;">免费报价：<?php echo $site['telphone']; ?></span>
				<!--<img src="/assets/img/frontend/telimg.png" class="img-responsive telnum">-->
			</div>
		</div>
	
	</div>
</div>

<div class="rightfix_bar">
	<ul class="list-unstyled">
		<li><a href="#top"><img src="/assets/img/frontend/1.png" onmouseover="this.src='/assets/img/frontend/1_1.png'"
								onmouseout="this.src='/assets/img/frontend/1.png'" class="img-responsive center-block"></a>
		</li>
		<li><a href="#"><img src="/assets/img/frontend/2.png" onmouseover="this.src='/assets/img/frontend/2_2.png'"
							 onmouseout="this.src='/assets/img/frontend/2.png'" class="img-responsive center-block"></a>
		</li>
		<li><a href="#"><img src="/assets/img/frontend/3.png" onmouseover="this.src='/assets/img/frontend/3_3.png'"
							 onmouseout="this.src='/assets/img/frontend/3.png'" class="img-responsive center-block"></a>
		</li>
		<li><a href="#"><img src="/assets/img/frontend/4.png" onmouseover="this.src='/assets/img/frontend/4_4.png'"
							 onmouseout="this.src='/assets/img/frontend/4.png'" class="img-responsive center-block"></a>
		</li>
		<li><img style="z-index: 99999" width="100px" src="/assets/img/frontend/rightfix_ad.gif"
				 class="img-responsive center-block"></li>
	</ul>
</div>

<!-----------------------------------底部导航------------------------------------->
<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-3">
				<h4>关于岭艺装饰</h4>
				<div class="row">
					<div class="col-xs-6">
						<ul>
							<li class="listnon"><a href="#">公司简介</a></li>
							<li class="listnon"><a href="#">品牌实力</a></li>
							<li class="listnon"><a href="#">岭艺动态</a></li>
							<li class="listnon"><a href="#">招聘信息</a></li>
						</ul>
					</div>
					<div class="col-xs-6">
						<ul>
							<li class="listnon"><a href="#">岭艺个性家</a></li>
							<li class="listnon"><a href="#">598乐享温馨家</a></li>
							<li class="listnon"><a href="#">最新活动</a></li>
							<li class="listnon"><a href="#">联系我们</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="row">
					<div class="col-xs-4">
						<h4>施工直播</h4>
						<ul>
							<li class="listnon"><a href="#">精选案例</a></li>
							<li class="listnon"><a href="#">全景案例</a></li>
						</ul>
					</div>
					<div class="col-xs-8">
						<h4>为您推荐</h4>
						<a href="#"><img src="/assets/img/frontend/fotad.jpg" class="img-responsive"></a>
					</div>
				</div>
			</div>
			<div class="col-xs-3">
				<h4>在线预约尊享贵宾体验</h4>
				<form>
					<div class="form-group">
						<input type="text" class="form-control" id="username" placeholder="联系人">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="userphone" placeholder="联系电话">
					</div>
					<a href="#" type="submit" class="btn btn-default color_f search_btn sub_btn foot_sechbtn">提交</a>
				</form>
				<p>已有<span class="spenumber"><b>58</b></span>位业主预约</p>
			</div>
			<div class="col-xs-2">
				<h4>关注我们</h4>
				<img src="/assets/img/frontend/copcode.jpg" class="img-responsive">
			</div>
		</div>
		<hr/>
		<div class="link_area">
			<ul class="list-inline">
				<li><h4>友情链接</h4></li>
				<?php if(is_array($config['link']) || $config['link'] instanceof \think\Collection || $config['link'] instanceof \think\Paginator): $i = 0; $__LIST__ = $config['link'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$link): $mod = ($i % 2 );++$i;?>
					<li class="listnon"><a href="<?php echo $link['link']; ?>"><?php echo $link['name']; ?></a></li>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			<p style="text-align: center">
				<small><?php echo $site['name']; ?>&nbsp;&nbsp;备案号&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $site['beian']; ?></small>
			</p>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(".dropdown-toggle").hover(function () {
        $(this).dropdown("toggle");
    });

    /*********************信息滚动*************************/
    $(function () {
        $(".record_list").RollTitle({line: 1, speed: 1000, timespan: 1});
    });

    $('.nav_content .dropdown').hover(function () {
        $(this).find('ul').show();
    }, function () {
        $(this).find('ul').hide();
    });
</script>
</body>
</html>
