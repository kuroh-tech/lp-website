# DFConnect LP 作業引き継ぎメモ

最終更新: 2026-05-13 JST

## 最初に読むこと

- 対象LP: `/Users/satou/Desktop/webscript/lp-website/static/dfconnect_white_label_lp`
- 本番ドメイン: `https://dfconnect.jp/`
- 本番サーバーのLP配置先: `/home/xs521344/dfconnect.jp/public_html`
- SSH alias: `xserver-dfconnect`
- 下層WordPressは絶対に書き換え・削除しない。
  - `https://dfconnect.jp/haru/`
  - `https://dfconnect.jp/tpi/`
  - `https://dfconnect.jp/livesta/`
- deploy時は `rsync --delete` をディレクトリ全体に対して使わない。LP直下にはWordPress下層も同居しているため、必要ファイルだけ個別に送る。
- 本番設定ファイル `config/app.php`, `config/db.php`, `config/mail.php` は秘密情報を含むため、ローカルのexampleで上書きしない。

## ユーザー要望

- LPの屋号表記は `DF CONNECT` ではなく `DFConnect` に統一。
- プライバシーポリシーは別ページ化。
- フォーム内のプライバシーポリシー同意文は横幅・縦長崩れを直し、`プライバシーポリシー` のみリンク。
- `任せられること` にライティング/文章作成系を追加。
- `対応できる範囲を、事前に整理します。` のカードは3分割前提ではなくまず2つ。
- フッターの屋号や事業者情報などノイズになる部分は消す。
- `index.html` の内容を `index.php` に反映して、`dfconnect.jp` にデプロイ。
- フォーム周りが問題ないか本番でテスト。

## 反映済み内容

- `index.html` を作成済み。
- `index.html` の表示内容を `index.php` に反映済み。
- `privacy.html` を作成し、フォーム内のリンク先を `privacy.html` に変更済み。
- `thanks.html` は `DFConnect` 表記に更新済み。
- CSSでプライバシーポリシー同意行を調整済み。
  - `.checkbox-label` に `flex-wrap: wrap;`
  - `.checkbox-label .form-error` を追加し、エラー表示が同意文の下に回るよう調整。
- バリデーション表示バグを修正済み。
  - 対象: `static/dfconnect_white_label_lp/app/Validator.php`
  - 原因: `foreach ($fieldErrors as $message)` が入力済み本文用の `$message` を上書きし、未同意エラーがtextarea内に入ることがあった。
  - 修正: ループ変数を `$fieldErrorMessage` に変更。
- メール関連の屋号表記も `DFConnect` に統一済み。
  - `app/MailService.php`
  - `app/bootstrap.php`
  - `config/app.example.php`
  - `config/mail.example.php`
  - 本番の `config/app.php`, `config/mail.php` も屋号関連4項目だけ置換済み。
- README/docs/composer/index.html.bak 内の `DF CONNECT` 表記も機械的に `DFConnect` へ統一済み。
- 2026-05-13 追加対応:
  - 削除済みの旧 `assets/img` 配下を参照していたアクティブファイルを修正。
  - `index.php`, `index.html` の `og:image` は `assets/images/顔写真.jpg` を参照。
  - `index.php`, `index.html`, `privacy.html`, `thanks.html` のfaviconは `assets/icons/icon-wl.svg` を参照。
  - `css/style.css` のhero背景から旧 `assets/img/hero-bg.svg` 参照を削除。

## 本番へ反映済みの主なファイル

- `/home/xs521344/dfconnect.jp/public_html/index.php`
- `/home/xs521344/dfconnect.jp/public_html/privacy.html`
- `/home/xs521344/dfconnect.jp/public_html/thanks.html`
- `/home/xs521344/dfconnect.jp/public_html/css/style.css`
- `/home/xs521344/dfconnect.jp/public_html/app/Validator.php`
- `/home/xs521344/dfconnect.jp/public_html/app/MailService.php`
- `/home/xs521344/dfconnect.jp/public_html/app/bootstrap.php`
- `/home/xs521344/dfconnect.jp/public_html/assets/images/顔写真.jpg`
- `/home/xs521344/dfconnect.jp/public_html/assets/icons/icon-wl.svg`

## 本番設定で確認済みの値

2026-05-13時点で以下を確認済み。

```json
{
  "app.admin_name": "DFConnect",
  "app.from_name": "DFConnect",
  "mail.admin_subject": "【DFConnect】お問い合わせがありました",
  "mail.auto_reply_subject": "【DFConnect】お問い合わせありがとうございます"
}
```

## フォーム本番テスト結果

