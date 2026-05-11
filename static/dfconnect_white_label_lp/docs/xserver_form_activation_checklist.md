# DF CONNECT LP 問い合わせフォーム（XServer反映）チェックリスト

作成日: 2026-05-11
対象LP: `static/dfconnect_white_label_lp`

## 目的
XServer上の`dfconnect.jp/public_html`に置いたLPで、`api/contact.php`経由のお問い合わせフォームを本番稼働させる。

## 前提
- すでにLP本体は公開済み（`public_html`に`index.php`, `api/`, `app/`, `config/`, `css/`, `js/`, `assets/`, `database/` がある状態）
- WordPressディレクトリ（`haru`, `tpi`, `livesta`）は削除していない
- `*.md` は公開アップロード対象から外す

## 1) 本番設定ファイルを作る

`/home/xs521344/dfconnect.jp/public_html/config` 内で、以下を作成して本番値に書き換える。

1. `app.example.php` → `app.php`
2. `db.example.php` → `db.php`
3. `mail.example.php` → `mail.php`

### app.php
- `admin_email`: 受信先管理者メール
- `admin_name`: 表示名
- `from_email`: 送信元（例: `no-reply@dfconnect.jp`）
- `base_url`: `https://dfconnect.jp`
- `debug`: `false`

### db.php
- `dsn`: `mysql:host=<DBホスト>;dbname=<DB名>;charset=utf8mb4`
- `username`: DBユーザー
- `password`: DBパスワード

### mail.php
- `host`: `smtp.xserver.ne.jp`
- `port`: `465`（TLS版も可）
- `username`: `no-reply@dfconnect.jp`
- `password`: SMTPパスワード
- `encryption`: 通常 `ssl`

### 例: 作業コマンド（SSH先で）
```bash
cd /home/xs521344/dfconnect.jp/public_html/config
cp app.example.php app.php
cp db.example.php db.php
cp mail.example.php mail.php
# 必要箇所を編集
```

## 2) PHPMailer導入（vendorの用意）
現在構成は`PHPMailer\\PHPMailer`を使うため、`vendor`が必須。

### A. ローカルで生成してアップロードする（推奨）
```bash
cd "/Users/satou/Desktop/webscript/lp-website/static/dfconnect_white_label_lp"
composer install --no-dev --optimize-autoloader
```

`vendor/`をXServerへ同期：
```bash
rsync -av --exclude='.DS_Store' \
  "/Users/satou/Desktop/webscript/lp-website/static/dfconnect_white_label_lp/vendor/" \
xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/vendor/
```

### B. サーバ側で実行する場合
```bash
cd /home/xs521344/dfconnect.jp/public_html
composer install --no-dev --optimize-autoloader
```

## 3) DBテーブル作成

`database/schema.sql` をDBへ流す。

```bash
mysql -h <DB_HOST> -u <DB_USER> -p <DB_NAME> < /home/xs521344/dfconnect.jp/public_html/database/schema.sql
```

- `inquiries`
- `inquiry_attempts`

の2テーブルが作成される。

## 4) 権限・公開保護の最終確認
- `public_html`直下の`.htaccess`で以下が有効
  - HTTPSリダイレクト
  - `app|config|database|storage|vendor` への直接アクセス拒否
  - `*.md`を除外
- `storage/logs/contact_error.log` の書き込み先が問題なく作れる権限

## 5) 送信テスト
1. `https://dfconnect.jp/` を開く
2. フォームに必須項目を入力して送信
3. 成功時: `thanks.html` へ遷移
4. 失敗時: `index.php#contact-form` に戻り、項目エラーや共通エラーが表示
5. DB確認
```sql
SELECT id, public_id, company, name, email, inquiry_type, status, created_at
FROM inquiries
ORDER BY id DESC
LIMIT 5;
```
6. メール確認
- 管理者メール受信
- 自動返信（送信者）受信

## 6) 想定エラーと対処
- **PHPMailer not installed**: `vendor`未配置
- **DB接続エラー**: `db.php`の`dsn`/`username`/`password`/DB名を再確認
- **SMTPエラー**: `mail.php`のアカウント情報・暗号化・ポート確認（XServer側制限も確認）
- **403**で`config`を見れない: 正常（公開保護されているため）

## 7) 運用時に触らない/確認すること
- WordPress領域（`haru`, `tpi`, `livesta`）は削除しない
- `*.md` はLP公開同期時に除外する

```bash
# 参考（アップロード時）
rsync -av --exclude='.DS_Store' --exclude='docs' --exclude='reference' --exclude='*.md' \
  "/Users/satou/Desktop/webscript/lp-website/static/dfconnect_white_label_lp/" \
xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/
```

