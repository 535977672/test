//铺货头hznzcn

var url = 'https://www.hznzcn.com/yuncang/';
var purl = 'https://www.hznzcn.com/yuncang/';
var reg = /(<script[\s\S]*?<\/script>)|(<(link|meta)[\s\S]*?>)|(<style[\s\S]*?<\/style>)/igm;
var totaldata = [];
var id = 1;

//saveMe(totaldata, 't.txt');

$.get(url, function(re){
    var listurl = [];
    var BodyObj = $(re.replace(reg, ''));
    var list = BodyObj.find('#productList_Div li .rowTitle a');
    $.each(list, function(i,v){
        listurl.push($(v).attr('href'));
    });
    //console.log(listurl);
    getdetail(listurl);
    console.log(totaldata);
});


function detail(id, BodyObj, url){
    //console.log(BodyObj);
    var data = [];
    var content = "";
    var attr = [];
    var brand = "";
    
    
    var url = url;
    
    
    
    var type = 4;
    var cover = [];
    var price = [];
    var title = '';
    var limit = 1;
    
    //content
    var con = BodyObj.find('#detail_img img');
    if(con.length>0){
        content = '';
        $.each(con, function(i, v){
            var c = $(v);
            c.find('a').remove();
            c.find('script').remove();
            c.find('script').remove();
            c.removeAttr("data-original");
            c.removeAttr("style");
            content = content+'<p>'+c.html()+'</p>';
            content = content.replace(/[\t\r\n]/g, '');
        });
        content = htmlspecialchars(content, 1);
    }
    //console.log(content);
    
    //attr
    var attributes = BodyObj.find('#props li');
    if(attributes.length>0){
        var skip = 0;
        var temp = '';
        $.each(attributes, function(j, k){
            if($(k).text() === '建议零售价'){

            }else{
                temp = $(k).text();
                temp = temp.replace(/[\t\r\n]/g, '');
                attr.push(temp);
                temp = '';
            }
        });
    }
    attr = JSON.stringify(attr);
    attr = attr.replace(/\//g, '\\\\/');
    
    //cover
    var objCover = BodyObj.find('#imageMenu img');
    if(objCover.length>0){
        $.each(objCover, function(j, k){
            var temp = {};
            temp.preview = $(k).attr('name');
            temp.thumb = $(k).attr('src');
            cover.push(temp);
        });
    }
    cover = JSON.stringify(cover);
    cover = cover.replace(/\//g, '\\\\/');

    //price
    var color = BodyObj.find('#spec_color_list_div #em0 span');
    if(color.length>0){
        $.each(color, function(j, k){
            //img cover
            var imgs = $(k);
            var temp1 = {};
            if(typeof($(k).attr("thumbnailaddress")) == "undefined"){
                temp1.preview = imgs.attr('hrthumbnail');
                temp1.thumb = imgs.attr('thumbnailaddress');
            }
            temp1.alt = imgs.attr('text');
            var sku = [];
            var objSku = BodyObj.find('#em1 tr');
            $.each(objSku, function(i, v){
                var temp2 = {};
                temp2.name = $(v).find('.sizeT').text();
                temp2.price = $(v).find('.priceT').text().replace('元', '');
                temp2.count = 20;
                sku.push(temp2);
            });
            temp1.sku = sku;
            price.push(temp1);
        });
    }else{
        //not cover
        var temp1 = {};
        var sku = [];
        var objSku = BodyObj.find('#em1 tr');
        $.each(objSku, function(i, v){
            var temp2 = {};
            temp2.name = $(v).find('.sizeT').text();
            temp2.price = $(v).find('.priceT').text().replace('元', '');
            temp2.count = 20;
            sku.push(temp2);
        });
        temp1.sku = sku;
        price.push(temp1);
    }
    price = JSON.stringify(price);
    price = price.replace(/\//g, '\\\\/');
    
    
    //title
    title = BodyObj.find('.detail-midtitle h1').text().replace(/[\t\r\n]/g, '');
    //limit
    limit = 1;
    
    var addr = '杭州';
    var cost = 0;
    var deleted = 0;

    var re  = [
        0,
        id,
        brand,
        content,
        attr,
        url,
        type,
        cover,
        price,
        title,
        limit,
        addr,
        cost,
        deleted
    ];
    //console.log(re);
    data.push(re);
    return data;
}

function getdetail(listurl){
    $.each(listurl, function(i,v){
        $.ajax({
            url:v,
            async: false,
            success:function(re){
                var BodyObj = $(re.replace(reg, ''));
                var d1 = detail(id, BodyObj, v);
                //console.log(d1);
                totaldata = totaldata.concat(d1);
            }
        });
    });
}








