# DF CONNECT ホワイトラベル制作・運用保守LP

`dfconnect_white_label_lp` は、既存LPの見た目を維持したまま、フォーム送信をXServer用PHP実装へ置き換えた構成です。

## ファイル構成

```txt
dfconnect_white_label_lp/
├── index.php                 # 既存HTML互換でCSRF発行・フラッシュ表示
├── index.html.bak            # 旧HTML（保守用）
├── css/
│   └── style.css
├── js/
│   └── main.js
├── assets/
│   ├── img/
│   └── icons/
├── api/
│   └── contact.php           # POST受付エンドポイント
├── app/
│   ├── bootstrap.php
│   ├── form_options.php
│   ├── helpers.php
│   ├── Csrf.php
│   ├── Validator.php
│   ├── InquiryRepository.php
│   ├── RateLimiter.php
│   ├── MailService.php
│   └── .htaccess
├── config/
│   ├── app.example.php
│   ├── db.example.php
│   ├── mail.example.php
│   ├── app.php               # 本番値。Git管理対象外
│   ├── db.php                # 本番値。Git管理対象外
│   ├── mail.php              # 本番値。Git管理対象外
│   └── .gitignore
├── database/
│   ├── schema.sql
│   └── .htaccess
├── storage/
│   └── logs/
│       └── contact_error.log
│       └── .htaccess
├── composer.json
├── thanks.html
├── privacy.html
├── docs/
│   └── form-requirements.md
└── README.md
```

## 実装済みの主な点

- `index.php` 化とCSRFトークン発行
- POST時のみ受ける `/api/contact.php`
- honeypot / CSRF / レート制限（IP+メール）
- サーバー側バリデーション
- MySQL/MariaDB保存（`inquiries` / `inquiry_attempts`）
- PHPMailerで管理者通知 + 自動返信
- エラー時の入力値保持とフォーム差し戻し（303）
- `thanks.html` / `privacy.html` 追加
- 機密ディレクトリへの直接アクセス拒否（`.htaccess`）

## ローカル確認

```
cd dfconnect_white_label_lp
php -S localhost:8080
```

`http://localhost:8080/index.php` を開いて以下を確認します。

- 送信ボタンで通常POST送信されること
- エラー時に値が保持されること
- 成功/失敗で表示内容が崩れないこと

## デプロイ時の簡易チェック

1. `config/app.php`, `config/db.php`, `config/mail.php` に本番値を設置
2. `composer install --no-dev` を実行（`vendor/`をアップロード）
3. `database/schema.sql` を本番DBへ適用
4. HTTPSアクセス時に `/`, `/thanks.html`, `/privacy.html` が見えること
5. `app/`, `config/`, `storage/`, `database/` が直接参照不可であること
6. 正常送信・必須未入力・CSRF不正・honeypot・レート制限で期待挙動を確認
