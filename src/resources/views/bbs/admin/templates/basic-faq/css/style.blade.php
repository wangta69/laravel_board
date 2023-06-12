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


/* 게시판 리스트 */
.basic-table.index .basic-faq {
    display: flex;
    flex-direction: column;
}
.basic-table.index .basic-faq .faq-title {
    padding: 10px;
    border-bottom: solid 1px;
}

.basic-table.index .basic-faq .faq-title:hover {
	background: #fafafa;
}
.basic-table.index .basic-faq .faq-title.view > div {
    text-align: left;
}
.basic-table.index .basic-faq .faq-title.view .answer {
    display: none;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
}
.basic-table.index .basic-faq .faq-title.view .answer.on {
    display: block;
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