本番 `https://dfconnect.jp/` のフォームで実施済み。

- `GET https://dfconnect.jp/api/contact.php`
  - 結果: `405`
  - GET送信は許可されないため想定通り。
- CSRF不正テスト
  - 無効な `csrf_token` でPOST。
  - 結果: `200 https://dfconnect.jp/index.php#contact`
  - エラーメッセージ表示あり。
- 必須項目/形式バリデーション
  - メール形式不正、会社名/名前/問い合わせ種別/同意など不足でPOST。
  - 結果: `200 https://dfconnect.jp/index.php#contact`
  - 各フィールドエラー表示あり。
  - textareaの値保持バグは修正後に再テスト済み。
- 正常送信
  - テストメール: `codex-form-success-retest@example.com`
  - 結果: `200 https://dfconnect.jp/thanks.html`
  - DBの `inquiries` に保存されることを確認。
  - `admin_mail_status = sent`
  - `auto_reply_status = sent`
  - これはサーバー側で送信処理が成功した状態の確認であり、実際の受信箱到達までは未確認。
- ハニーポット
  - `website` に値を入れてPOST。
  - 結果: `200 https://dfconnect.jp/thanks.html`
  - `inquiries` には保存されない。
  - `inquiry_attempts` に `result = honeypot` として記録される。

## テストデータ削除状況

今回作成したテストデータは削除済み。

最終確認結果:

```json
{
  "counts": {
    "inquiries": 0,
    "attempts": 2
  },
  "test_inquiries": 0,
  "test_attempts": 0
}
```

残っている `inquiry_attempts` 2件は、今回の最終テストより前から存在していた古いハニーポットログ。

## 最終URL確認

2026-05-13時点の最終確認。

```text
200 https://dfconnect.jp/
200 https://dfconnect.jp/privacy.html
200 https://dfconnect.jp/thanks.html
200 https://dfconnect.jp/css/style.css
200 https://dfconnect.jp/assets/icons/icon-wl.svg
200 https://dfconnect.jp/assets/images/%E9%A1%94%E5%86%99%E7%9C%9F.jpg
405 https://dfconnect.jp/api/contact.php
200 https://dfconnect.jp/haru/
200 https://dfconnect.jp/tpi/
200 https://dfconnect.jp/livesta/
```

## 次に作業する人向けの開始手順

1. まず状態確認。

```sh
cd /Users/satou/Desktop/webscript/lp-website
git status --short
```

2. LP側の差分を確認。

```sh
git diff -- static/dfconnect_white_label_lp
```

3. WordPress下層は触らない。ローカルworktree上では以下のようなWordPress関連差分が見えている場合があるが、このLP作業では本番へ反映していない。

```text
wordpress/haru-theme/upload-root/.htaccess
wordpress/livesta-theme/upload-root/.htaccess
wordpress/tpi-theme/upload-root/.htaccess
```

4. PHP構文確認。

```sh
php -l static/dfconnect_white_label_lp/app/Validator.php
php -l static/dfconnect_white_label_lp/app/MailService.php
php -l static/dfconnect_white_label_lp/app/bootstrap.php
```

5. 本番へ送る場合は、必要ファイルだけ個別に送る。

```sh
rsync -av -e ssh static/dfconnect_white_label_lp/index.php xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/
rsync -av -e ssh static/dfconnect_white_label_lp/privacy.html xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/
rsync -av -e ssh static/dfconnect_white_label_lp/thanks.html xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/
rsync -av -e ssh static/dfconnect_white_label_lp/css/style.css xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/css/
rsync -av -e ssh static/dfconnect_white_label_lp/app/Validator.php xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/app/
rsync -av -e ssh static/dfconnect_white_label_lp/app/MailService.php xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/app/
rsync -av -e ssh static/dfconnect_white_label_lp/app/bootstrap.php xserver-dfconnect:/home/xs521344/dfconnect.jp/public_html/app/
```

6. 本番設定ファイルは丸ごと上書きしない。屋号など一部だけ変える必要がある場合は、サーバー上の `config/app.php`, `config/mail.php` の該当キーだけ慎重に変更する。

## 注意点

- `config/db.php`, `config/mail.php` の秘密情報はローカルに置かない。
- `api/contact.php` のGETが405なのは正常。
- 正常送信テストをすると実際にメール送信処理が走る。テスト後はDBからテストレコードを削除する。
- メールの `sent` は送信処理成功の確認で、受信箱到達確認ではない。
- 下層WordPressの表示確認はURL応答確認だけに留める。ファイル反映や削除はしない。
