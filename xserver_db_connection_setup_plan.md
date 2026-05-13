# XServer DB接続反映プラン（haru / tpi / livesta + LPフォーム）

## 1. 目的
- haru / tpi / livesta の WordPress 各環境と、
- LP の `api/contact.php` 用フォームDB接続を、XServer実DBへ接続させる。
- 既存の `haru`, `tpi`, `livesta` ディレクトリは削除しない。

## 2. 現状（確認済み）
- LP本体 `public_html/config` には `app.php` / `db.example.php` / `mail.example.php` のみ。
- `public_html/config/db.php` は未作成。
- haru / tpi / livesta 各サイトとも `xserver-db-config.php.example` は存在するが、`xserver-db-config.php` は未作成。
- そのため、現状はXServer接続テストが未完了状態。

## 3. 反映対象ファイル
- LPフォーム: 
  - `/home/xs521344/dfconnect.jp/public_html/config/db.php`
- haru: 
  - `/home/xs521344/dfconnect.jp/public_html/haru/xserver-db-config.php`
- tpi: 
  - `/home/xs521344/dfconnect.jp/public_html/tpi/xserver-db-config.php`
- livesta: 
  - `/home/xs521344/dfconnect.jp/public_html/livesta/xserver-db-config.php`

## 4. ローカル準備（ファイル内容のテンプレ）

### 4-1. LP用 `db.php`
`static/dfconnect_white_label_lp/config/db.example.php` から作成

```php
<?php

return [
    'dsn' => 'mysql:host=<DB_HOST>;dbname=<DB_NAME>;charset=utf8mb4',
    'username' => '<DB_USER>',
    'password' => '<DB_PASSWORD>',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
```

### 4-2. WordPress 共通 `xserver-db-config.php`
各WP同様のテンプレ。

```php
<?php
putenv('WORDPRESS_DB_NAME=<DB_NAME>');
putenv('WORDPRESS_DB_USER=<DB_USER>');
putenv('WORDPRESS_DB_PASSWORD=<DB_PASSWORD>');
putenv('WORDPRESS_DB_HOST=<DB_HOST>');
putenv('WORDPRESS_DB_CHARSET=utf8mb4');
putenv('WORDPRESS_DB_COLLATE=');
putenv('WORDPRESS_TABLE_PREFIX=wp_');
putenv('WORDPRESS_DEBUG=');
```

## 5. 実サーバー反映手順

```bash
# LPフォーム
cp static/dfconnect_white_label_lp/config/db.example.php static/dfconnect_white_label_lp/config/db.php
# 値を本番用に差し替え
scp static/dfconnect_white_label_lp/config/db.php xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/config/db.php

# WP 各テーマ
scp wordpress/haru-theme/upload-root/xserver-db-config.php.example xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/haru/xserver-db-config.php
scp wordpress/tpi-theme/upload-root/xserver-db-config.php.example xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/tpi/xserver-db-config.php
scp wordpress/livesta-theme/upload-root/xserver-db-config.php.example xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/livesta/xserver-db-config.php

# 反映後に各ファイル内の __SET_XSERVER_DB_... / placeholder を実値に編集
```

## 6. 接続情報（記入欄）

- LPフォーム DB
  - HOST: 
  - NAME: 
  - USER: 
  - PASSWORD: 

- haru DB
  - HOST: 
  - NAME: 
  - USER: 
  - PASSWORD: 
  - TABLE_PREFIX（必要なら）: 

- tpi DB
  - HOST: 
  - NAME: 
  - USER: 
  - PASSWORD: 
  - TABLE_PREFIX（必要なら）: 

- livesta DB
  - HOST: 
  - NAME: 
  - USER: 
  - PASSWORD: 
  - TABLE_PREFIX（必要なら）: 

## 7. 接続確認コマンド（反映後）

```bash
# LPフォームのDB接続確認
ssh xserver-dfconnect 'php -r "
require "/home/xs521344/dfconnect.jp/public_html/app/bootstrap.php"; 
try { new PDO("mysql:host='."${LP_DB_HOST}".';dbname='."${LP_DB_NAME}".';charset=utf8mb4", "'."${LP_DB_USER}".'", "'."${LP_DB_PASSWORD}".'", [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]); echo "LP DB: OK\n"; } catch (Throwable $e){ echo "LP DB: NG\n".$e->getMessage()."\n"; }
"'
```

```bash
# WordPress 側（例: haru）
ssh xserver-dfconnect "cd /home/xs521344/dfconnect.jp/public_html/haru && php -r 'require "wp-load.php"; echo DB_NAME."\n"; echo DB_HOST."\n"; echo "WP HARU: DB_LOAD_OK\n";'"
```

```bash
# LPフォーム用テーブル作成
ssh xserver-dfconnect "mysql -h <LP_DB_HOST> -u <LP_DB_USER> -p'<LP_DB_PASSWORD>' <LP_DB_NAME> < /home/xs521344/dfconnect.jp/public_html/database/schema.sql"
```

## 8. 反映後の確認
- `https://dfconnect.jp/` が正常表示
- フォーム送信/保存、`db_error` や DB接続失敗がログに出ない
- `haru`, `tpi`, `livesta` がそれぞれのDBに対して表示・ログインが成立
