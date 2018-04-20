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
	background-color:#fff;
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

.basic-table.show .bbs-view .title {
    color:#fff;
    font-weight: bold;
}

.basic-table.show .bbs-view .info {
    text-align: right;
    padding:3px;
}

.basic-table.show .bbs-view .body {
    background-color:#fff;
    padding:10px;
}
.basic-table.show .bbs-view .act {
    text-align: right;
    padding:10px;
}

.comment-input {
    padding-top:10px;
}

section.comment-list {
    padding-top:10px;
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


/* 댓글 부분 */
.comment-list {

}
.comment-list .comment-article {background-color:#111111; color:#fff; padding:10px;}
.comment-list .comment-article > ul {list-style-type: none; padding-left: 0;}
.comment-list .comment-article > ul .view.depth-1{padding-left: 50px;}
.comment-list .comment-article > ul .view.depth-2{padding-left: 100px;}
.comment-list .comment-article > ul .view.depth-3{padding-left: 150px;}
.comment-list .comment-article > ul .view.depth-4{padding-left: 200px;}
.comment-list .comment-article > ul .info {text-align:right;}

.comment-list .comment-article > ul .footer {    padding-top: 5px;
    text-align: right;
    position: initial;
    background-color: inherit;
    color: inherit;
    height: inherit;}

.comment-list .comment-article > ul > li.update{display:none;}
#re_comment, .re_comment {display:none;}
