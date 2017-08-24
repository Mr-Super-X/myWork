    <?php require_once("../../s/function/check_session.php");?>
<!DOCTYPE html>
<html>
<head>
    <title>胜网</title>
	<meta charset="utf-8" />
    <link href="/resource/part/ligerlib/ligerUI/skins/Aqua/css/ligerui-all.css" rel="stylesheet" type="text/css" />
    <link href="/resource/part/ligerlib/ligerUI/skins/ligerui-icons.css" rel="stylesheet" type="text/css" />
    <script src="/resource/part/ligerlib/jquery/jquery-1.5.2.min.js" type="text/javascript"></script>
    <script src="/resource/part/ligerlib/ligerUI/js/ligerui.all.js" type="text/javascript"></script> 
	<script src="/resource/part/ligerlib/jquery.cookie.js" type="text/javascript"></script>
    <script src="/resource/part/ligerlib/json2.js"></script>
    <script type="text/javascript">
            var tab = null;
            var accordion = null;
            var tree = null;
            var tabItems = [];	
			var ddd,rmenu_id,rmenu_ti,cu_tabid;			
            $(function() {
				//
				$("#tab1").ligerTab();
                //布局
                $("#layout1").ligerLayout({ leftWidth: 200, height: '100%', heightDiff: -5, space: 4, onHeightChanged: f_heightChanged,isLeftCollapse:true});

                var height = $(".l-layout-center").height();
				rmenu = $.ligerMenu({ top: 100, left: 100, width: 120, items:[{ text: '网络硬盘', click: godisk,icon:'add' }]});      
				$("#flow_tree").ligerTree(
				{data:
					[{text:'我发起的流程',children:[{text:'我的请求',type:'getmyflow'},{text:'办结流程',type:'getmyendflow'},{text:'待办流程',type:'getnoendflow'}],isLeaf:true},{text:'承接流程',children:[{text:'待办流程',type:'chulinode'},{text:'处理记录',type:'chuliendnode'}],isLeaf:true}],
					onselect: function (node)
					{
						var tabid = $(node.target).attr("tabid");
						if (!tabid)
						{
							tabid = new Date().getTime();
							$(node.target).attr("tabid", tabid)
						}
						//cu_tabid=tabid;
						if (!node.data.isLeaf)
						{
							f_addTab(tabid,node.data.text,encodeURI('/s/tp/wwwroot/index.php?s=/Flow/run/getflowlist/type/' + node.data.type));
						}                                  

					},
					checkbox:false
				}
					);
                //加载完成插入图标
                $("li[tabid=tabitem1] a").after("<img src='/resource/images/common/sz.png' onclick=gourl('role') />");
                $("li[tabid=tabitem2] a").after("<img src='/resource/images/common/sz.png' onclick=gourl('form') />");
                $("li[tabid=tabitem3] a").after("<img src='/resource/images/common/sz.png' onclick=gourl('flow') />");

			$("#appflow_tree").ligerTree(
				{
					url:"/s/flow/ajax.php?act=appflowtree",	
					nodeWidth:200,				
					onselect: function (node)
					{
						var tabid = $(node.target).attr("tabid");
						if (!tabid)
						{
							tabid = new Date().getTime();
							$(node.target).attr("tabid", tabid)
						}
						if (!node.data.isLeaf)
						{
							f_addTab(tabid,node.data.text,encodeURI('/s/tp/wwwroot/index.php?s=/Flow/run/getflow/flow_id/'+node.data.id+'.html'));
						}                                  

					},
					checkbox:false
				}
			);
                var power_tree=$("#power_tree").ligerTree(
                    {
                        url:"/s/organizesetting/powertree.php",
                        nodeWidth:200,
                        onclick:function(node){
                            var tabid = $(node.target).attr("tabid");
                            if (!tabid)
                            {
                                tabid = new Date().getTime();
                                $(node.target).attr("tabid", tabid)
                            }
                            if(node.isonclick){
                                power_tree.cancelSelect(node.data.treedataindex);
                                f_addTab(tabid+node.data.id,node.data.text+" 网盘硬盘",encodeURI('/upload/document/filemg.php?role='+node.data.id));
                            }else if(!node.data.isLeaf)
                            {
                                /*f_addTab(tabid,node.data.text,encodeURI('/s/organizesetting/powerformlist.php?roleid='+node.data.id));*/
                                f_addTab(tabid,node.data.text,encodeURI('/s/organizesetting/powermain.php?roleid='+node.data.id));
                            }
                        },

                        checkbox:false
                    }
                );

                function godisk(item){
					var tabid = $(this.target).attr("tabid");
					if (!tabid)
					{
						tabid = "rmenu"+rmenu_id;
						$(this.target).attr("tabid", tabid)
					}					
					f_addTab(tabid,rmenu_ti+' '+item.text,encodeURI('/front/UploadFile/Uopen.htm?oId='+rmenu_id+'&mode=fileOpen'));
				}
				//Tab
                tab=$("#framecenter").ligerTab({
                    height: height,
                    showSwitchInTab: true,
                    showSwitch: true,
                    onAfterAddTabItem: function(tabdata) {
                        tabItems.push(tabdata);
                        saveTabStatus();
                    },
                    onAfterRemoveTabItem: function(tabid) {
                        for (var i = 0; i < tabItems.length; i++) {
                            var o = tabItems[i];
                            if (o.tabid == tabid) {
                                tabItems.splice(i, 1);
                                saveTabStatus();
                                break;
                            }
                        }
                    },
                    onReload: function(tabdata) {
                        var tabid = tabdata.tabid;
                        addFrameSkinLink(tabid);
                    },
					onAfterSelectTabItem: function (tabid)
					{
						cu_tabid=tabid;										
					}
                });
                function retab(tabid){
                    tab.reload(tabid);
                }
                //面板
                $("#accordion1").ligerAccordion({
                    height: height - 24, speed: null
                });

                $(".l-link").hover(function() {
                    $(this).addClass("l-link-over");
                }, function() {
                    $(this).removeClass("l-link-over");
                });
						//树					  
						<?php						
						include_once"../../s/inc/conn.php";
						include_once"../../s/function/public.php";
						$result=getfreepower("",0,implode(',',$_SESSION['uroles']));
						?>
						var data=eval('['+decodeURIComponent('<?php echo urlencode($result[1]);?>')+']');
					    
					   $("#tissue_nav_tree").ligerTree({
										data:data,
										checkbox: false,
										slide: false,
										nodeWidth: 120,                    
										render: function (item)
										{                        
											 return item.values[0].value;
										 },
										 isLeaf:function(item){
											return item.isLeaf;
										 },
										 isExpand: 2,
										 onContextmenu: function (node, e)
							             { 
							                if(node.data.isLeaf)return false;
											rmenu_id = node.data.id;
											rmenu_ti=node.data.values[0].value;
							                rmenu.show({ top: e.pageY, left: e.pageX });
							                return false;
							             },
										 onselect: function (node)
										{							
											var tabid = $(node.target).attr("tabid");
											if (!tabid)
											{
												tabid = new Date().getTime();
												$(node.target).attr("tabid", tabid)
											}
                                            if (node.data.isLeaf)
                                            {
												if(node.data.form_type==1)
                                                	f_addTab(tabid,node.data.values[0].value,encodeURI('/s/listform.php?formid=' + node.data.id));
												else if(node.data.type==2)
												{
													f_addTab(tabid,node.data.values[0].value,encodeURI(node.data.content));
												}else if(node.data.form_type==2)
												{
													f_addTab(tabid,node.data.values[0].value,encodeURI('/s/submitformitem.php?res=main&formid=' + node.data.id));
												}else
                                                	f_addTab(tabid,node.data.values[0].value,encodeURI('/s/readtemplate.php?formid=' + node.data.id));
                                            }
                                            else {                                             
												f_addTab(tabid,node.data.values[0].value+" 网盘硬盘",encodeURI('/upload/document/filebox38668671.php?op=home&root='+node.data.id+'&folder='+node.data.id));
                                            }

										}
									});

               //tab = liger.get("framecenter");
			   /*tab=$("#framecenter").ligerTab({onAfterSelectTabItem: function (tabid)
					{
						cu_tabid=tabid;						
					} 
				});*/
                accordion = liger.get("accordion1");
                tree = liger.get("tissue_nav_tree");
                $("#pageloading").hide();
                css_init();
                pages_init();

                //$("#power_tree .l-tree-icon-folder-open").removeClass("");
            });

            function f_heightChanged(options) {
                if (tab)
                    tab.addHeight(options.diff);
                if (accordion && options.middleHeight - 24 > 0)
                    accordion.setHeight(options.middleHeight - 24);
            }
			function f_getTabId(text, url)
			{
				for(i in tabItems)
				{
					if(tabItems[i].text==text && tabItems[i].url==url)
					{
						return tabItems[i].tabid;
					}
				}
				return -1;
			}
            function f_addTab(tabid, text, url) {
				var id=f_getTabId(text,url);
				if(id!=-1)tabid=id;
                tab.addTabItem({
                    tabid: tabid,
                    text: text,
                    url: url,
                    callback: function() {
                       // addShowCodeBtn(tabid);
                       // addFrameSkinLink(tabid);
                    }
                });
            }
			
            function addShowCodeBtn(tabid) {
                var viewSourceBtn = $('<a class="viewsourcelink" href="javascript:void(0)">查看源码</a>');
                var jiframe = $("#" + tabid);
                viewSourceBtn.insertBefore(jiframe);
                viewSourceBtn.click(function() {
                    showCodeView(jiframe.attr("src"));
                }).hover(function() {
                    viewSourceBtn.addClass("viewsourcelink-over");
                }, function() {
                    viewSourceBtn.removeClass("viewsourcelink-over");
                });
            }
            function showCodeView(src) {
                $.ligerDialog.open({
                    title: '源码预览',
                    url: 'dotnetdemos/codeView.aspx?src=' + src,
                    width: $(window).width() * 0.9,
                    height: $(window).height() * 0.9
                });

            }
            function addFrameSkinLink(tabid) {
                var prevHref = getLinkPrevHref(tabid) || "";
                var skin = getQueryString("skin");
                if (!skin) return;
                skin = skin.toLowerCase();
                attachLinkToFrame(tabid, prevHref + skin_links[skin]);
            }
            var skin_links = {
                "aqua": "lib/ligerUI/skins/Aqua/css/ligerui-all.css",
                "gray": "lib/ligerUI/skins/Gray/css/all.css",
                "silvery": "lib/ligerUI/skins/Silvery/css/style.css",
                "gray2014": "lib/ligerUI/skins/gray2014/css/all.css"
            };
            function pages_init() {				
                var tabJson = $.cookie('liger-home-tab');
                if (tabJson) {
                    var tabitems = JSON2.parse(tabJson);
                    for (var i = 0; tabitems && tabitems[i]; i++) {
                        f_addTab(tabitems[i].tabid, tabitems[i].text, tabitems[i].url);
                    }
                }
            }
            function saveTabStatus() {
                $.cookie('liger-home-tab',JSON2.stringify(tabItems));				
            }
            function css_init() {
                var css = $("#mylink").get(0), skin = getQueryString("skin");
                $("#skinSelect").val(skin);
                $("#skinSelect").change(function() {
                    if (this.value) {
                        location.href = "index.htm?skin=" + this.value;
                    } else {
                        location.href = "index.htm";
                    }
                });


                if (!css || !skin) return;
                skin = skin.toLowerCase();
                $('body').addClass("body-" + skin);
                $(css).attr("href", skin_links[skin]);
            }
            function getQueryString(name) {
                var now_url = document.location.search.slice(1), q_array = now_url.split('&');
                for (var i = 0; i < q_array.length; i++) {
                    var v_array = q_array[i].split('=');
                    if (v_array[0] == name) {
                        return v_array[1];
                    }
                }
                return false;
            }
            function attachLinkToFrame(iframeId, filename) {
                if (!window.frames[iframeId]) return;
                var head = window.frames[iframeId].document.getElementsByTagName('head').item(0);
                var fileref = window.frames[iframeId].document.createElement("link");
                if (!fileref) return;
                fileref.setAttribute("rel", "stylesheet");
                fileref.setAttribute("type", "text/css");
                fileref.setAttribute("href", filename);
                head.appendChild(fileref);
            }
            function getLinkPrevHref(iframeId) {
                if (!window.frames[iframeId]) return;				
                var head = window.frames[iframeId].document.getElementsByTagName('head').item(0);
                var links = $("link:first", head);
                for (var i = 0; links[i]; i++) {
                    var href = $(links[i]).attr("href");
                    if (href && href.toLowerCase().indexOf("ligerui") > 0) {
                        return href.substring(0, href.toLowerCase().indexOf("lib"));
                    }
                }
            }

            function tuichu() {
                location.href = "/login.php";
				}
            function gourl(type){
                if(type=="role"){
                    f_addTab('role',"角色配置",encodeURI('/admin/new_organizesetting/bumenTree.htm'));
                }else if(type=="form"){
					/*f_addTab('form',"表单配置",encodeURI('/admin/new_pagedesign/form_index.html'));*/
                    f_addTab('form',"表单配置",encodeURI('/admin/newtpl/formset.php'));
                }else if(type=="flow"){
                    f_addTab('flow',"流程配置",encodeURI('/admin/flowsetting/index.htm'));
                }
            }
			var per_tree=[{ text: '基础',url:"demos/base/resizable.htm"},{ text: '基础',url:"demos/base/resizable.htm"},];
			function open_qq(){
				f_addTab("qq8888","QQ聊天",encodeURI('http://218.15.27.250:8080/myservice/verify?username=admin&password=admin'));
			}
			function open_msg(type){
				if(type!=''){types='act='+type;}else{types='all';}
				f_addTab("个人消息","个人消息",encodeURI('/s/message/msg.php?'+types));
			}
			function open_email(){
				f_addTab("email","email",encodeURI('http://218.15.27.250:8080/myservice/verify?type=1&username=admin&password=admin'));
			}
			function open_xinxi(){
				f_addTab("xinxi","个人配置",encodeURI('/front/personsetting/index.htm'));
			}
			function open_phone(){
				f_addTab("phone","云电话",encodeURI('/front/personsetting/phone.html'));
			}
     </script> 
    <style type="text/css">
    body,html{height:100%; }
    body{ padding:0px; margin:0;   overflow:hidden;}  
    .l-link{ display:block; height:26px; line-height:26px; padding-left:10px; text-decoration:underline; color:#333;}
    .l-link2{text-decoration:underline; color:white; margin-left:2px;margin-right:2px;}
    .l-layout-top{background:#102A49; color:White;}
    .l-layout-bottom{ background:#E5EDEF; text-align:center;}
    #pageloading{position:absolute; left:0px; top:0px; background:white url('/resource/part/ligerlib/images/loading.gif') no-repeat center; width:100%; height:100%;z-index:99999;}
    .l-link{ display:block; line-height:22px; height:22px; padding-left:16px;border:1px solid white; margin:4px;}
    .l-link-over{ background:#FFEEAC; border:1px solid #DB9F00;} 
    .l-winbar{ background:#2B5A76; height:30px; position:absolute; left:0px; bottom:0px; width:100%; z-index:99999;}
    .space{ color:#E7E7E7;}
    /* 顶部 */ 
    .l-topmenu{ margin:0; padding:0; height:31px; line-height:31px; background:url('/resource/part/ligerlib/images/top.jpg') repeat-x bottom;  position:relative; border-top:1px solid #1D438B;  }
    .l-topmenu-logo{ color:#E7E7E7; padding-left:35px; line-height:26px;background:url('/resource/part/ligerlib/images/topicon.gif') no-repeat 10px 5px;}
    .l-topmenu-welcome{  position:absolute; height:24px; line-height:24px;  right:30px; top:2px;color:#070A0C;}
    .l-topmenu-welcome a{ color:#E7E7E7; text-decoration:none}
	.l-topmenu-welcome a:hover{
		text-decoration:underline;
	}
     .body-gray2014 #framecenter{
        margin-top:3px;
    }
      .viewsourcelink {
         background:#B3D9F7;  display:block; position:absolute; right:10px; top:3px; padding:6px 4px; color:#333; text-decoration:underline;
    }
    .viewsourcelink-over {
        background:#81C0F2;
    }
    .l-topmenu-welcome label {color:white;
    }
	.l-topmenu-welcome label a{color:#FF0;
    }
    #skinSelect {
        margin-right: 6px;
    }
	.zdy span{
		position: relative;
		left: 3px;
		top: -6px;
	}
	.l-tab-content{height:93%;}
   // #power_tree .l-tree-icon-folder-open,#power_tree .l-tree-icon-folder{ background:url('/resource/part/ligerlib/ligerUI/skins/icons/customers.gif') no-repeat center;}
    #power_tree .l-tree-icon-folder-open,#power_tree .l-tree-icon-folder{ background:url('/resource/part/ligerlib/ligerUI/skins/icons/net.gif') no-repeat center;}
    .l-layout-left .l-tab-links li a{
        float: left;
        margin-right: 7px;
    }
    .l-layout-left  .l-tab-links li img{
        padding-top: 4px;
        padding-right: 3px;
        width:22px;
    }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body style="padding:0px;background:#EAEEF5;">  
<div id="pageloading"></div>  
<!--<div id="topmenu" class="l-topmenu">
    <div class="l-topmenu-logo">胜网科技</div>
    <div class="l-topmenu-welcome">
    <label>当前登录用户：<strong><?php /*echo $_SESSION['username']; */?></strong> ( <a href="#" onClick="open_msg('all')">消息</a> )&nbsp;&nbsp;</label>
        <a href="#" onClick="open_xinxi()">个人信息</a>
        <a href="/admin/newtpl/index.php" class="l-link2">配置</a>
        <span class="space">|</span>
        <a href="/s/login/loginout.php" class="l-link2">退出系统</a>
    </div>
</div>-->
  <div id="layout1" style="width:99.2%; margin:0 auto; margin-top:0px; ">
        <div position="left"  title="主要菜单" id="accordion1!!!" style="height:100%;"> 
			<div id="tab1" style="height:100%">
                <div title="角色" style="height:100%;overflow-y:scroll">
                    <div style="height:7px;"></div>
                    <ul id="power_tree" style="margin-top:3px;"> </ul>
                </div>
                <div title="功能" style="height:100%;overflow-y:scroll">
                    <div style=" height:7px;"></div>
                    <ul id="tissue_nav_tree" style="margin-top:3px;"></ul>
                </div>
                <div title="流程" style="height:100%;overflow-y:scroll">
                    <div style="height:7px;"></div>
                    <ul id="flow_tree" style="margin-top:3px;"> </ul>
                    <ul id="appflow_tree" style="margin-top:3px;"> </ul>
                </div>
				<!--<div title="个人管理">
				<div style=" height:7px;"></div>
				<ul style="margin-top:3px;" >
				<li onClick="open_msg('all')"><img src="../../resource/images/common/yx.png"><a href="#">消息</a></li>
				<li onClick="open_email()"><img src="../../resource/images/common/yx.png"><a href="#">邮箱</a></li>
				<li onClick="open_qq()"><img src="../../resource/images/common/QQ.png"><a href="#">聊天工具</a></li>
				<li onClick="open_xinxi()"><img src="../../resource/images/common/sz.png"><a href="#">个人信息</a></li>
				<li onClick="open_phone()"><img src="../../resource/images/common/phone.png"><a href="#">云电话</a></li>
				</ul>
				</div>  -->
			</div>
        </div>
        <div position="center" id="framecenter"> 
            <div tabid="home" title="我的主页" style="height:300px">
                <iframe frameborder="0" name="home" id="home" src="/s/organizesetting/powermain.php?roleid=<?php echo implode(',',$_SESSION['uroles']);?>"></iframe>
            </div>
        </div> 
    
    </div>
</body>
</html>