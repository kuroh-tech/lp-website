# haru-theme WordPress demo

## 起動

```bash
docker compose up -d
```

## 確認先

- フロント: `http://localhost:8103`
- 管理画面: `http://localhost:8103/wp-admin`
- ログイン: `admin / admin`

## 構成

- テーマ: `wp-content/themes/haru-theme`
- 自動初期化: `wp-content/mu-plugins/haru-site-bootstrap.php`

初回起動時に固定ページ、投稿ページ、カテゴリ、お知らせ投稿を自動作成します。
