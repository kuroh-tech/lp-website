# tpi-theme WordPress demo

## 起動

```bash
docker compose up -d
```

## 確認先

- フロント: `http://localhost:8102`
- 管理画面: `http://localhost:8102/wp-admin`
- ログイン: `admin / admin`

## 構成

- テーマ: `wp-content/themes/tpi-theme`
- 自動初期化: `wp-content/mu-plugins/tpi-site-bootstrap.php`
- 画像プロンプト: `image-prompts.md`
- 生成画像の配置先: `wp-content/themes/tpi-theme/assets/images/`

初回起動時に固定ページを自動作成し、トップページを `home` に設定します。
