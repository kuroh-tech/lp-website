# LIVESTA Theme セットアップ

## 1. テーマ配置
- このフォルダ `livesta-theme` を `wp-content/themes/` に配置
- 管理画面で「LIVESTA Theme」を有効化

## 2. 固定ページ作成（スラッグ）
- `about`（会社概要）
- `service`（事業内容）
- `contact`（お問い合わせ）
- `contact/thanks`（送信完了）
- `privacy`（プライバシーポリシー）
- `sitemap`（サイトマップ）

## 3. 投稿ページ設定
- 固定ページで `news` を作成
- `設定 > 表示設定` で「投稿ページ」に `news` を設定

## 4. 物件投稿
- カスタム投稿タイプ `物件情報` が追加されます
- 下記カスタムフィールド名を使うとデザインに反映されます
  - `price`, `layout`, `area_size`, `floor`, `built_date`, `station`, `badge`, `location`
  - 詳細用: `balcony_size`, `management_fee`, `repair_fund`, `structure`, `total_units`, `parking`, `right_type`, `handover_time`, `transaction_type`, `property_kind`, `address_full`, `traffic`, `description`, `gallery`

## 5. カテゴリ
- テーマ有効化時に以下を自動作成
  - `news`（お知らせ）
  - `property`（物件情報）
  - `media`（メディア）

## 6. 補足
- Contact Form 7 を使う場合は、`contact` ページ本文にショートコードを貼ると優先表示されます。
- 未登録データがある箇所はダミー表示で崩れないようにしています。
- このテーマに含まれる会社情報・住所・連絡先・物件情報は、デモ利用を想定した架空データです。
