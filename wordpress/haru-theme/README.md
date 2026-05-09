# haru-theme 移行パッケージ

- テーマ識別: haru-theme
- 元ソース: demo_sites/site_03
- サイト識別: haru
- 取得元 URL: http://localhost:8103
- 取得元: 稼働中の Docker コンテナと DB

内容:

- `upload-root/`: XServer で展開する WordPress ルート一式
- `upload-root/_migration/wordpress.sql`: 記事・固定ページ・設定を含む DB ダンプ
- `archives/haru-theme-upload-root.zip`: XServer へアップロードする zip
- `source-local/`: 元の `docker-compose.yml` と README

手順:

1. `archives/haru-theme-upload-root.zip` を XServer の対象ディレクトリへアップロードして展開
2. 展開後に `xserver-db-config.php.example` を `xserver-db-config.php` にコピーして DB 情報を入力
3. `/_migration/import.php?token=66e61c03c76bf43b58c2ee40eb673bce` を開いて SQL 取込と URL 置換を実行
4. 確認後に `_migration/` を削除
