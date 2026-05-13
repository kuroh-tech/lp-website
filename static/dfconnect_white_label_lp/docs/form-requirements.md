# DFConnect LP フォーム要件定義書

## 0. 概要

既存LPのHTMLフォーム（`#contact` セクション内の `<form class="contact-form">` ）をそのまま入り口として使用し、裏側でXServer上の自前PHPフォームに紐づける。

外部フォームサービス（Googleフォーム等）は使用しない。

---

## 1. 基本方針

| 項目 | 内容 |
|------|------|
| LP本体 | 静的 HTML / CSS / JavaScript（既存） |
| フォーム処理 | PHP 8系 |
| メール送信 | PHPMailer + XServer SMTP |
| データ保存 | XServer MySQL |
| スパム対策 | honeypot + CSRF + IPレート制限 |
| 成功後遷移 | `/thanks.html` |

---

## 2. 既存フォームとの紐づけ方針

### 2.1 変更箇所

既存HTMLフォームの `action` 属性のみ変更する。

```html
<!-- 現状 -->
<form class="contact-form reveal js-contact-form" action="#" method="post">

<!-- 本番 -->
<form class="contact-form reveal js-contact-form" action="/api/contact.php" method="post">
```

### 2.2 追加するhidden項目

既存フォームに以下のhidden項目を追加する。

```html
<!-- CSRF対策 -->
<!-- ※ PHPでトークンを生成しフォームに埋め込む。静的HTML運用の場合はJS経由でAPI取得する設計も可 -->

<!-- honeypot（bot対策） -->
<input type="text" name="website" tabindex="-1" autocomplete="off" class="hp-field">
```

honeypot用CSS:

```css
.hp-field {
  position: absolute;
  left: -9999px;
  opacity: 0;
  height: 0;
}
```

### 2.3 既存フォームのJS処理

既存 `js/main.js` のフォーム送信イベント（`contactForm.addEventListener('submit', ...)` ）は、本番移行時に以下のいずれかで対応する。

- **方式A（推奨）**: JSのpreventDefaultを外し、通常のPOST送信にする
- **方式B**: fetch APIでPHPエンドポイントに非同期送信し、成功時にJS側でthanks.htmlへ遷移する

---

## 3. フォーム項目一覧

### 3.1 必須項目

| name | ラベル | 型 | バリデーション |
|------|--------|-----|---------------|
| company | 会社名 | text | 空でない、255文字以内 |
| name | お名前 | text | 空でない、100文字以内 |
| email | メールアドレス | email | 空でない、メール形式、255文字以内 |
| inquiry_type | 相談内容 | select | 空でない、100文字以内 |
| message | メッセージ | textarea | 空でない、5000文字以内 |
| privacy | プライバシーポリシー同意 | checkbox | 同意済み |

### 3.2 任意項目

| name | ラベル | 型 | バリデーション |
|------|--------|-----|---------------|
| company_type | 会社区分 | select | 100文字以内 |
| response_style | 希望の対応スタイル | select | 100文字以内 |
| desired_timing | 希望時期 | select | 100文字以内 |
| budget_range | 予算感 | select | 100文字以内 |
| nda | NDA希望 | select | 50文字以内 |
| contact_method | 希望の連絡方法 | select | 100文字以内 |

### 3.3 hidden / bot対策項目

| name | 用途 |
|------|------|
| website | honeypot。画面非表示。値があればbot判定 |
| csrf_token | CSRF対策用トークン |

---

## 4. フォーム選択肢

### 4.1 会社区分（company_type）

- Web制作会社
- 広告代理店
- デザイン会社
- フリーランス
- その他

### 4.2 相談内容（inquiry_type）

- デザイン支給コーディング
- WordPress制作・改修
- LP / 下層ページ制作
- 運用保守・軽微修正
- フォーム・CTA改善
- 公開前チェック
- 提案前の競合調査・資料作成補助
- NDA / ホワイトラベル相談
- デモサイトを見たい
- その他

### 4.3 希望の対応スタイル（response_style）

- 完全裏方で依頼したい
- 必要時にMTG同席してほしい
- 要件整理から相談したい
- まずは小さな修正を相談したい
- まだ決まっていない

### 4.4 希望時期（desired_timing）

- すぐ
- 1週間以内
- 1ヶ月以内
- 3ヶ月以内
- 未定

### 4.5 予算感（budget_range）

- 未定
- 〜3万円
- 3〜10万円
- 10〜30万円
- 30万円以上
- 継続相談

### 4.6 NDA希望（nda）

- 希望する
- 必要に応じて
- 現時点では不要

### 4.7 希望の連絡方法（contact_method）

- メール
- オンラインMTG
- どちらでも

---

## 5. ファイル構成

```
/public_html/
  index.html          ← 既存LP（action属性のみ変更）
  thanks.html          ← 送信成功後の遷移先
  privacy.html         ← プライバシーポリシー
  api/
    contact.php        ← フォーム処理エンドポイント
  assets/              ← 既存アセット（変更なし）
    css/
    js/
    icons/
    images/
    img/

/config/               ← public_html外に配置（推奨）
  mail_config.php      ← SMTP設定
  db_config.php        ← MySQL接続設定

/logs/                 ← public_html外に配置（推奨）
  contact_error.log    ← エラーログ

/vendor/
  phpmailer/           ← PHPMailerライブラリ
```

※ `/config/` と `/logs/` がpublic_html外に置けない場合は、`.htaccess` で直接アクセスを拒否する。

---

## 6. メール送信要件

### 6.1 送信元設定

```
From: DFConnect <no-reply@dfconnect.jp>
To: 管理者メールアドレス
Reply-To: フォーム入力者のメールアドレス
```

