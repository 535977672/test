
function detail(id){
    var data = [];
    var content = "";
    var attr = [];
    var brand = "";
    var url = window.location.href;
    var type = 2;
    var cover = [];
    var price = [];
    var title = '';
    var limit = 1;
    var cate = '';
    var sex = sex;

    var prices = 0;
    
    //content
    var con = $('.de-description-detail p');
    if(con.length>0){
        content = '';
        $.each(con, function(i, v){
            var c = $(v);
            c.find('a').remove();
            c.find('script').remove();
            c.find('img').removeAttr('height');
            c.find('img').removeAttr('width');
            c.find('img').removeAttr('style');
            c.find('img').removeAttr('alt');
            content = content+'<p>'+c.html()+'</p>';
            content = content.replace(/[\t\r\n]/g, '');
        });
        content = htmlspecialchars(content, 1);
    }else{
        return data;
    }
    //console.log(content);
    
    //attr
    var attributes = $('#mod-detail-attributes .obj-content td');
    if(attributes.length>0){
        var skip = 0;
        var temp = '';
        $.each(attributes, function(j, k){
            if($(k).text() === '建议零售价' || $(k).text() === '货号'){
                skip = 1;
            }else if(skip !== 1){
                if(j%2 === 0){
                    temp = $(k).text()+':&nbsp;';
                }else{
                    temp = temp+$(k).text();
                    temp = temp.replace(/[\t\r\n]/g, '');
                    attr.push(temp);
                    temp = '';
                }
            }else{
                skip = 0;
            }
        });
    }
    attr = JSON.stringify(attr);
    attr = myTrim(attr);
    attr = attr.replace(/\//g, '\\\\/');
    
    //cover
    var objCover = $('li.tab-trigger');
    if(objCover.length>0){
    $.each(objCover, function(j, k){
            var temp = {};
            temp.preview = $.parseJSON($(k).attr('data-imgs')).preview;
            temp.thumb = $(k).find('img').attr('src');
            cover.push(temp);
        });
    }
    cover = JSON.stringify(cover);
    cover = myTrim(cover);
    cover = cover.replace(/\//g, '\\\\/');

    //price
    var objPrice = $('.offerdetail_ditto_purchasing .d-content');
    var color = objPrice.find('.obj-leading li .unit-detail-spec-operator');
    if(color.length>0){
        $.each(color, function(j, k){
            //img cover
            var imgs = $(k).find('.box-img img');
            if(imgs.length>0){
                //$(imgs).click();
                //sleep(1000);
                var temp1 = {};
                temp1.preview = $.parseJSON($(k).attr('data-imgs')).preview;//
                temp1.thumb = imgs.attr('src');//
                temp1.alt = imgs.attr('alt');//
                var sku = [];
                var objSku = objPrice.find('.obj-sku .obj-content tr');
                $.each(objSku, function(i, v){
                    var temp2 = {};
                    temp2.name = $(v).find('.name span').text();
                    temp2.alt = '';
                    temp2.price = $(v).find('.price .value').text();
                    temp2.count = $(v).find('.count .value').text();
                    sku.push(temp2);
                    if(!prices) prices = temp2.price;
                });
                temp1.sku = sku;
                price.push(temp1);
            }
        });
    }else{
        //not cover
        var temp1 = {};
        var sku = [];
        var objSku = objPrice.find('.obj-sku .obj-content tr');
        $.each(objSku, function(i, v){
            var temp2 = {};
            if($(v).find('.name span img').length>0){
                temp2.name = $(v).find('.name span img').attr('src');
                temp2.alt = $(v).find('.name span img').attr('alt');
            } else {
                temp2.name = $(v).find('.name span').text();
                temp2.alt = '';
            }
            temp2.price = $(v).find('.price .value').text();
            temp2.count = $(v).find('.count .value').text();
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
    title = $('#mod-detail-title .d-title').text().replace(/[\t\r\n]/g, '');
    //limit
    limit = $('.offerdetail_ditto_price .d-content .amount .value:first').text();
    
    var addr = $('.offerdetail_ditto_postage .delivery-addr').text().replace(/[\t\r\n]/g, '');
    var cost = $('.offerdetail_ditto_postage .obj-carriage .cost-entries .value').text();
    var deleted = 0;
    var video = '';
    var is_hot = 0;
    var is_new = 0;
    var is_recommend = 0;

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
        prices,
        is_hot,
        is_new,
        is_recommend
    ];
    console.log(re);
    data.push(re);
    return data;
}

//load data infile 'C:/Users/Administrator/Desktop/t.txt' into table `test` character set utf8mb4;;
var id = 0;
var sex = 0;
var d1 = detail(id);

setlocalData('detail2_'+id,d1);
//saveMe(getlocalData('detail2_'+id), 't.txt');

//saveMe(getlocalData('detail2_'+id), 'http://test.test.com/tianmao/data.php', 1);