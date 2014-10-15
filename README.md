tckimlik
========

https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?wsdl web servise ile TC no, Ad, Soyad ve doğum yılı bilgilerini kontrol etme işlemini yapıyor

Sorgulama işlemi yapmadan önce tc kimlik algoritması kontrolü yapılır bu kontrolü geçerse web service sorgusu yapıyor

$params['tc_no'] = "11111111111";
$params['ad'] = "AHMET";
$params['soyad'] = "CAN";
$params['dogum_yili'] = '1989';
require_once 'tcKimlikDogrulama.php';
$tckimlikDogrulama = new tcKimlikDogrulama($params);
$sonuc = $tckimlikDogrulama->kontrol();

if($sonuc === TRUE){
    echo "TC kimlik numarası doğru.";
}else{
    foreach($tckimlikDogrulama->getHatalar() AS $hata){
        echo "<div>$hata</div>;
    }
}