送信元を問い合わせ者のメールアドレスにしない（到達率・なりすまし対策）。

### 6.2 管理者通知メール

件名: `【DFConnect】お問い合わせがありました`

本文に含める項目:
- 会社名
- お名前
- メールアドレス
- 会社区分
- 相談内容
- 希望の対応スタイル
- 希望時期
- 予算感
- NDA希望
- 希望の連絡方法
- メッセージ
- IPアドレス
- 送信日時

### 6.3 自動返信メール

件名: `【DFConnect】お問い合わせありがとうございます`

```
{name} 様

この度はDFConnectへお問い合わせいただきありがとうございます。
内容を確認のうえ、通常1〜2営業日以内にご返信いたします。

なお、クライアント名・案件詳細などの機密情報は、NDA締結後の共有でも問題ありません。
まずは概要ベースで確認させていただきます。

送信内容：
--------------------
会社名：{company}
相談内容：{inquiry_type}
希望時期：{desired_timing}
メッセージ：
{message}
--------------------

DFConnect
```

※ 初期は管理者通知のみでも可。自動返信は段階的に追加。

---

## 7. DB保存要件

### 7.1 テーブル名

`inquiries`

### 7.2 CREATE TABLE

```sql
CREATE TABLE inquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  company_type VARCHAR(100),
  inquiry_type VARCHAR(100) NOT NULL,
  response_style VARCHAR(100),
  desired_timing VARCHAR(100),
  budget_range VARCHAR(100),
  nda VARCHAR(50),
  contact_method VARCHAR(100),
  message TEXT NOT NULL,
  ip_address VARCHAR(45),
  user_agent TEXT,
  status VARCHAR(50) DEFAULT 'new',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7.3 statusの想定値

| 値 | 意味 |
|----|------|
| new | 新規 |
| replied | 返信済み |
| meeting | 商談化 |
| estimated | 見積提出 |
| won | 受注 |
| lost | 失注 |
| ng | 対象外・送信拒否等 |

初期は管理画面不要。phpMyAdminまたは今後の営業管理ツールで確認する。

---

## 8. バリデーション要件

### 8.1 サーバー側必須チェック

- company: 空でない
- name: 空でない
- email: 空でない + メール形式
- inquiry_type: 空でない
- message: 空でない
- privacy: 同意済み
- csrf_token: 正しい

### 8.2 エラー時の扱い

- 不正入力時はメール送信しない
- DB保存もしない
- エラーメッセージを表示する
- 可能であれば入力内容を保持して再表示する

---

## 9. セキュリティ要件

### 9.1 必須対応

- POST以外のリクエスト拒否
- CSRFトークン検証
- honeypot項目によるbot対策
- 同一IPの短時間連投制限（5分以内に3回以上で拒否）
- 同一メールアドレスの短時間連投制限（5分以内に3回以上で拒否）
- メールアドレス形式チェック
- ヘッダーインジェクション対策
- HTML出力時のエスケープ
- DB保存はプリペアドステートメント使用
- SMTP/DBパスワードは公開しない
- エラー詳細を画面に出さない（ログに記録）

### 9.2 honeypot仕様

HTML側に `name="website"` のテキスト入力を設置し、CSSで非表示にする。
PHP側で値が入っていればbot判定。ただしbotに悟らせないため、成功時と同じ `/thanks.html` にリダイレクトする。

### 9.3 レート制限

DBの `inquiries` テーブルの `ip_address`、`email`、`created_at` を使って判定する。

---

## 10. 画面遷移要件

### 10.1 成功時

`/thanks.html` にリダイレクト。

### 10.2 thanks.html 文言

```
お問い合わせありがとうございます

内容を確認のうえ、通常1〜2営業日以内にご返信いたします。
案件詳細やクライアント名などの機密情報は、NDA締結後の共有でも問題ありません。

トップへ戻る
```

### 10.3 失敗時

- 入力不備: フォーム上にエラーメッセージを表示
- メール送信失敗: 「送信に失敗しました。時間をおいて再度お試しください。」と表示
- DB保存失敗: ログに記録、画面には詳細を出さない

---

## 11. privacy.html 要件

### 11.1 記載項目

- 取得する情報
- 利用目的
- 第三者提供について
- 外部サービスの利用について
- 安全管理
- 開示・訂正・削除依頼
- お問い合わせ窓口
- 改定について

### 11.2 簡易文面

```
DFConnectでは、お問い合わせフォームを通じて取得した会社名、お名前、メールアドレス、
相談内容等の情報を、お問い合わせ対応、見積作成、業務連絡のために利用します。
取得した情報は、法令に基づく場合を除き、ご本人の同意なく第三者へ提供しません。
```

---

## 12. 実装優先順位

```
1. action属性の変更 + honeypot/CSRFのhidden追加
2. /api/contact.php の作成（バリデーション + メール送信 + DB保存）
3. /config/mail_config.php、/config/db_config.php の作成
4. /thanks.html の作成
5. /privacy.html の作成
6. schema.sql の作成
7. 送信テスト・スマホ表示確認
```

---

## 13. 動作確認チェックリスト

- [ ] 必須項目未入力で送信できない
- [ ] メール形式が不正な場合に送信できない
- [ ] privacy未同意で送信できない
- [ ] 正常送信で管理者メールが届く
- [ ] Reply-Toが問い合わせ者メールになる
- [ ] DBに問い合わせ内容が保存される
- [ ] thanks.htmlに遷移する
- [ ] honeypotに値が入った場合、実送信されない
- [ ] 同一IP連投時に制限される
- [ ] スマホでフォーム表示が崩れない
- [ ] 「クライアント非接触」の文言がLP内に残っていない
