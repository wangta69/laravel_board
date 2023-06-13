/** index css */
index {
  width:100%;
  max-width:1400px;
  margin:0 auto;
  overflow:hidden;
}

.index .gallery{
  margin:50px -20px;
  box-sizing:border-box;
}
.index .gallery:after{
  content:"";
  display:block;
  clear:both;
  visibility: hidden;
}
.index .gallery > div{
  width:20%;
  float:left;
  box-sizing:border-box;
  padding:0 20px;
  margin:0 0 50px 0;
}
.index .gallery > div img{
  width:100%;height:auto;}

.index .gallery .desc {
  display: flex;
  flex-direction: column;
  overflow: hidden;
  white-space: nowrap;
  padding: 15px;
  background-color: #fff;
  border-bottom-left-radius: 15px;
  border-bottom-right-radius: 15px;
  border: 1px solid #dfdfdf;

}

.index .image-link {
  /*width: 100%;
  height: 200px;
  */
  position: relative;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
  overflow: hidden;
  display: block;
}

@media (max-width:1200px){
  .index .gallery > div{width:25%;}
}
@media (max-width:768px){
  .index .gallery > div{width:33.33333%;}
}
@media (max-width:560px){
  .index .gallery > div{width:50%;}
}
@media (max-width:480px){
  .index .gallery > div{width:100%;}
}

/** show css */
.show ul.link {
	margin: 0px;
}

.show .body {
  padding: 20px;
}
.show .body img {
  max-width: 90%;
}

.show .body .content {
  padding: 10px;
}

/** bbs-comments */
.bbs-comments {
	margin-top : 30px;
}

.bbs-comments .comment-list{
	margin-top : 30px;
}

.bbs-comments .comment-list ul, .bbs-comments .comment-list li{
  margin:0;
  padding:0;
  list-style:none;
}
/*
.bbs-comments .comment-list li{
	padding-bottom: 20px;
}
*/
.bbs-comments .comment-list .comment-article {
	border: 1px solid #ccc;
  margin-left: 20px;
  padding: 10px;
	margin-bottom: 10px;
}

.comment-article .comment-content {
	padding: 20px;
}
