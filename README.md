<html>
<body>
https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?wsdl web servise ile TC no, Ad, Soyad ve doğum yılı bilgilerini kontrol etme işlemini yapıyor
</br>
Sorgulama işlemi yapmadan önce tc kimlik algoritması kontrolü yapılır bu kontrolü geçerse web service sorgusu yapıyor

<p>$params['tc_no'] = "11111111111";</p>
<p>$params['ad'] = "AHMET";</p>
<p>$params['soyad'] = "CAN";</p>
<p>$params['dogum_yili'] = '1989';</p>
<p>require_once 'tcKimlikDogrulama.php';</p>
<p>$tckimlikDogrulama = new tcKimlikDogrulama($params);</p>
<p>$sonuc = $tckimlikDogrulama->kontrol();</p>

<p>if($sonuc === TRUE){</p>
<p>    echo "TC kimlik numarası doğru.";</p>
<p>}else{</p>
<p>    foreach($tckimlikDogrulama->getHatalar() AS $hata){</p>
<p>        echo "$hata";</p>
<p>    }</p>
<p>}</p>
</body>
</html>