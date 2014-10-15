<?php
/**
 * tc kimlik doğrulama işlemi
 * Created by PhpStorm.
 * User: eyapici
 * Date: 14/10/14
 * Time: 21:47
 */

class tcKimlikDogrulama {

    private $location = "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?wsdl";
    private $tc_no;
    private $ad;
    private $soyad;
    private $dogum_yili;
    private $hatalar = array();

    /**
     * @param $params
     */
    public function __construct($params){
        $this->tc_no = isset($params['tc_no']) ? self::clean($params['tc_no'],'double') : '';
        $this->ad = isset($params['ad']) ? self::clean($params['ad']) : '';
        $this->soyad = isset($params['soyad']) ? self::clean($params['soyad']) : '';
        $this->dogum_yili = isset($params['dogum_yili']) ? self::clean($params['dogum_yili'],'int') : '';
    }

    /**
     * Kontrol işlemi
     * @return bool
     */
    public function kontrol(){
        $this->tckimlikKontrol($this->tc_no);

        if($this->ad == ''){
            $this->hatalar['ad'] = 'Ad boş olamaz!';
        }
        if($this->soyad == ''){
            $this->hatalar['soyad'] = 'Ad boş olamaz!';
        }
        if($this->dogum_yili < 1900 || $this->dogum_yili > date('Y')){
            $this->hatalar['dogum_yili'] = 'Doğum yılı geçersiz!';
        }

        if(empty($this->hatalar)){
            try{
                $client = new SoapClient($this->location);

                $params['TCKimlikNo'] = (double)$this->tc_no;
                $params['Ad'] = self::upper($this->ad);
                $params['Soyad'] = self::upper($this->soyad);

                $params['DogumYili'] = $this->dogum_yili;
                $response = $client->__soapCall("TCKimlikNoDogrula",array(0=>$params));

                if($response->TCKimlikNoDogrulaResult === TRUE){
                    return TRUE;
                }else{
                    $this->hatalar[] = 'Girilen bilgiler tc kimlik no bilgileri ile uyuşmuyor!';
                    return FALSE;
                }
            }catch (Exception $e){
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    /***
     * Tc kimlik no algoritması kontrol fonksiyonu
     * @param $str
     */
    public function tckimlikKontrol($str){
        $arg = str_split($str);
        if(count($arg) != 11){
            $this->hatalar['tc_no'] = 'TC no 11 karakter olmalıdır!';
        }else{
            /**
             * Tc kimlik nonun 1. karakteri 0 olamaz
             */
            if($arg[0] == 0){
                $this->hatalar['tc_no'] = 'TC no ilk hanesi 0 olamaz!';
            }else{
                $sum1 = $arg[0] + $arg[2] + $arg[4] + $arg[6] + $arg[8];
                $sum2 = $arg[1] + $arg[3] + $arg[5] + $arg[7];

                $kontro1 = ($sum1*7 - $sum2)%10;
                /**
                 * 1. 3. 5. 7. ve 9. hanelerin toplamının 7 katından, 2. 4. 6. ve 8. hanelerin toplamı çıkartıldığında,
                 * elde edilen sonucun 10'a bölümünden kalan, yani Mod10'u bize 10. haneyi verir.
                 */
                if($kontro1 != $arg[9]){
                    $this->hatalar['tc_no'] = 'TC kimlik no yanlış girdiniz!';
                }else{
                    /**
                     * 1. 2. 3. 4. 5. 6. 7. 8. 9. ve 10. hanelerin toplamından elde edilen sonucun 10'a bölümünden
                     * kalan, yani Mod10'u bize 11. haneyi verir.
                     */
                    $kontro12 = ($sum1 + $sum2 + $arg[9]) % 10;
                    if($kontro12 != $arg[10]){
                        $this->hatalar['tc_no'] = 'TC kimlik no yanlış girdiniz!';
                    }
                }
            }
        }
    }

    /**
     * Hataları geri döndürür
     * @return array
     */
    public function getHatalar(){
        return $this->hatalar;
    }

    /**
     * Değişkeni temizleme işlemi
     * @param $str
     * @param string $tip
     * @return int|string
     */
    static function clean($str,$tip = 'string'){
        $str = strip_tags( trim($str) );
        if($tip == 'int'){
            $str = (int)$str;
        }else{
            $str = (string)$str;
        }
        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    static function upper($str){
        return mb_strtoupper(str_replace(array('ı', 'ğ', 'ü', 'ş', 'i', 'ö', 'ç'), array('I', 'Ğ', 'Ü', 'Ş', 'İ', 'Ö', 'Ç'), $str), 'utf-8');
    }
} 