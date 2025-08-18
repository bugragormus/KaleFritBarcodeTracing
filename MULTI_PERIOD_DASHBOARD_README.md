# Çoklu Periyot Dashboard Özelliği

Bu özellik, dashboard sayfasını çoklu periyot desteği ile günceller ve artık sadece günlük değil, farklı zaman periyotlarında rapor oluşturmanızı sağlar.

## 🚀 Yeni Özellikler

### 📊 Periyot Seçenekleri

1. **Günlük** - Seçilen günün verileri
2. **Haftalık** - Seçilen günün bulunduğu hafta (gelecekteki tarihler sınırlanır)
3. **Aylık** - Seçilen günün bulunduğu ay (gelecekteki tarihler sınırlanır)
4. **3 Aylık** - Seçilen günün bulunduğu çeyrek (gelecekteki tarihler sınırlanır)
5. **Yıllık** - Seçilen günün bulunduğu yıl (gelecekteki tarihler sınırlanır)
6. **Özel Tarih Aralığı** - İstediğiniz başlangıç ve bitiş tarihleri

### 🎯 Periyot Seçici

-   **Page Header**: Sadece başlık ve hızlı export butonu
-   **Ayrı Filtreler Bölümü**: Tarih ve periyot seçicileri
-   Otomatik form submit ile anında güncelleme
-   Seçilen periyoda göre veriler toplanır
-   Özel tarih aralığı seçimi için date picker
-   Gelecekteki tarihler otomatik olarak bugüne sınırlanır

### 📈 Veri Toplama

-   **Üretim Verileri**: Seçilen periyoda göre toplanır
-   **Vardiya Raporu**: Sadece günlük periyotta mevcuttur
-   **Fırın Performansı**: Periyot bazında hesaplanır
-   **Red Sebepleri**: Periyot bazında analiz edilir
-   **OEE ve AI/ML**: Her zaman güncel tarihe göre çalışır

## 🔧 Teknik Detaylar

### Controller Güncellemeleri

-   `calculatePeriodInfo()` metodu eklendi
-   `getProductionData()` metodu eklendi
-   Tüm veri toplama metodları periyot parametresi aldı
-   Export metodu periyot desteği eklendi

### View Güncellemeleri

-   Periyot seçici eklendi
-   Üretim özeti kartı periyot bilgileri ile güncellendi
-   Vardiya raporu sadece günlük periyotta gösteriliyor
-   Export butonları seçili periyoda göre çalışıyor

### Export Güncellemeleri

-   Excel dosya adı periyot bilgisi içeriyor
-   Tüm sheet'ler periyot bilgileri ile güncellendi
-   Vardiya raporu sheet'i periyot kontrolü yapıyor

## 📋 Kullanım

### 1. Periyot Seçimi

```
1. Dashboard sayfasında header'da periyot seçici bulunur
2. İstediğiniz periyodu seçin (Günlük, Haftalık, Aylık, 3 Aylık, Yıllık, Özel Tarih Aralığı)
3. Özel tarih aralığı seçerseniz, başlangıç ve bitiş tarihlerini belirleyin
4. Sayfa otomatik olarak yenilenir
```

### 2. Tarih Seçimi

```
1. Tarih seçici ile istediğiniz tarihi seçin
2. Seçilen tarih, seçilen periyodun başlangıç noktası olur
3. Periyot otomatik olarak hesaplanır
4. Gelecekteki tarihler otomatik olarak bugüne sınırlanır
```

### 3. Export

```
1. Export butonları seçili periyoda göre çalışır
2. Excel dosya adı periyot bilgisi içerir
3. Tüm veriler seçili periyoda göre toplanır
4. Özel tarih aralığı için başlangıç ve bitiş tarihleri export'a dahil edilir
```

## 🎨 UI Güncellemeleri

### Filtreler Bölümü Tasarımı

-   **Ayrı Bölüm**: Page header'ın altında ayrı bir kart
-   **Modern Tasarım**: Şeffaf, blur efektli arka plan
-   **Responsive**: Mobil ve masaüstü uyumlu
-   **Düzenli Yerleşim**: Filtreler yan yana, bilgiler alt kısımda

### Periyot Seçici Stilleri

-   Modern, şeffaf tasarım
-   Hover ve focus efektleri
-   Responsive tasarım
-   Dashboard teması ile uyumlu

### Özel Tarih Aralığı Seçici

-   İki tarih input'u (başlangıç ve bitiş)
-   Otomatik tarih validasyonu
-   Gelecekteki tarihler için maksimum sınır
-   Modern buton tasarımı

### Kart Güncellemeleri

-   Periyot bilgileri kart başlıklarında
-   Tarih aralığı bilgileri
-   Dinamik buton metinleri

## 🔍 Önemli Notlar

### Vardiya Raporu

-   Sadece günlük periyotta mevcuttur
-   Diğer periyotlarda gizlenir
-   Export'ta da periyot kontrolü yapılır

### OEE ve AI/ML

-   Her zaman güncel tarihe göre çalışır
-   Periyot seçiminden etkilenmez
-   Gerçek zamanlı veriler

### Veri Tutarlılığı

-   Tüm veriler seçilen periyoda göre toplanır
-   Tarih aralıkları otomatik hesaplanır
-   Periyot değişikliklerinde veriler güncellenir
-   Gelecekteki tarihler otomatik olarak bugüne sınırlanır
-   Özel tarih aralığı için tam kontrol

## 🚀 Gelecek Geliştirmeler

-   Periyot karşılaştırma grafikleri
-   Trend analizi
-   Performans metrikleri
-   Daha detaylı filtreleme seçenekleri
-   Tarih aralığı karşılaştırma
-   Gelişmiş periyot analizi
