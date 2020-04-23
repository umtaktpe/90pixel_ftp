## 90Pixel FTP Projesi

FTP’de bir klasöre bir sistem tarafından otomatik olarak Excel dosyası olarak atılan kategori ağacının veritabanına aktarılarak sistem yöneticisine bildirim gönderilmesi.

### .env Dosyasının Düzenlenmesi

    FTP_HOSTNAME=
    FTP_USERNAME=
    FTP_PASSWORD=
    QUEUE_CONNECTION=database
    MAIL_USERNAME=
    MAIL_PASSWORD=

### Çalıştırmak İçin

    php artisan key:generate
    php artisan migrate
    php artisan queue:work

### Docker İçin .env Eklenmesi Gerekenler

    DB_HOST=db
    DB_DATABASE=categories
    DB_USERNAME=root
    DB_PASSWORD=root
    
### Docker Ortamında Çalıştırma

    docker-compose build
    docker-compose -d
    docker-compose exec app php artisan migrate
    docker-compose exec app php artisan queue:work