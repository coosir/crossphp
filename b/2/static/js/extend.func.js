function tips(c){$.dialog.tips(c);}
function succ(c){ $.dialog({icon: 'succeed',content: c});}
function error(c){$.dialog({icon: 'error',content: c});}


//插入到编辑器
function insertEdit(val)
{
	if(editor){ editor.pasteText(val);}else{ editors.pasteText(val);}
}

/*显示隐藏回复*/
function commentOpen(id,gid)
{
	$('#rcomment_'+id).toggle('fast');
}

/*视频弹出或隐藏播放*/
function showVideo(id,url)
{
	 if($('#play_'+id).is(":hidden")){
		  $('#swf_'+id).html('<object width="500" height="420" id="swf_'+id+'"><param name="allowscriptaccess" value="always"></param><param name="wmode" value="window"></param><param name="movie" value="'+url+'"></param><embed src="'+url+'" width="500" height="420" allowscriptaccess="always" wmode="window" type="application/x-shockwave-flash"></embed></object>')
		  $('#play_'+id).show();
	 }else{
		$('#swf_'+id).find('object').remove();
		$('#play_'+id).hide();
	 }
	$('#img_'+id).toggle();
}

function showMp3(id,url)
{
	$('#mp3swf_'+id).toggle();
	$('#mp3img_'+id).toggle();
}



//显示和隐藏
	function viewcontent(){
		$("#displaycontent").toggle();
		$("#displaytitle").hide();
	}
	//显示和隐藏
	function closecontent(){
		$("#displaycontent").hide();
		$("#displaytitle").toggle();
	}

/*显示标签界面*/
function showTagFrom(){	$('#tagFrom').toggle('fast');}
/*提交标签*/
function savaTag(tid)
{
	var tag = $('#tags').val();
		if(tag ==''){ tips('请输入标签哟^_^');$('#tagFrom').show('fast');}else{
			var url = siteUrl+'index.php?app=tag&ac=add_ajax&ts=do';
			$.post(url,{objname:'topic',idname:'topicid',tags:tag,objid:tid},function(rs){  window.location.reload()   })
		}
	
}
//加入秀店Ajax
function joinGroup(gid){
	var url = siteUrl+'index.php?app=group&ac=do&ts=joingroup';
		$.post(url,{groupid:gid},function(rs){
			  if(rs == 0){
				  $.dialog.open(siteUrl+'index.php?app=user&ac=ajax&ts=login', {title: '登录'});
			  }else if(rs == 1){
				  error('你已经加入该秀店，请不要再次加入^_^');
			  }else if(rs == 2){
				  succ('加入秀店成功^_^');
				  window.location.reload(); 
			  }
		})
}

//退出秀店Ajax
function exitGroup(gid){
	art.dialog.confirm('你确认退出秀店吗？', function(){								 
		var url = siteUrl+'index.php?app=group&ac=do&ts=exitgroup';
		$.post(url,{groupid:gid},function(rs){
			  if(rs == 0){
				  error('组长责任重于泰山^_^');
			  }else if(rs == 1){
				  succ('退出秀店成功^_^');
				 window.location.reload(); 
			  }
		})
	});
}

//进入发帖页面
function newTopic(groupid){
	var url = siteUrl+'index.php?app=group&ac=new_topic&groupid='+groupid;
		$.post(url,function(rs){
			  if(rs == 0){
				  $.dialog.open(siteUrl+'index.php?app=user&ac=ajax&ts=login', {title: '登录'});
			  }else if(rs == 1){
				  error('非法操作');
			  }else if(rs == 2){
				  error('不是本组成员不能发帖');
			  }else if(rs == 3){
				  error('本秀店仅限组长和管理员发帖，会员可以评论^_^');
			  }else{
				  window.location = siteUrl+"index.php?app=group&ac=new_topic&groupid="+groupid;
			  }
		})
}

//添加秀店分类索引
function addgroupcateindex(gid,cid){
	var url = siteUrl+'index.php?app=group&ac=do&ts=addgroupcateindex';
		$.post(url,{groupid:gid,cateid:cid},function(rs){
					if(rs == 0){
						succ('添加分类成功^_^');
						window.location.href = siteUrl+"index.php?app=group&ac=edit_group&groupid="+gid+"&ts=cate";
					}else{
						error('出现异常，请报告管理员^_^');
					}
		})
}

//删除宝贝评论
function comment_del(tid,cid){
								  
		var url = siteUrl+'index.php?app=group&ac=do&ts=comment_del';
		$.post(url,{topicid:tid,commentid:cid},function(rs){
					if(rs == 0){
					}
		})
}

