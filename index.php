<?php
$post = $_POST;

$tc_no = isset($post['tc_no']) ? $post['tc_no']: '';
$ad = isset($post['ad']) ? $post['ad'] : '';
$soyad = isset($post['soyad']) ? $post['soyad'] : '';
$dogum_yili = isset($post['dogum_yili']) ? $post['dogum_yili'] : '';

$sonuc = 0;
if(!empty($post)){
    require_once 'tcKimlikDogrulama.php';
    $tckimlikDogrulama = new tcKimlikDogrulama($post);
    $sonuc = $tckimlikDogrulama->kontrol();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>TC Kimlik Doğrulama</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
<form role="form" method="post" action="index.php">
    <?php
        if($sonuc === TRUE){
    ?>
            <div class="alert alert-success" role="alert">TC kimlik numarası doğru.</div>
    <?php }elseif($sonuc === FALSE){ ?>
        <div class="alert alert-danger" role="alert">
            <?php
                foreach($tckimlikDogrulama->getHatalar() AS $hata){?>
                   <div><?php echo $hata;?></div>
            <?php } ?>
        </div>

     <?php } ?>
    <div class="form-group">
        <label for="tc_no">TC No</label>
        <input type="text" class="form-control" name="tc_no" id="tc_no" placeholder="TC No giriniz" value="<?php echo $tc_no;?>" maxlength="11">
    </div>
    <div class="form-group">
        <label for="ad">Ad</label>
        <input type="text" class="form-control" name="ad" id="ad" placeholder="Adınızı giriniz" value="<?php echo $ad;?>">
    </div>
    <div class="form-group">
        <label for="soyad">Soyad</label>
        <input type="text" class="form-control" name="soyad" id="soyad" placeholder="Soyadınızı giriniz" value="<?php echo $soyad;?>">
    </div>
    <div class="form-group">
        <label for="dogum_yili">Doğum Yılı</label>
        <input type="text" class="form-control" name="dogum_yili" id="dogum_yili" placeholder="Doğum yılınızı giriniz" value="<?php echo $dogum_yili;?>">
    </div>

    <button type="submit" class="btn btn-default">Sorgula</button>
</form>
</body>
</html>