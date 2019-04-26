//var $domain =  'http://test.nfc.com/v1/';
$domain =  'http://api-h5.gebodata.com/v1/';

//var $domain =  'http://test.test.com/';

var $authentication =  '';
/**
 * 
 * @param {type} $url
 * @param {type} $data
 * @param {type} $type 1-get 2-post ...
 * @param {type} $callback 回调
 * @returns {undefined}
 */
function toAjax($url, $data = {}, $type, $callback = false){
    Vue.http.headers.common['version'] = 'v1';
    if($authentication) Vue.http.headers.common['authentication'] = $authentication;
    Vue.http.options.timeout = 5000;//超时5s
    $url = $domain+$url;
    if($type === 1){
        //load TODO
        Vue.http.get($url, {params:$data}).then(function(res){
            //load end TODO
            if($callback){
                $callback(res);
            }
        },function(res){
            //load end TODO
            console.log(1);
            console.log(res);
        });
    }
    if($type === 2){
        //load TODO
        Vue.http.post($url, $data, {emulateJSON: true}).then(function(res){
            //load end TODO
            if($callback){
                $callback(res);
            }
        },function(res){
            //load end TODO
            console.log(2);
            console.log(res);
        });
    }
}