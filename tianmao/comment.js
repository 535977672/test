
//评论
function comment(id){
    var ooo = $('.rate-grid .tm-col-master .tm-rate-content');
    var data = [];
    $.each(ooo, function(i, v){
        var con = $(v).find('.tm-rate-fulltxt').text();
        if(con !== '此用户没有填写评论!'){
            con = con.replace(/\s+/g, '');
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
            
            var re  = [
                0,
                id,
                con,
                thumbs
            ];
            data.push(re);
        }
    });
    return data;
}

function detail(id){
    var data = [];
    var content = "";
    var attr = [];
    var brand = "";
    var url = window.location.href;
    var type = 1;
    
    
    var attributes = $('.attributes-list');
    con = $('.content');
    if(con.length>0){
        //过滤a标签
        con.find('a').remove();
        con.find('script').remove();
        content = con.html();
        content = content.replace(/[\t\r\n]/g, '');
        content = htmlspecialchars(content, 1);
    }
    if(attributes.length>0){
        if($('#J_BrandAttr b').length>0)
            brand = $('#J_BrandAttr b').text();
        var list = $('#J_AttrUL li');
        if(list.length>0){
            $.each(list, function(j, k){
                var ttemp = $(k).text();
                ttemp = ttemp.replace(/[\t\r\n]/g, '');
                attr.push(ttemp);
            });
        }
    }
    
    attr = JSON.stringify(attr);
    attr = attr.replace(/\//g, '\\\\/');
    var re  = [
        0,
        id,
        brand,
        content,
        attr,
        url,
        type
    ];
    data.push(re);
    return data;
}



//load data infile 'C:/Users/Administrator/Desktop/t.txt' into table `test`;
//load data infile '/var/lib/mysql-files/t.txt' into table `test` character set utf8;
//因为UTF-8编码有可能是两个，三个，四个字节.Emoji表情是4个字节，而MySQL的utf8编码最多3个字节，所以数据插不进去。
//将Mysql的编码从utf8转换成utf8mb4  
//前端JS校验过滤掉emoji表情 str.replace(/\uD83C[\uDF00-\uDFFF]|\uD83D[\uDC00-\uDE4F]/g, "");
var id = '111';

var d1 = comment(id);
setlocalData('comment_'+id,d1);
//saveMe(getlocalData('comment_'+id), 't.txt');

var d2 = detail(id);
setlocalData('detail_'+id,d2);
//saveMe(getlocalData('detail_'+id), 't.txt');
