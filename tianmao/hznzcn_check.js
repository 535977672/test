//铺货头hznzcn
var script = document.createElement('script');
script.src = "https://code.jquery.com/jquery-3.1.1.js";
document.getElementsByTagName('head')[0].appendChild(script);

var reg = /(<script[\s\S]*?<\/script>)|(<(link|meta)[\s\S]*?>)|(<style[\s\S]*?<\/style>)/igm;

var delurl = [];
var urls = '';
urls = $.parseJSON(urls);

getdata(urls);
console.log(delurl);

function getdata(urls){
    $.each(urls, function(i,v){
        $.ajax({
            url:'https://www.hznzcn.com/product-'+v+'.html',
            async: false,
            success:function(re){
                var BodyObj = $(re.replace(reg, ''));
                if(BodyObj.find('.colSiRed').text() == '商品下架区'){
                    delurl.push(i);
                }
            }
        });
    });
}