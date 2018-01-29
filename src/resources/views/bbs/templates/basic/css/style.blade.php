@import url('//cdn.jsdelivr.net/font-nanum/1.0/nanumbarungothic/nanumbarungothic.css');

.basic-table {
  font-family: 'Nanum Barun Gothic', 'dotum';
  font-size: 12px;
}

.basic-table h1.title {
	font-size: 14pt;
	font-weight: bold;
	padding-bottom: 8px;
	border-bottom: 3px solid #4d4d4d;
	margin-bottom: 20px;
}
.basic-table table {
	width: 100%;
	border-top: 1px solid #4d4d4d;
	margin-bottom: 20px;
}

.basic-table table tr th {
	height: 40px;
	background: #f6f6f6;
	border-bottom: 1px solid #efefef;
	padding: 0 10px;
}

.basic-table table tr td {
	height: 40px;
	border-bottom: 1px solid #efefef;
	padding: 0 10px;
}

/* 게시판 리스트 */
.basic-table.index table tbody tr:hover {
	background: #fafafa;
}

/* 게시판 뷰 */
.basic-table.show .content {
	margin-bottom: 20px;
	border-bottom: 1px solid #efefef;
	padding-bottom: 10px;
}

/* 게시글 등록 */
.basic-table.create table tr.file-control td ul, .basic-table.create table tr.file-control td ul li label {
	margin: 0px;
}

.basic-table.create table tr.file-control td ul li {
	padding-left: 0px;
	position: relative;
}

.basic-table.create table tr.file-control td ul li input[type=file] {
	position: absolute;
	opacity: 0;
	filter: alpha(opacity=0);
	width: 85px;
	height: 30px;
	top: 6px;
}

.basic-table.create table tr.file-control td ul li button[type=button] {
	margin-top: 6px;
}

.basic-table.create table tr.file-control td ul li button span {
	top: 2px;
}


/** 게시판 뷰   **/
article.bbs-view header.title{padding: 20px 15px;border: 1px solid #ddd;background: #fafafa;color: #0a87dd;font-size: 1.2em;}
article.bbs-view section.info {padding: 10px;border-bottom: 1px solid #ddd;margin-bottom: 15px;}
article.bbs-view section.info > span {display: inline-block;margin: 0 15px 0 5px;font-weight: normal;}
/*
article.bbs-view section.info .title {}
article.bbs-view section.info .created-at {display: inline-block;margin: 0 15px 0 5px;font-weight: normal;}
article.bbs-view section.info .hit {display: inline-block; margin: 0 15px 0 5px;font-weight: normal;}
*/
article.bbs-view section.link {}
article.bbs-view section.link ul{margin: 0;padding: 0;list-style: none;}
article.bbs-view section.body  {margin-bottom: 30px;width: 100%;line-height: 1.7em;word-break: break-all;overflow: hidden;}

/** 게시판 뷰 -- 대글 **/
.comment-input {margin-top:30px;}
article.comment-article {margin: 0 0 10px;border: 1px solid #ddd;}
article.comment-article header {position: relative;padding: 20px 0 15px 20px;}
article.comment-article h1{display:none;}
article.comment-article .comment-content {border-top: 1px solid #efefef;padding: 20px;line-height: 1.8em;}
article.comment-article footer {zoom: 1;padding: 5px 0 20px 20px;margin:0px;background: initial;}
article.comment-article footer .comment-action {margin: 0;list-style: none;zoom: 1;}
article.comment-article footer .comment-action li {float: left;margin-right: 12px;}
article.comment-article footer .comment-action li a {color: #ee5353;}
