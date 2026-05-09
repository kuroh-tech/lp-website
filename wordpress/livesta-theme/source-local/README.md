# livesta-theme WordPress demo

## 起動

```bash
docker compose up -d
```

## 確認先

- フロント: `http://localhost:8101`
- 管理画面: `http://localhost:8101/wp-admin`
- ログイン: `admin / admin`

## 構成

- テーマ: `wp-content/themes/livesta-theme`
- 自動初期化: `wp-content/mu-plugins/livesta-site-bootstrap.php`

初回起動時に固定ページ、投稿ページ、サンプルのお知らせ、物件データを自動作成します。
