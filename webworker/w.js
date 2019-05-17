this.onmessage = function(ev){
    var $ = ev.data;
    var url = '';
    url = 'http://test.test.com/index1.php';

    setInterval(tt, 500);

    function tt(){
        $.ajax({
            type:"GET",
            dataType:"String",
            url:url,
            success:function(re){
                console.log(re);
                //this.postMessage(re);
            },
            error:function(re){
                console.log(re);
                //this.postMessage(re);
            }
        });
    }
    
};