//删除宝贝
function topic_del(gid,tid){
	art.dialog.confirm('确定删除吗？', function(){							  
		var url = siteUrl+'index.php?app=group&ac=do&ts=topic_del';
		$.post(url,{groupid:gid,topicid:tid},function(rs){
					if(rs == 0){
						succ('删除成功^_^');
						window.location = siteUrl;
					}
		})
	});
}

//收藏宝贝
function topic_collect(tid){
	
	var url = siteUrl+'index.php?app=group&ac=do&ts=topic_collect';
	$.post(url,{topicid:tid},function(rs){
			if(rs == 0){
				$.dialog.open(siteUrl+'index.php?app=user&ac=ajax&ts=login', {title: '登录'});
			}else if(rs == 1){
				
			}else if(rs == 2){
				
			}else{
				topic_collect_user(tid);
				window.location.reload();
			}					
	});
}

//谁收藏了这篇宝贝
function topic_collect_user(topicid){
	var url = siteUrl+'index.php?app=group&ac=topic_collect_user&ts=ajax&topicid='+topicid;
	$.post(url,function(rs){ $('#collects').html(rs); });
}

//置顶宝贝
function topic_istop(tid,tops){
	if(tops ==1){
		$('#topNotop').html('置顶');
	}else{
		$('#topNotop').html('取消置顶');
	}
	var url = siteUrl+'index.php?app=group&ac=do&ts=topic_istop';
	$.post(url,{topicid:tid,istop:tops},function(rs){
				if(rs == '3'){
					succ('成功^_^');
					window.location.reload();
				}else{
					error('操作失败！');
				}
	})
}

//隐藏显示处理
function topic_isshow(tid,issow){

	if(issow ==1){
		$('#hideShow').html('隐藏');
	}else{
		$('#hideShow').html('显示');
	}
	var url = siteUrl+'index.php?app=group&ac=do&ts=topic_isshow';
	$.post(url,{topicid:tid,isshow:issow},function(rs){
				if(rs == 0){
					succ('成功^_^');
					window.location.reload();
				}
	})	
}


//Ctrl+Enter 回复评论

function keyRecomment(rid,tid,event)
{
     if(event.ctrlKey == true)
	 {
		if(event.keyCode == 13)
		recomment(rid,tid);
		return false;
	}
}

//回复评论
function recomment(rid,tid){

	c = $('#recontent_'+rid).val();
	if(c==''){tips('回复内容不能为空');return false;}
	var url = siteUrl+'index.php?app=group&ac=do&ts=recomment';
	$('#recomm_btn_'+rid).hide();
	$.post(url,{referid:rid,topicid:tid,content:c} ,function(rs){
				if(rs == 0)
				{
					succ('回复成功');
					window.location.reload();
				}else{
					$('#recomm_btn_'+rid).show();
				}
	})	
}

//取消秀店的分类
function cancelCate(cid,gid){
	
	$.dialog.confirm('确定删除吗？', function(){					  
	var url = siteUrl+'index.php?app=group&ac=do&ts=cate_cancel';
	$.post(url,{cateid:cid,groupid:gid},function(rs){
				if(rs == 0){
					succ('删除成功^_^');
					window.location = siteUrl+'index.php?app=group&ac=edit_group&groupid='+gid+'&ts=cate';
				}
			})	
	});
}



function newTopicFrom(that)
{
	var title = $(that).find('input[name=title]').val();
	//var typeid = $(that).find('select[name=typeid]').val();
	var content = $(that).find('input[name=content]').val();
	if(title == '' || content == ''){
		tips('请填写标题和内容'); 
		return false;
		}
	//if(typeid == 0){tips('请选择分类'); return false;}
	$(that).find('input[type=submit]').val('正在提交^_^').attr('disabled',true);
}
function newGoodFrom(that)
{
	var title = $(that).find('input[name=title]').val();
	//var typeid = $(that).find('select[name=typeid]').val();
	var content = $(that).find('input[name=content]').val();
	var gourl = $(that).find('input[name=gourl]').val();
	var price = $(that).find('input[name=price]').val();
	if(title == '' || content == ''){
		tips('请填写标题和描述'); 
		return false;
		}
	if(gourl == ''){
		tips('请填写来源网址'); 
		return false;
		}
	if(price == ''){
		tips('请填写价格'); 
		return false;
		}
	//if(typeid == 0){tips('请选择分类'); return false;}
	$(that).find('input[type=submit]').val('正在提交^_^').attr('disabled',true);
}



