//铺货头hznzcn
var listurls = '';

var reg = /(<script[\s\S]*?<\/script>)|(<(link|meta)[\s\S]*?>)|(<style[\s\S]*?<\/style>)/igm;
var totaldata = [];
var id = 0;

var urls = [
    //日韩女装
    {sex: 4, url: 'https://www.hznzcn.com/gallery-catess-pagess-grid.html?style1=1',
        cate: [
            {cid: 150, start:1, end: 2, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 150, start:3, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [129, 143,130,133,17,145,127,146,373,141,132,115,245,105,142,117,116,238,240,144,5,2,251,53,63,252,253,243], start:1, end: 2, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [254,310,248,244,307], start:1, end: 1, is_hot: 0, is_new: 0, is_recommend: 0}
        ]
    },
    {sex: 4, url: 'https://www.hznzcn.com/gallery-99999-grid.html', start:1, end: 5, is_hot: 1, is_new: 1, is_recommend: 1},
    {sex: 2, url: 'https://www.hznzcn.com/yuncang/list-pagess.html', start:1, end: 3, is_hot: 1, is_new: 1, is_recommend: 1},
    {sex: 2, url: 'https://www.hznzcn.com/yuncang/list-pagess.html?q_sort=2&q_sortT=1', start:1, end: 3, is_hot: 0, is_new: 0, is_recommend: 0},
    {sex: 2, url: 'https://www.hznzcn.com/gallery-catess-pagess-grid.html',
        cate: [
            {cid: 150, start:1, end: 1, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 150, start:2, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [129, 143,133,130,17,145,127,146,373,141,132,115,245,105,142,117,116,238,240,144,5,2,251,53,63,252,253,243], start:1, end: 2, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [254,310,248,244,307], start:1, end: 1, is_hot: 0, is_new: 0, is_recommend: 0}
            ]
    },
    {sex: 1, url: 'https://www.hznzcn.com/gallery-catess-pagess-grid.html',
        cate: [
            {cid: 151, start:1, end: 2, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 151, start:3, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [263,155,271,273,272,268,255,153,266,256,261,270,262,267,260,154,269,259], start:1, end: 2, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [296,265,274,257,258,360], start:1, end: 1, is_hot: 0, is_new: 0, is_recommend: 0}
        ]
    },

    {sex: 3, url: 'https://www.hznzcn.com/zl/gallery-catess-pagess-grid.html',
        cate: [
            {cid: 152, start:1, end: 2, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 152, start:3, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [280,282,289,288,279,285,312,281,308,286], start:1, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [374,278,233,275,290,277,283,287,316], start:1, end: 3, is_hot: 0, is_new: 0, is_recommend: 0},
        ]
    },
    {sex: 2, url: 'https://www.hznzcn.com/gz/gallery-catess-pagess-grid.html',
        cate: [
            {cid: 150, start:1, end: 2, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 150, start:3, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [130,53,129,2,146,17,132,116,115], start:1, end: 2, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [238,141,145,105,5,117,253,251,127,144,243,244,248], start:1, end: 1, is_hot: 0, is_new: 0, is_recommend: 0},
        ]
    },
    //男鞋
    {sex: 5, url: 'https://www.hznzcn.com/gallery-322-grid.html', start:1, end: 7, is_hot: 0, is_new: 0, is_recommend: 0},
    //女鞋
    {sex: 6, url: 'https://www.hznzcn.com/wl/gallery-catess-pagess-grid.html',
        cate: [
            {cid: 319, start:1, end: 2, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 319, start:3, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [324,325,326,328,329], start:1, end: 5, is_hot: 0, is_new: 0, is_recommend: 0},
        ]
    },
    {sex: 2, url: 'https://www.hznzcn.com/py/gallery-catess-pagess-grid.html',
        cate: [
            {cid: 150, start:1, end: 2, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 150, start:3, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [130,17,143,129,53,145], start:1, end: 5, is_hot: 0, is_new: 0, is_recommend: 0},
        ]
    },
    {sex: 1, url: 'https://www.hznzcn.com/py/gallery-catess-pagess-grid.html',
        cate: [
            {cid: 151, start:1, end: 2, is_hot: 1, is_new: 1, is_recommend: 1},
            {cid: 151, start:3, end: 10, is_hot: 0, is_new: 0, is_recommend: 0},
            {cids: [155,154,255,256,273,261,269,266,153,262,263,267,268,270,271,272], start:1, end: 5, is_hot: 0, is_new: 0, is_recommend: 0},
        ]
    },
];

getdata(urls);
//saveMe(totaldata, 't.php');

function getdata(urls){
    if(!listurls) {
        var tmplisturls = localStorage.getItem('listurls');
        listurls = tmplisturls?tmplisturls.split(','):[];
    }
    getajax(geturl(urls));
    localStorage.setItem('listurls', listurls);
}

function geturl(urls){
    var u = [];
    $.each(urls, function(i,v){
        if(v.hasOwnProperty('cate')){
            var turls = [];
            $.each(v.cate, function(k,va){
                if(va.hasOwnProperty('cids')){
                    $.each(va.cids, function(ka,vaa){
                        var turl = {};
                        turl.sex = v.sex;
                        turl.start = va.start;
                        turl.end = va.end;
                        turl.is_hot = va.is_hot;
                        turl.is_new = va.is_new;
                        turl.is_recommend = va.is_recommend;
                        turl.url = v.url.replace("catess", vaa);
                        turls.push(turl);
                    });
                }else{
                    var turl = va;
                    turl.sex = v.sex;
                    turl.url = v.url.replace("catess", va.cid);
                    turls.push(turl);
                }
            });
            u = u.concat(geturl(turls));
        }else if(v.hasOwnProperty('start')){
            var turls = [];
            for (var is = v.start; is <= v.end; is++) {
                var turl = {};
                turl.sex = v.sex;
                turl.is_hot = v.is_hot;
                turl.is_new = v.is_new;
                turl.is_recommend = v.is_recommend;
                turl.url = v.url.replace("pagess", is);
                turls.push(turl);
            }
            u = u.concat(geturl(turls));
        }else{
            u.push(v);
        }
    });
    return u;
}

function getajax(urls){
    $.each(urls, function(i,vd){
        $.ajax({
            url:vd.url,
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
                getdetail(listurl, vd);
            }
        });
    });
}

function detail(id, BodyObj, url, vss){
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
        vss.sex,
        prices,
        vss.is_hot,
        vss.is_new,
        vss.is_recommend,
    ];
    //console.log(re);
    data.push(re);
    return data;
}

function getdetail(listurl, vd){
    $.each(listurl, function(i,v){
        $.ajax({
            url:v,
            async: false,
            success:function(re){
                var BodyObj = $(re.replace(reg, ''));
                var d1 = detail(id, BodyObj, v, vd);
                //console.log(d1);
                //totaldata = totaldata.concat(d1);
                totaldata.push(d1[0]);
            }
        });
    });
}








