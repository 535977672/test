//铺货头hznzcn
var listurls = '';

var reg = /(<script[\s\S]*?<\/script>)|(<(link|meta)[\s\S]*?>)|(<style[\s\S]*?<\/style>)/igm;
var totaldata = [];
var id = 0;

var sex = 2;
var urls = [
    'https://www.hznzcn.com/yuncang/list-1.html',
    'https://www.hznzcn.com/yuncang/list-2.html',
    'https://www.hznzcn.com/yuncang/list-3.html',
    'https://www.hznzcn.com/yuncang/list-1.html?q_sort=2&q_sortT=1',
    'https://www.hznzcn.com/yuncang/list-2.html?q_sort=2&q_sortT=1',
    'https://www.hznzcn.com/yuncang/list-3.html?q_sort=2&q_sortT=1'
];

getdata(urls);
saveMe(totaldata, 't.php');

function getdata(urls){
    if(!listurls) {
        var tmplisturls = localStorage.getItem('listurls');
        listurls = tmplisturls?tmplisturls.split(','):[];
    }
    $.each(urls, function(i,v){
        $.ajax({
            url:v,
            async: false,
            success:function(re){
                var listurl = [];
                var BodyObj = $(re.replace(reg, ''));
                var list = BodyObj.find('#productList_Div li .rowTitle a');
                $.each(list, function(i,v){
                    if(listurls.indexOf ($(v).attr('href')) == -1){
                        listurl.push($(v).attr('href'));
                        listurls.push($(v).attr('href'));
                    }
                });
                //listurl = ['https://www.hznzcn.com/product-1960154.html'];
                //console.log(listurl);
                getdetail(listurl);
                //console.log(totaldata);
            }
        });
    });
    localStorage.setItem('listurls', listurls);
}

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
    var cate = '';
    var prices = 0;

    //content
    var con = BodyObj.find('#detail_img img');
    if(con.length>0){
        content = '';
        $.each(con, function(i, v){
            var c = $(v);
            content = content+'<p><img src="'+c.attr("data-original")+'" alt=""></p>';
            content = content.replace(/[\t\r\n]/g, '');
        });
        content = htmlspecialchars(content, 1);
    }else{
        return data;
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
                temp = $(k).text().trim();
                temp = temp.replace(/[\t\r\n]/g, '');
                attr.push(temp);
                temp = '';
            }
        });
    }
    attr = JSON.stringify(attr);
    attr = myTrim(attr);
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
    cover = myTrim(cover);
    cover = cover.replace(/\//g, '\\\\/');

    //price
    var color = BodyObj.find('#spec_color_list_div #em0 span');
    if(color.length>0){
        $.each(color, function(j, k){
            //img cover
            var imgs = $(k);
            var temp1 = {};
            if(typeof($(k).attr("thumbnailaddress")) != "undefined"){
                temp1.preview = imgs.attr('hrthumbnail');
                temp1.thumb = imgs.attr('thumbnailaddress');
            }else{
                temp1.thumb = imgs.attr('text');
            }
            temp1.alt = imgs.attr('text');
            var sku = [];
            var objSku = BodyObj.find('#em1 tr');
            $.each(objSku, function(i, v){
                var temp2 = {};
                temp2.name = $(v).find('.sizeT').text();
                temp2.alt = '';
                temp2.price = $(v).find('.priceT').text().replace('元', '');
                temp2.count = 20;
                sku.push(temp2);
                if(!prices) prices = temp2.price;
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
            temp2.alt = '';
            temp2.price = $(v).find('.priceT').text().replace('元', '');
            temp2.count = 20;
            sku.push(temp2);
            if(!prices) prices = temp2.price;
        });
        temp1.sku = sku;
        price.push(temp1);
    }
    price = JSON.stringify(price);
    price = myTrim(price);
    price = price.replace(/\//g, '\\\\/');
    
    
    //title
    title = BodyObj.find('.detail-midtitle h1').text().replace(/[\t\r\n]/g, '');
    //limit
    limit = 1;
    cate = BodyObj.find('.colSiRed').text();

    var addr = BodyObj.find('.Detail-toptitle a:nth-of-type(2)').text();
    var cost = 4.5;
    var deleted = 0;

    var video = '';
    var vi = BodyObj.find('#J_playVideo');
    if(vi.length>0){
        video = vi.attr('videourl').replace(/[\t\r\n]/g, '');
    }

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
        deleted,
        video,
        cate,
        sex,
        prices
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
                //totaldata = totaldata.concat(d1);
                totaldata.push(d1[0]);
            }
        });
    });
}








