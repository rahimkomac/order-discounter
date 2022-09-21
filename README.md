<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://www.pngitem.com/pimgs/m/69-695417_discount-icon-png-png-download-discount-icon-vector.pnghttps://www.pngitem.com/pimgs/m/69-695417_discount-icon-png-png-download-discount-icon-vector.png?w=1200&h=675" width="400"></a></p>

## Order discount (Laravel, Docker)

## Installation Steps

1. Clone the repository

```bash
git clone https://github.com/rahimkomac/order-discounter.git
```

2. Change the working directory

```bash
cd order-discounter
```

3. set env

```bash
cp .env.example .env
```

4. Composer Install

```bash
composer install
```

5. Install dependencies

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

6. Call URL
```bash
http://ideasoft.test/ or http://localhost
```

Postman Collection:

Repodaki order.postman_collection.json dosyasını import ederek test edebilirsiniz.
- [order.postman_collection.json](./order.postman_collection.json)
