# DF CONNECT ホワイトラベル制作・運用保守LP コーディングデータ

添付イメージをベースに、Web制作会社向けのホワイトラベル制作・運用保守パートナーLPとして静的HTML/CSS/JSで実装したデータです。

## ファイル構成

```txt
dfconnect_white_label_lp/
├── index.html
├── css/
│   └── style.css
├── js/
│   └── main.js
├── assets/
│   ├── img/
│   │   ├── logo.svg
│   │   ├── favicon.svg
│   │   ├── hero-bg.svg
│   │   ├── hero-dashboard.svg
│   │   └── ogp.png
│   └── icons/
│       └── icon-*.svg
└── reference/
    └── design-reference.jpeg
```

## 実装内容

- レスポンシブ対応済みのLP
- ヘッダー固定、スマホ用ハンバーガーメニュー
- ファーストビュー、課題、解決策、対応領域、利用の流れ、導入事例、セキュリティ、FAQ、資料請求、問い合わせフォーム
- 画像・アイコンはSVG中心で同梱
- 外部ライブラリ不使用
- フォントファイル同梱なし（system font stack）

## 必ず差し替える箇所

1. `index.html` の電話番号 `03-1234-5678`
2. フォーム送信先  
   現状は静的デモのため、`js/main.js` で送信を止めてトースト表示しています。実運用時は以下いずれかに変更してください。
   - サーバーサイドの問い合わせ処理
   - Formspree / Google Apps Script / microCMS等のフォーム連携
   - WordPress化する場合はContact Form 7等
3. `og:image` のURL  
   本番公開時は絶対URLに変更してください。
4. 会社概要・プライバシーポリシー・特商法リンク
5. 資料ダウンロード導線  
   PDFなどを用意する場合は `#download` のCTAリンクをPDFまたはフォームに変更してください。

## 編集しやすい箇所

- 色：`css/style.css` の `:root` 内のCSS変数
- 最大幅：`--container`
- ヘッダー高さ：`--header-height`
- メイン文言：`index.html` の各section
- 画像：`assets/img/hero-dashboard.svg` や `assets/icons/` 配下

## ローカル確認方法

そのまま `index.html` をブラウザで開いて確認できます。ローカルサーバーで確認する場合は以下です。

```bash
cd dfconnect_white_label_lp
python3 -m http.server 8080
```

ブラウザで `http://localhost:8080` を開いてください。

## 補足

- `reference/design-reference.jpeg` は今回のイメージ画像を比較用に入れています。公開時は削除して問題ありません。
- SVGアイコンは自由に差し替え可能です。
- 画像はデプロイしやすいように軽量化を優先しています。
