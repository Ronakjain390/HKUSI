<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MemberInfo;
use App\Models\ImageBank;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth;
use App\Models\HallBookingInfo; 
use App\Models\EventBooking;
use App\Models\ExportHallBookingInfo;

class PaymentController extends Controller
{
    use UploadTraits;
    //
    function __construct()
    {
        $this->middleware('permission:payment-list|payment-create|payment-edit|payment-delete', ['only' => ['index','store']]);
        $this->middleware('permission:payment-create', ['only' => ['create','store']]);
        $this->middleware('permission:payment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Payment";
        return view('admin.payment.index',compact('headerTitle'));
    }    
    public function paymentDetails(Request $request , $id ,$type){
        $dataId = $id;
        $dataType = $type;
        if ($dataType=="show") {
            $headerTitle = "Payment Details";
        }elseif($dataType=="edit"){
            $headerTitle = "Payment Details";
        }else{
            return redirect()->route('admin.payments.index');
        }
        $PaymentInfo = Payment::find($id);
        if (!empty($PaymentInfo)) {            
            return view('admin.payment.comman',compact('headerTitle','PaymentInfo','dataId','dataType'));          
        }else{
            return redirect()->route('admin.payment.index');
        }
        return redirect()->route('admin.payment.index');
        
    }

    public function multiplepaymentdelet(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $paymentdata) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    Payment::where('id', $paymentdata)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Confirmed'){
                     Payment::where('id', $paymentdata)->update(['status'=>1]);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Cancelled'){
                    Payment::where('id',$paymentdata)->update(['status'=>0]);
                }else{
                    Payment::where('id',$paymentdata)->update(['status'=>1]);
                }
            }
        }
        return redirect()->back();
    }

    public function update(Request $request, $id){
        $input = $request->all();
        Payment::where('id',$id)->update(['payment_status' => $input['payment_status']]);
        return redirect()->route('admin.payments.index')->with('success', 'Hall Booking Info update successfully');
    }
    
    public function destroy($id)
    {
        Payment::where('id',$id)->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Hall Booking Info deleted successfully');
    }
	
	public function updatePaymentStatusByApiStatus(){
		ini_set('max_execution_time', -1);
		$payment = Payment::select('id','order_no','status','payment_status')->where('payment_status','=','CANCELLED')->where('status',0)->whereNotNull('order_no')->orderBy('id','DESC')->get();
		// @dd($payment);
		$i = 0;
		if (isset($payment) && count($payment)) {
			foreach ($payment as $key => $value) {
				$orderNumber    = $value->order_no;
				/*
				$md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA_V2"}'.env('INCOME_COLLECTION_GATEWAY_KEY');
				$md5key  = md5($md5string);
				
				$curl    = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => env('INCOME_COLLECTION_GATEWAY_URL'),
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>'{
					"requestBody": {
						"deptCode": "60380",
						"orderNo": "'.$orderNumber.'",
						"systemCode": "HKUSI_ORMA_V2"
					},
					"sign": "'.$md5key.'"
				}',
				  CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				  ),
				));
				*/
				
				$md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA"}b52c458411d44075a00b7bf2aa7d412f';
                    $md5key         = md5($md5string);

                    $curl           = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://icgw.feo.hku.hk/hkuapi/incomeCollectionGateway/queryPay',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'{
                        "requestBody": {
                            "deptCode": "60380",
                            "orderNo": "'.$orderNumber.'",
                            "systemCode": "HKUSI_ORMA"
                        },
                        "sign": "'.$md5key.'"
                    }',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                      ),
                    ));

				$response = json_decode(curl_exec($curl),true);
				curl_close($curl);
				if(isset($response['data']['transactions']) && count($response['data']['transactions'])){
					$key = 0;
					$key = count($response['data']['transactions']) - 1;
					if(isset($response['data']['transactions']) && count($response['data']['transactions'])){
						$bookinghall = Payment::where('id',$value->id)->update(['payment_status'=>$response['data']['transactions'][$key]['status']]);
						echo "</br>update row payment table id ".$value->id." ---------- Order number ".$orderNumber."</br>";
						$i++;
					}
				}
			}
		} 
		echo '</br>Total Row : '.$i.'</br>Payment status update end'; die;
    }
	
	public function updatePaymentStatusByApiStatusbyId(){
		ini_set('max_execution_time', -1);
		$payment = Payment::select('id','order_no','status','payment_status')->whereIn('id',array('6','9','15','28','32','33','37','51','90','113','123','125','130','132','134','146','148','163','177','178','200','201','204','205','218','222','240','258','299','323','456','458','466','475','477','481','483','484','486','490','491','494','499','527','538','562','576','582','606','619','630','644','649','678','695','696','730','744','767','791','810','813','816','828','850','882','886','889','905','907','919','927','931','932','954','956','967','991','999','1009','1015','1035','1042','1045','1054','1061','1076','1081','1082','1103','1117','1139','1146','1149','1153','1172','1182','1189','1209','1219','1226','1233','1270','1291','1305','1314','1330','1334','1337','1338','1339','1340','1341','1342','1343','1344','1345','1346','1347','1348','1349','1350','1351','1352','1353','1354','1355','1356','1357','1358','1359','1360','1361','1362','1363','1364','1365','1366','1367','1368','1369','1370','1371','1372','1373','1374','1375','1376','1377','1378','1379','1380','1381','1382','1383','1384','1385','1386','1387','1388','1389','1390','1391','1392','1393','1394','1395','1396','1397','1398','1399','1400','1401','1402','1403','1404','1405','1406','1407','1408','1409','1410','1411','1412','1413','1414','1415','1416','1417','1418','1419','1420','1421','1422','1423','1424','1425','1426','1427','1428','1429','1430','1431','1432','1433','1434','1435','1436','1437','1438','1439','1440','1441','1442','1443','1444','1445','1446','1447','1448','1449','1450','1451','1452','1453','1454','1455','1456','1457','1458','1459','1460','1461','1462','1463','1464','1465','1466','1467','1468','1469','1470','1471','1472','1473','1474','1475','1476','1477','1478','1479','1480','1481','1482','1483','1484','1485','1486','1487','1488','1489','1490','1491','1492','1493','1494','1495','1496','1497','1498','1499','1500','1501','1502','1503','1504','1505','1506','1507','1508','1509','1510','1511','1512','1513','1514','1515','1516','1517','1518','1519','1520','1521','1522','1523','1524','1525','1526','1527','1528','1529','1530','1531','1532','1533','1534','1535','1536','1537','1538','1539','1540','1541','1542','1543','1544','1545','1546','1547','1548','1549','1550','1551','1552','1553','1554','1555','1556','1557','1558','1559','1560','1561','1562','1563','1564','1565','1566','1567','1568','1569','1570','1571','1572','1573','1574','1575','1576','1577','1578','1579','1580','1581','1582','1583','1584','1585','1586','1587','1588','1589','1590','1591','1592','1593','1594','1595','1596','1597','1598','1599','1600','1601','1602','1603','1604','1605','1606','1607','1608','1609','1610','1611','1612','1613','1614','1615','1616','1617','1618','1619','1620','1621','1622','1623','1624','1625','1626','1627','1628','1629','1630','1631','1632','1633','1634','1635','1636','1637','1638','1639','1640','1641','1642','1643','1644','1645','1646','1647','1648','1649','1650','1651','1652','1653','1654','1655','1656','1657','1658','1659','1660','1661','1662','1663','1664','1665','1666','1667','1668','1669','1670','1671','1672','1673','1674','1675','1676','1677','1678','1679','1680','1681','1682','1683','1684','1685','1686','1687','1688','1689','1690','1691','1692','1693','1694','1695','1696','1697','1698','1699','1700','1701','1702','1703','1704','1705','1706','1707','1708','1709','1710','1711','1712','1713','1714','1715','1716','1717','1718','1719','1720','1721','1722','1723','1724','1725','1726','1727','1728','1729','1730','1731','1732','1733','1734','1735','1736','1737','1738','1739','1740','1741','1742','1743','1744','1745','1746','1747','1748','1749','1750','1751','1752','1753','1754','1755','1756','1757','1758','1759','1760','1761','1762','1763','1764','1765','1766','1767','1768','1769','1770','1771','1772','1773','1774','1775','1776','1777','1778','1779','1780','1781','1782','1783','1784','1785','1786','1787','1788','1789','1790','1791','1792','1793','1794','1795','1796','1797','1798','1799','1800','1801','1802','1803','1804','1805','1806','1807','1808','1809','1810','1811','1812','1813','1814','1815','1816','1817','1818','1819','1820','1821','1822','1823','1824','1825','1826','1827','1828','1829','1830','1831','1832','1833','1834','1835','1836','1837','1838','1839','1840','1841','1842','1843','1844','1845','1846','1847','1848','1849','1850','1851','1852','1853','1854','1855','1856','1857','1858','1859','1860','1861','1862','1863','1864','1865','1866','1867','1868','1869','1870','1871','1872','1873','1874','1875','1876','1877','1878','1879','1880','1881','1882','1883','1884','1885','1886','1887','1888','1889','1890','1891','1892','1893','1894','1895','1896','1897','1898','1899','1900','1901','1902','1903','1904','1905','1906','1907','1908','1909','1910','1911','1912','1913','1914','1915','1916','1917','1918','1919','1920','1921','1922','1923','1924','1925','1926','1927','1928','1929','1930','1931','1932','1933','1934','1935','1936','1937','1938','1939','1940','1941','1942','1943','1944','1945','1946','1947','1948','1949','1950','1951','1952','1953','1954','1955','1956','1957','1958','1959','1960','1961','1962','1963','1964','1965','1966','1967','1968','1969','1970','1971','1972','1973','1974','1975','1976','1977','1978','1979','1980','1981','1982','1983','1984','1985','1986','1987','1988','1989','1990','1991','1992','1993','1994','1995','1996','1997','1998','1999','2000','2001','2002','2003','2004','2005','2006','2007','2008','2009','2010','2011','2012','2013','2014','2015','2016','2017','2018','2019','2020','2021','2022','2023','2024','2025','2026','2027','2028','2029','2030','2031','2032','2033','2034','2035','2036','2037','2038','2039','2040','2041','2042','2043','2044','2045','2046','2047','2048','2049','2050','2051','2052','2053','2054','2055','2056','2057','2058','2059','2060','2061','2062','2063','2064','2065','2066','2067','2068','2069','2070','2071','2072','2073','2074','2075','2076','2077','2078','2079','2080','2081','2082','2083','2084','2085','2086','2087','2088','2089','2090','2091','2092','2093','2094','2095','2096','2097','2098','2099','2100','2101','2102','2103','2104','2105','2106','2107','2108','2109','2110','2111','2112','2113','2114','2115','2116','2117','2118','2119','2120','2121','2122','2123','2124','2125','2126','2127','2128','2129','2130','2131','2132','2133','2134','2135','2136','2137','2138','2139','2140','2141','2142','2143','2144','2145','2146','2147','2148','2149','2150','2151','2152','2153','2154','2155','2156','2157','2158','2159','2160','2161','2162','2163','2164','2165','2166','2167','2168','2169','2170','2171','2172','2173','2174','2175','2176','2177','2178','2179','2180','2181','2182','2183','2184','2185','2186','2187','2188','2189','2190','2191','2192','2193','2194','2195','2196','2197','2198','2199','2200','2201','2202','2203','2204','2205','2206','2207','2208','2209','2210','2211','2212','2213','2214','2215','2216','2217','2218','2219','2220','2221','2222','2223','2224','2225','2226','2227','2228','2229','2230','2231','2232','2233','2234','2235','2236','2237','2238','2239','2240','2241','2242','2243','2244','2245','2246','2247','2248','2249','2250','2251','2252','2253','2254','2255','2256','2257','2258','2259','2260','2261','2262','2263','2264','2265','2266','2267','2268','2269','2270','2271','2272','2273','2274','2275','2276','2277','2278','2293','2312','2313','2319','2333','2338','2348','2360','2363','2371','2375','2384','2387','2392','2402','2407','2412','2422','2424','2426','2427','2430','2434','2436','2445','2458','2475','2477','2484','2486','2487','2510','2519','2522','2527','2531','2539','2545','2546','2547','2548','2552','2553','2555','2556','2563','2581','2582','2589','2595','2596','2598','2609','2611','2616','2619','2624','2634','2646','2647','2649','2659','2665','2669','2671','2682','2690','2694','2703','2705','2711','2712','2715','2716','2731','2743','2744','2745','2748','2761','2762','2770','2771','2775','2777','2781','2791','2799'))->whereNotNull('order_no')->orderBy('id','DESC')->get();
		// @dd($payment);
		$i = 0;
		if (isset($payment) && count($payment)) {
			foreach ($payment as $key => $value) {
				$orderNumber    = $value->order_no;
				/*
				$md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA_V2"}'.env('INCOME_COLLECTION_GATEWAY_KEY');
				$md5key  = md5($md5string);
				
				$curl    = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => env('INCOME_COLLECTION_GATEWAY_URL'),
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>'{
					"requestBody": {
						"deptCode": "60380",
						"orderNo": "'.$orderNumber.'",
						"systemCode": "HKUSI_ORMA_V2"
					},
					"sign": "'.$md5key.'"
				}',
				  CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				  ),
				));
				*/
				
				$md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA"}b52c458411d44075a00b7bf2aa7d412f';
                    $md5key     = md5($md5string);

                    $curl       = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://icgw.feo.hku.hk/hkuapi/incomeCollectionGateway/queryPay',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'{
                        "requestBody": {
                            "deptCode": "60380",
                            "orderNo": "'.$orderNumber.'",
                            "systemCode": "HKUSI_ORMA"
                        },
                        "sign": "'.$md5key.'"
                    }',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                      ),
                    ));
				$bookinghall = Payment::where('id',$value->id)->update(['payment_status'=>'EXPIRED']);
				/*
				$response = json_decode(curl_exec($curl),true);
				curl_close($curl);
				if(isset($response['data']['transactions']) && count($response['data']['transactions'])){
					$key = 0;
					$key = count($response['data']['transactions']) - 1;
					if(isset($response['data']['transactions']) && count($response['data']['transactions'])){
						$bookinghall = Payment::where('id',$value->id)->update(['payment_status'=>'EXPIRED']);
						echo "</br>update row payment table id ".$value->id." ---------- Order number ".$orderNumber."</br>";
						$i++;
					}
				}
				*/
			}
		} 
		echo '</br>Total Row : '.$i.'</br>Payment status update end'; die;
    }
	
	public function statusComplatedToCompleted(){
		ini_set('max_execution_time', -1);
		
		// Table1  hall_booking_infos
			$hallBooking = HallBookingInfo::where('status','=','Complated')->get();
			$i = 0;
			if (isset($hallBooking) && count($hallBooking) > 0) {
				foreach ($hallBooking as $key => $hallBookingValue) {
					$hallBookingId    = $hallBookingValue->id;
					if(!empty($hallBookingId)){
						$bookinghall = HallBookingInfo::where('id',$hallBookingId)->update(['status'=>'Completed']);
						echo "</br>Hall Booking : ----------------------------------------</br>";
						echo "</br>Update row hall Booking table id ".$hallBookingId."</br>";
						$i++;
					}
				}
			} 
			echo '</br>Total Row : '.$i.'</br>Hall Booking status update end';
		// Table1  hall_booking_infos
		
		// Table1  EventBooking
			$eventBooking = EventBooking::where('booking_status','=','Complated')->get();
			$j = 0;
			if (isset($eventBooking) && count($eventBooking) > 0) {
				foreach ($eventBooking as $key => $eventBookingValue) {
					$eventBookingId    = $eventBookingValue->id;
					if(!empty($eventBookingId)){
						$bookinghall = EventBooking::where('id',$eventBookingId)->update(['booking_status'=>'Completed']);
						echo "</br>Event Booking : ----------------------------------------</br>";
						echo "</br>Update row Event Booking table id ".$eventBookingId."</br>";
						$j++;
					}
				}
			} 
			echo '</br>Total Row : '.$j.'</br>EventBooking status update end'; 
		// Table1  EventBooking
		
		// Table1  ExportHallBookingInfo
			$ExportHallBookingInfo = ExportHallBookingInfo::where('status','=','Complated')->get();
			$k = 0;
			if (isset($ExportHallBookingInfo) && count($ExportHallBookingInfo) > 0) {
				foreach ($ExportHallBookingInfo as $key => $ExportHallBookingInfoValue) {
					$ExportHallBookingInfoId    = $ExportHallBookingInfoValue->id;
					if(!empty($ExportHallBookingInfoId)){
						$result = ExportHallBookingInfo::where('id',$ExportHallBookingInfoId)->update(['status'=>'Completed']);
						echo "</br>ExportHallBookingInfo : ----------------------------------------</br>";
						echo "</br>Update row ExportHallBookingInfo table id ".$ExportHallBookingInfoId."</br>";
						$k++;
					}
				}
			} 
			echo '</br>Total Row : '.$k.'</br>ExportHallBookingInfo status update end'.'</br>'; 
		// Table1  ExportHallBookingInfo
		
		echo "<br>Script End"; die;
    }

}
