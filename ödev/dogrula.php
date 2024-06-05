<?php
$servername = "localhost";
$username = "root";  
$password = ""; 
$dbname = "tc_kimlik_dogrulama";

// Veritabanı bağlantısını oluşturdum
$baglanti = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol ettik
if ($baglanti->connect_error) {
    die("Bağlantı hatası: " . $baglanti->connect_error);
}

// T.C. kimlik numarasını doğrulamak için fonksiyon tanımladım.
function tcKimlikDogrula($tcKimlikNo) {
    // T.C. kimlik numarasının uzunluğu kontrol ettim
    if (strlen($tcKimlikNo) != 11) {
        return false;
    }
    // T.C. kimlik numarasının sadece rakamlardan oluşup oluşmadığı kontrol ettim
    if (!ctype_digit($tcKimlikNo)) {
        return false;
    }
    // T.C. kimlik numarasının ilk hanesinin 0 olup olmadığı kontrol ettim
    if ($tcKimlikNo[0] == '0') {
        return false;
    }

    // T.C. kimlik numarasının her bir hanesini ayrı ayrı dizi elemanı olarak aldık.
    $hane = str_split($tcKimlikNo);

    // Tek hanelerin toplamı (1,3,5,7 ve 9. haneler)
    $tekToplam = $hane[0] + $hane[2] + $hane[4] + $hane[6] + $hane[8];
    // Çift hanelerin toplamı (2,4,6 ve 8. haneler)
    $ciftToplam = $hane[1] + $hane[3] + $hane[5] + $hane[7];

    // 10. hanenin kontrolü (tek hanelerin toplamının 7 katı eksi çift hanelerin toplamının 10'a bölümünden kalan)
    if (($tekToplam * 7 - $ciftToplam) % 10 != $hane[9]) {
        return false;
    }

    // İlk 10 hanenin toplamının 10'a bölümünden kalan, 11. haneye eşit olmalı
    $toplamSum = array_sum(array_slice($hane, 0, 10));
    if ($toplamSum % 10 != $hane[10]) {
        return false;
    }

    return true;
}

// POST ile gelen veriyi kontrol ettik
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tc_kimlik_no = $_POST["tc_kimlik_no"]; // Kullanıcıdan gelen T.C. kimlik numarasını aldık

    if (tcKimlikDogrula($tc_kimlik_no)) {
        // Veritabanında kullanıcıyı bulmak için sorgu 
        $sorgu = $baglanti->prepare("SELECT ad, soyad FROM kullanicilar WHERE tc_kimlik_no = ?");
        $sorgu->bind_param("s", $tc_kimlik_no);
        $sorgu->execute();
        $sorgu->bind_result($ad, $soyad);
        
        // Sonuçları kontrol ettik
        if ($sorgu->fetch()) {
            echo "T.C. Kimlik No: $tc_kimlik_no<br>";
            echo "Ad: $ad<br>";
            echo "Soyad: $soyad<br>";
        } else {
            echo "Geçersiz T.C. Kimlik No veya kayıt bulunamadı.";
        }
        
        $sorgu->close();
    } else {
        echo "Geçersiz T.C. Kimlik No";
    }
}

$baglanti->close();
?>
