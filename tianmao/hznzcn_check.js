//铺货头hznzcn
var script = document.createElement('script');
script.src = "https://code.jquery.com/jquery-3.1.1.js";
document.getElementsByTagName('head')[0].appendChild(script);

var reg = /(<script[\s\S]*?<\/script>)|(<(link|meta)[\s\S]*?>)|(<style[\s\S]*?<\/style>)/igm;

var delurl = [];
var urls = '{"1002":"https:\\/\\/www.hznzcn.com\\/product-1692970.html","1003":"https:\\/\\/www.hznzcn.com\\/product-1869148.html","1004":"https:\\/\\/www.hznzcn.com\\/product-1908070.html","1005":"https:\\/\\/www.hznzcn.com\\/product-1744635.html","1006":"https:\\/\\/www.hznzcn.com\\/product-1737710.html","1007":"https:\\/\\/www.hznzcn.com\\/product-1466182.html","1008":"https:\\/\\/www.hznzcn.com\\/product-517224.html","1009":"https:\\/\\/www.hznzcn.com\\/product-101120.html","1010":"https:\\/\\/www.hznzcn.com\\/product-731814.html","1011":"https:\\/\\/www.hznzcn.com\\/product-669345.html","1012":"https:\\/\\/www.hznzcn.com\\/product-938376.html","1013":"https:\\/\\/www.hznzcn.com\\/product-725603.html","1014":"https:\\/\\/www.hznzcn.com\\/product-451178.html","1015":"https:\\/\\/www.hznzcn.com\\/product-1908071.html","1016":"https:\\/\\/www.hznzcn.com\\/product-1908072.html","1017":"https:\\/\\/www.hznzcn.com\\/product-519486.html","1018":"https:\\/\\/www.hznzcn.com\\/product-309995.html","1019":"https:\\/\\/www.hznzcn.com\\/product-699484.html","1020":"https:\\/\\/www.hznzcn.com\\/product-1714550.html","1021":"https:\\/\\/www.hznzcn.com\\/product-1689576.html","1022":"https:\\/\\/www.hznzcn.com\\/product-1463665.html","1023":"https:\\/\\/www.hznzcn.com\\/product-1670983.html","1024":"https:\\/\\/www.hznzcn.com\\/product-533018.html","1025":"https:\\/\\/www.hznzcn.com\\/product-1744637.html","1026":"https:\\/\\/www.hznzcn.com\\/product-655664.html","1027":"https:\\/\\/www.hznzcn.com\\/product-1769447.html","1028":"https:\\/\\/www.hznzcn.com\\/product-1769448.html","1029":"https:\\/\\/www.hznzcn.com\\/product-1542623.html","1030":"https:\\/\\/www.hznzcn.com\\/product-440398.html","1031":"https:\\/\\/www.hznzcn.com\\/product-1734148.html","1032":"https:\\/\\/www.hznzcn.com\\/product-753078.html","1033":"https:\\/\\/www.hznzcn.com\\/product-1525234.html","1034":"https:\\/\\/www.hznzcn.com\\/product-1451870.html","1035":"https:\\/\\/www.hznzcn.com\\/product-1451878.html","1036":"https:\\/\\/www.hznzcn.com\\/product-1451872.html","1037":"https:\\/\\/www.hznzcn.com\\/product-1440466.html","1038":"https:\\/\\/www.hznzcn.com\\/product-1542650.html","1039":"https:\\/\\/www.hznzcn.com\\/product-1462040.html","1040":"https:\\/\\/www.hznzcn.com\\/product-655208.html","1041":"https:\\/\\/www.hznzcn.com\\/product-1843692.html","1042":"https:\\/\\/www.hznzcn.com\\/product-1810164.html","1043":"https:\\/\\/www.hznzcn.com\\/product-2007730.html","1044":"https:\\/\\/www.hznzcn.com\\/product-1753786.html","1045":"https:\\/\\/www.hznzcn.com\\/product-1754639.html","1046":"https:\\/\\/www.hznzcn.com\\/product-1959974.html","1047":"https:\\/\\/www.hznzcn.com\\/product-1790851.html","1048":"https:\\/\\/www.hznzcn.com\\/product-489458.html","1049":"https:\\/\\/www.hznzcn.com\\/product-686967.html","1050":"https:\\/\\/www.hznzcn.com\\/product-1683872.html","1051":"https:\\/\\/www.hznzcn.com\\/product-1997401.html","1052":"https:\\/\\/www.hznzcn.com\\/product-1997400.html","1053":"https:\\/\\/www.hznzcn.com\\/product-1771811.html","1054":"https:\\/\\/www.hznzcn.com\\/product-1753909.html","1055":"https:\\/\\/www.hznzcn.com\\/product-1969557.html","1056":"https:\\/\\/www.hznzcn.com\\/product-1960031.html","1057":"https:\\/\\/www.hznzcn.com\\/product-1918147.html","1058":"https:\\/\\/www.hznzcn.com\\/product-764445.html","1059":"https:\\/\\/www.hznzcn.com\\/product-1767781.html","1060":"https:\\/\\/www.hznzcn.com\\/product-1776480.html","1061":"https:\\/\\/www.hznzcn.com\\/product-1928340.html","1062":"https:\\/\\/www.hznzcn.com\\/product-1713633.html","1063":"https:\\/\\/www.hznzcn.com\\/product-1960154.html","1064":"https:\\/\\/www.hznzcn.com\\/product-1759780.html","1065":"https:\\/\\/www.hznzcn.com\\/product-770357.html","1066":"https:\\/\\/www.hznzcn.com\\/product-1688806.html","1067":"https:\\/\\/www.hznzcn.com\\/product-1777532.html","1068":"https:\\/\\/www.hznzcn.com\\/product-772682.html","1069":"https:\\/\\/www.hznzcn.com\\/product-772676.html","1070":"https:\\/\\/www.hznzcn.com\\/product-1802433.html","1071":"https:\\/\\/www.hznzcn.com\\/product-523336.html","1072":"https:\\/\\/www.hznzcn.com\\/product-765750.html","1073":"https:\\/\\/www.hznzcn.com\\/product-1612063.html","1074":"https:\\/\\/www.hznzcn.com\\/product-664227.html","1075":"https:\\/\\/www.hznzcn.com\\/product-1786894.html","1076":"https:\\/\\/www.hznzcn.com\\/product-1786897.html","1077":"https:\\/\\/www.hznzcn.com\\/product-1713955.html","1078":"https:\\/\\/www.hznzcn.com\\/product-740121.html","1079":"https:\\/\\/www.hznzcn.com\\/product-1943133.html","1080":"https:\\/\\/www.hznzcn.com\\/product-1943153.html","1081":"https:\\/\\/www.hznzcn.com\\/product-1688982.html","1082":"https:\\/\\/www.hznzcn.com\\/product-1802436.html","1083":"https:\\/\\/www.hznzcn.com\\/product-1674660.html","1084":"https:\\/\\/www.hznzcn.com\\/product-1674661.html","1085":"https:\\/\\/www.hznzcn.com\\/product-1718093.html","1086":"https:\\/\\/www.hznzcn.com\\/product-1927962.html","1087":"https:\\/\\/www.hznzcn.com\\/product-1853690.html","1088":"https:\\/\\/www.hznzcn.com\\/product-1844218.html","1089":"https:\\/\\/www.hznzcn.com\\/product-1844217.html","1090":"https:\\/\\/www.hznzcn.com\\/product-732047.html","1091":"https:\\/\\/www.hznzcn.com\\/product-1741379.html","1092":"https:\\/\\/www.hznzcn.com\\/product-1713429.html","1093":"https:\\/\\/www.hznzcn.com\\/product-1326477.html","1094":"https:\\/\\/www.hznzcn.com\\/product-1796337.html","1095":"https:\\/\\/www.hznzcn.com\\/product-1771807.html","1096":"https:\\/\\/www.hznzcn.com\\/product-1859924.html","1097":"https:\\/\\/www.hznzcn.com\\/product-720267.html","1098":"https:\\/\\/www.hznzcn.com\\/product-1837548.html","1099":"https:\\/\\/www.hznzcn.com\\/product-1837541.html","1100":"https:\\/\\/www.hznzcn.com\\/product-1759773.html","1101":"https:\\/\\/www.hznzcn.com\\/product-1776472.html","1102":"https:\\/\\/www.hznzcn.com\\/product-1728671.html","1103":"https:\\/\\/www.hznzcn.com\\/product-1767370.html","1104":"https:\\/\\/www.hznzcn.com\\/product-1693226.html","1105":"https:\\/\\/www.hznzcn.com\\/product-495310.html","1106":"https:\\/\\/www.hznzcn.com\\/product-537958.html","1107":"https:\\/\\/www.hznzcn.com\\/product-1746870.html","1108":"https:\\/\\/www.hznzcn.com\\/product-1670982.html","1109":"https:\\/\\/www.hznzcn.com\\/product-1463666.html","1110":"https:\\/\\/www.hznzcn.com\\/product-1821762.html","1111":"https:\\/\\/www.hznzcn.com\\/product-1767001.html","1112":"https:\\/\\/www.hznzcn.com\\/product-1733717.html","1113":"https:\\/\\/www.hznzcn.com\\/product-1696928.html","1114":"https:\\/\\/www.hznzcn.com\\/product-1725279.html","1115":"https:\\/\\/www.hznzcn.com\\/product-520400.html","1116":"https:\\/\\/www.hznzcn.com\\/product-1799895.html","1117":"https:\\/\\/www.hznzcn.com\\/product-1788127.html","1118":"https:\\/\\/www.hznzcn.com\\/product-473095.html","1119":"https:\\/\\/www.hznzcn.com\\/product-516840.html","1120":"https:\\/\\/www.hznzcn.com\\/product-496096.html","1121":"https:\\/\\/www.hznzcn.com\\/product-733790.html","1122":"https:\\/\\/www.hznzcn.com\\/product-1793056.html","1123":"https:\\/\\/www.hznzcn.com\\/product-1759782.html","1124":"https:\\/\\/www.hznzcn.com\\/product-1759778.html","1125":"https:\\/\\/www.hznzcn.com\\/product-1776452.html","1126":"https:\\/\\/www.hznzcn.com\\/product-1728663.html","1127":"https:\\/\\/www.hznzcn.com\\/product-1810112.html","1128":"https:\\/\\/www.hznzcn.com\\/product-730448.html","1129":"https:\\/\\/www.hznzcn.com\\/product-1809717.html","1130":"https:\\/\\/www.hznzcn.com\\/product-1751878.html","1131":"https:\\/\\/www.hznzcn.com\\/product-1751838.html","1132":"https:\\/\\/www.hznzcn.com\\/product-1714523.html","1133":"https:\\/\\/www.hznzcn.com\\/product-683926.html","1134":"https:\\/\\/www.hznzcn.com\\/product-726182.html","1135":"https:\\/\\/www.hznzcn.com\\/product-826435.html","1136":"https:\\/\\/www.hznzcn.com\\/product-1816118.html","1137":"https:\\/\\/www.hznzcn.com\\/product-1725801.html","1138":"https:\\/\\/www.hznzcn.com\\/product-1551631.html","1139":"https:\\/\\/www.hznzcn.com\\/product-1551630.html","1140":"https:\\/\\/www.hznzcn.com\\/product-1551629.html","1141":"https:\\/\\/www.hznzcn.com\\/product-1551628.html","1142":"https:\\/\\/www.hznzcn.com\\/product-1551627.html","1143":"https:\\/\\/www.hznzcn.com\\/product-763684.html","1144":"https:\\/\\/www.hznzcn.com\\/product-763685.html","1145":"https:\\/\\/www.hznzcn.com\\/product-772712.html","1146":"https:\\/\\/www.hznzcn.com\\/product-763688.html","1147":"https:\\/\\/www.hznzcn.com\\/product-1697041.html","1148":"https:\\/\\/www.hznzcn.com\\/product-1763985.html","1149":"https:\\/\\/www.hznzcn.com\\/product-1763984.html","1150":"https:\\/\\/www.hznzcn.com\\/product-1788113.html","1151":"https:\\/\\/www.hznzcn.com\\/product-1759790.html","1152":"https:\\/\\/www.hznzcn.com\\/product-1733952.html","1153":"https:\\/\\/www.hznzcn.com\\/product-652212.html","1154":"https:\\/\\/www.hznzcn.com\\/product-1761306.html","1155":"https:\\/\\/www.hznzcn.com\\/product-1776478.html","1156":"https:\\/\\/www.hznzcn.com\\/product-1776548.html","1157":"https:\\/\\/www.hznzcn.com\\/product-1728697.html","1158":"https:\\/\\/www.hznzcn.com\\/product-1776589.html","1159":"https:\\/\\/www.hznzcn.com\\/product-1776569.html","1160":"https:\\/\\/www.hznzcn.com\\/product-1776477.html","1161":"https:\\/\\/www.hznzcn.com\\/product-712119.html","1162":"https:\\/\\/www.hznzcn.com\\/product-745389.html","1163":"https:\\/\\/www.hznzcn.com\\/product-1766452.html","1164":"https:\\/\\/www.hznzcn.com\\/product-1761939.html","1165":"https:\\/\\/www.hznzcn.com\\/product-1766448.html","1166":"https:\\/\\/www.hznzcn.com\\/product-1766446.html","1167":"https:\\/\\/www.hznzcn.com\\/product-1752562.html","1168":"https:\\/\\/www.hznzcn.com\\/product-480533.html","1169":"https:\\/\\/www.hznzcn.com\\/product-1740232.html","1170":"https:\\/\\/www.hznzcn.com\\/product-1667879.html","1171":"https:\\/\\/www.hznzcn.com\\/product-1755151.html","1172":"https:\\/\\/www.hznzcn.com\\/product-1766333.html","1173":"https:\\/\\/www.hznzcn.com\\/product-433460.html","1174":"https:\\/\\/www.hznzcn.com\\/product-633390.html","1175":"https:\\/\\/www.hznzcn.com\\/product-1815576.html","1176":"https:\\/\\/www.hznzcn.com\\/product-711063.html","1177":"https:\\/\\/www.hznzcn.com\\/product-1610488.html","1178":"https:\\/\\/www.hznzcn.com\\/product-1542621.html","1179":"https:\\/\\/www.hznzcn.com\\/product-1753714.html","1180":"https:\\/\\/www.hznzcn.com\\/product-795474.html","1181":"https:\\/\\/www.hznzcn.com\\/product-1790403.html","1182":"https:\\/\\/www.hznzcn.com\\/product-1702079.html","1183":"https:\\/\\/www.hznzcn.com\\/product-1764328.html","1184":"https:\\/\\/www.hznzcn.com\\/product-1769450.html","1185":"https:\\/\\/www.hznzcn.com\\/product-1781392.html","1186":"https:\\/\\/www.hznzcn.com\\/product-1753907.html","1187":"https:\\/\\/www.hznzcn.com\\/product-1749347.html","1188":"https:\\/\\/www.hznzcn.com\\/product-1753910.html","1189":"https:\\/\\/www.hznzcn.com\\/product-1655175.html","1190":"https:\\/\\/www.hznzcn.com\\/product-1597496.html","1191":"https:\\/\\/www.hznzcn.com\\/product-1610503.html","1192":"https:\\/\\/www.hznzcn.com\\/product-683894.html","1193":"https:\\/\\/www.hznzcn.com\\/product-1771866.html","1194":"https:\\/\\/www.hznzcn.com\\/product-1771871.html","1195":"https:\\/\\/www.hznzcn.com\\/product-769357.html","1196":"https:\\/\\/www.hznzcn.com\\/product-1705619.html","1197":"https:\\/\\/www.hznzcn.com\\/product-702988.html","1198":"https:\\/\\/www.hznzcn.com\\/product-1807813.html","1199":"https:\\/\\/www.hznzcn.com\\/product-1668423.html","1200":"https:\\/\\/www.hznzcn.com\\/product-1668318.html","1201":"https:\\/\\/www.hznzcn.com\\/product-1637113.html","1202":"https:\\/\\/www.hznzcn.com\\/product-1717349.html","1203":"https:\\/\\/www.hznzcn.com\\/product-1583642.html","1204":"https:\\/\\/www.hznzcn.com\\/product-716918.html","1205":"https:\\/\\/www.hznzcn.com\\/product-1761313.html","1206":"https:\\/\\/www.hznzcn.com\\/product-1761314.html","1207":"https:\\/\\/www.hznzcn.com\\/product-1761316.html","1208":"https:\\/\\/www.hznzcn.com\\/product-1525078.html","1209":"https:\\/\\/www.hznzcn.com\\/product-1456845.html","1210":"https:\\/\\/www.hznzcn.com\\/product-1768722.html","1211":"https:\\/\\/www.hznzcn.com\\/product-1733754.html","1212":"https:\\/\\/www.hznzcn.com\\/product-1768263.html","1213":"https:\\/\\/www.hznzcn.com\\/product-1768268.html","1214":"https:\\/\\/www.hznzcn.com\\/product-1790393.html","1215":"https:\\/\\/www.hznzcn.com\\/product-700473.html","1216":"https:\\/\\/www.hznzcn.com\\/product-717781.html","1217":"https:\\/\\/www.hznzcn.com\\/product-1790400.html","1218":"https:\\/\\/www.hznzcn.com\\/product-1560432.html","1219":"https:\\/\\/www.hznzcn.com\\/product-1468828.html","1220":"https:\\/\\/www.hznzcn.com\\/product-1728633.html","1221":"https:\\/\\/www.hznzcn.com\\/product-464333.html","1222":"https:\\/\\/www.hznzcn.com\\/product-653955.html","1223":"https:\\/\\/www.hznzcn.com\\/product-688424.html","1224":"https:\\/\\/www.hznzcn.com\\/product-463452.html","1225":"https:\\/\\/www.hznzcn.com\\/product-1662139.html","1226":"https:\\/\\/www.hznzcn.com\\/product-1709296.html","1227":"https:\\/\\/www.hznzcn.com\\/product-776407.html","1228":"https:\\/\\/www.hznzcn.com\\/product-751878.html","1229":"https:\\/\\/www.hznzcn.com\\/product-1725802.html","1230":"https:\\/\\/www.hznzcn.com\\/product-724486.html","1231":"https:\\/\\/www.hznzcn.com\\/product-1684713.html","1232":"https:\\/\\/www.hznzcn.com\\/product-751890.html","1233":"https:\\/\\/www.hznzcn.com\\/product-778327.html","1234":"https:\\/\\/www.hznzcn.com\\/product-1759742.html","1235":"https:\\/\\/www.hznzcn.com\\/product-779581.html","1236":"https:\\/\\/www.hznzcn.com\\/product-527904.html","1237":"https:\\/\\/www.hznzcn.com\\/product-1293939.html","1238":"https:\\/\\/www.hznzcn.com\\/product-1503828.html","1239":"https:\\/\\/www.hznzcn.com\\/product-1502010.html","1240":"https:\\/\\/www.hznzcn.com\\/product-1498768.html","1241":"https:\\/\\/www.hznzcn.com\\/product-656677.html","1242":"https:\\/\\/www.hznzcn.com\\/product-1547270.html","1243":"https:\\/\\/www.hznzcn.com\\/product-1511987.html","1244":"https:\\/\\/www.hznzcn.com\\/product-1521075.html","1245":"https:\\/\\/www.hznzcn.com\\/product-1444079.html","1246":"https:\\/\\/www.hznzcn.com\\/product-1444073.html","1247":"https:\\/\\/www.hznzcn.com\\/product-718450.html","1248":"https:\\/\\/www.hznzcn.com\\/product-1529904.html","1249":"https:\\/\\/www.hznzcn.com\\/product-1463718.html","1250":"https:\\/\\/www.hznzcn.com\\/product-1490145.html","1251":"https:\\/\\/www.hznzcn.com\\/product-634533.html","1252":"https:\\/\\/www.hznzcn.com\\/product-819294.html","1253":"https:\\/\\/www.hznzcn.com\\/product-1457341.html","1254":"https:\\/\\/www.hznzcn.com\\/product-1463612.html","1255":"https:\\/\\/www.hznzcn.com\\/product-795752.html","1256":"https:\\/\\/www.hznzcn.com\\/product-793883.html","1257":"https:\\/\\/www.hznzcn.com\\/product-1468940.html","1258":"https:\\/\\/www.hznzcn.com\\/product-437659.html","1259":"https:\\/\\/www.hznzcn.com\\/product-1440464.html","1260":"https:\\/\\/www.hznzcn.com\\/product-1440467.html","1261":"https:\\/\\/www.hznzcn.com\\/product-633284.html","1262":"https:\\/\\/www.hznzcn.com\\/product-666181.html","1263":"https:\\/\\/www.hznzcn.com\\/product-802106.html","1264":"https:\\/\\/www.hznzcn.com\\/product-806688.html","1265":"https:\\/\\/www.hznzcn.com\\/product-1042815.html","1266":"https:\\/\\/www.hznzcn.com\\/product-1741387.html","1267":"https:\\/\\/www.hznzcn.com\\/product-1607470.html","1268":"https:\\/\\/www.hznzcn.com\\/product-1776941.html","1269":"https:\\/\\/www.hznzcn.com\\/product-1741378.html","1270":"https:\\/\\/www.hznzcn.com\\/product-1741394.html","1271":"https:\\/\\/www.hznzcn.com\\/product-1741381.html","1272":"https:\\/\\/www.hznzcn.com\\/product-1741403.html","1273":"https:\\/\\/www.hznzcn.com\\/product-1741383.html","1274":"https:\\/\\/www.hznzcn.com\\/product-1741392.html","1275":"https:\\/\\/www.hznzcn.com\\/product-1741398.html","1276":"https:\\/\\/www.hznzcn.com\\/product-1444763.html","1277":"https:\\/\\/www.hznzcn.com\\/product-1741396.html","1278":"https:\\/\\/www.hznzcn.com\\/product-5697.html","1279":"https:\\/\\/www.hznzcn.com\\/product-1683690.html","1280":"https:\\/\\/www.hznzcn.com\\/product-1806752.html","1281":"https:\\/\\/www.hznzcn.com\\/product-1806748.html","1282":"https:\\/\\/www.hznzcn.com\\/product-1772494.html","1283":"https:\\/\\/www.hznzcn.com\\/product-1768261.html","1284":"https:\\/\\/www.hznzcn.com\\/product-1768265.html","1285":"https:\\/\\/www.hznzcn.com\\/product-1790846.html","1286":"https:\\/\\/www.hznzcn.com\\/product-1741390.html"}';
urls = $.parseJSON(urls);

getdata(urls);
console.log(delurl);

function getdata(urls){
    $.each(urls, function(i,v){
        $.ajax({
            url:v,
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