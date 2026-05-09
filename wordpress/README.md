# WordPress 完全移行パッケージ

このディレクトリには、`livesta-theme`、`tpi-theme`、`haru-theme` の XServer 向け完全移行パッケージを格納します。

各サイトには次の内容を含めます。

- `upload-root/`: XServer で展開する WordPress ルート一式
- `upload-root/_migration/wordpress.sql`: 記事データを含む DB エクスポート
- `archives/*.zip`: XServer へアップロードする zip
- `source-local/`: 元のローカル compose / README
- パッケージディレクトリ名と zip 名は、各サイトの有効テーマスラッグに合わせます

注意点:

- `upload-root/xserver-db-config.php.example` を `xserver-db-config.php` にコピーして DB 情報を入力してください。
- 展開後、`_migration/import.php?token=...` を開いて SQL 取込と URL 置換を実行してください。
- 確認後は `_migration/` を削除してください。
