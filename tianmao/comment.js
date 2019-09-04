
//评论
function comment(ids, next = true ){
    var ooo = $('.rate-grid .tm-col-master .tm-rate-content');
    var data = [];
    $.each(ooo, function(i, v){
        var con = $(v).find('.tm-rate-fulltxt').text();
        if(con !== '此用户没有填写评论!'){
            con = con.replace(/\s+/g, '');
            if($(v).siblings('.tm-rate-date').length<1) return true;
            var date = $(v).siblings('.tm-rate-date').text();
            var thumb = $(v).find('.tm-m-photos-thumb li');
            var thumbs = [];
            if(thumb.length>0){
                $.each(thumb, function(j, k){
                    var thumbss = {};
                    var o = $(k);
                    thumbss.osrc = o.attr('data-src');
                    thumbss.tsrc = o.children('img').attr('src');
                    thumbs.push(thumbss);
                });
            }
            thumbs = JSON.stringify(thumbs);
            thumbs = thumbs.replace(/\//g, '\\\\/');
            var date = $(v).siblings('.tm-rate-date').text();
            var re  = [
                0,
                ids,
                0,
                con,
                thumbs,
                date
            ];
            data.push(re);
        }
    });
    if(next) $('.rate-paginator a:last-child')[0].click();
    return data;
}

//load data infile 'C:/Users/Administrator/Desktop/t.txt' into table `m_goods_comment` character set utf8mb4;
//load data infile '/var/lib/mysql-files/t.txt' into table `m_goods_comment` character set utf8mb4;
//因为UTF-8编码有可能是两个，三个，四个字节.Emoji表情是4个字节，而MySQL的utf8编码最多3个字节，所以数据插不进去。
//将Mysql的编码从utf8转换成utf8mb4  
//前端JS校验过滤掉emoji表情 str.replace(/\uD83C[\uDF00-\uDFFF]|\uD83D[\uDC00-\uDE4F]/g, "");
var id = '111';

var d1 = comment(id);
setlocalData('comment_'+id,d1);
//saveMe(getlocalData('comment_'+id), 't.txt');
