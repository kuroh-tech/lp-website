<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$csrfToken = Csrf::token();
$errors = flash_get('errors', []);
$fieldErrors = flash_get('field_errors', []);
$systemError = flash_get('system_error', '');
$old = flash_get('old', []);
if (!is_array($old)) {
  $old = [];
}
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Web制作会社・広告代理店向けの外部制作パートナー｜DF CONNECT</title>
  <meta name="description" content="DF CONNECTは、個人事業として運営しているWeb制作会社様向けの外部制作パートナーです。デザイン支給後のコーディング、WordPress改修、公開前チェック、公開後修正まで、必要な範囲だけ対応します。">
  <meta property="og:title" content="Web制作会社・広告代理店向けの外部制作パートナー｜DF CONNECT">
  <meta property="og:description" content="案件概要だけで相談可能。NDA前はクライアント名・URLを伏せてOK。匿名の協力実績と品質確認用デモサイトを掲載。">
  <meta property="og:type" content="website">
  <meta property="og:image" content="assets/img/ogp.png">
  <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <a class="skip-link" href="#main">本文へスキップ</a>

  <header class="site-header" data-header>
    <div class="container header-inner">
      <a class="brand" href="#top" aria-label="DF CONNECT トップへ">
        <span class="brand-main">DF CONNECT</span>
        <span class="brand-sub">個人事業の制作パートナー</span>
      </a>
      <div class="header-actions">
        <a class="button button-primary button-small" href="#contact">概要だけ送る</a>
        <button class="menu-button js-menu-button" type="button" aria-controls="site-nav" aria-expanded="false">
          <span></span><span></span><span></span>
          <span class="sr-only">メニューを開く</span>
        </button>
      </div>
      <nav id="site-nav" class="site-nav" aria-label="グローバルナビゲーション">
        <a href="#what">対応内容</a>
        <a href="#works">匿名実績</a>
        <a href="#demo">デモ</a>
        <a href="#how">進め方</a>
        <a href="#faq">FAQ</a>
        <a class="button button-primary button-small" href="#contact">相談する</a>
      </nav>
    </div>
  </header>

  <main id="main">
    <section id="top" class="hero section">
      <div class="container hero-grid">
        <div class="hero-copy reveal">
          <p class="hero-kicker">Web制作会社・広告代理店向け</p>
          <h1>制作会社の手が足りない時に、<br>実装・改修だけ相談できる外部パートナー。</h1>
          <p class="hero-lead">
            DF CONNECTは、個人事業として運営しているWeb制作パートナーです。
            Figma支給からのHTML/CSSコーディングを中心に、WordPress改修、公開前チェック、公開後の軽微修正まで、制作会社様の進行ルールに合わせて必要な範囲だけ対応します。
          </p>
          <p class="hero-lead">
            NDA前は、クライアント名・URL・詳細情報を伏せた概要だけで問題ありません。
            対応可否と進め方を整理して返信します。
          </p>
          <div class="hero-chips" aria-label="DF CONNECTの特徴">
            <span class="chip">窓口と実装者が同一人物</span>
            <span class="chip">貴社名義の進行に配慮</span>
            <span class="chip">NDA前の匿名相談OK</span>
            <span class="chip">小さな修正から相談OK</span>
          </div>

          <div class="hero-cta-wrap">
            <a class="button button-primary hero-cta-sp" href="#contact">案件概要だけ送って相談する</a>
            <a class="button button-ghost" href="#demo">デモサイトで品質を見る</a>
          </div>
        </div>

        <div class="hero-form-wrapper reveal">
          <form id="contact" class="contact-form js-contact-form" action="api/contact.php" method="post" novalidate>
            <p class="form-title">案件概要だけで相談できます</p>
            <p class="form-subtitle">
              正式な仕様書がなくても大丈夫です。<br>
              「Figmaあり」「WordPressの固定ページ追加」「公開前チェックだけ」など、分かる範囲でお送りください。<br>
              クライアント名やURLは、NDA前であれば伏せたままで問題ありません。
            </p>

            <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
            <div class="hp-wrap" aria-hidden="true">
              <label for="website">Website</label>
              <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <?php if (!empty($systemError)): ?>
              <div class="form-error-summary" role="alert" aria-live="assertive">
                <ul><li><?= h($systemError) ?></li></ul>
              </div>
            <?php elseif (!empty($errors)): ?>
              <div class="form-error-summary" role="alert" aria-live="assertive">
                <p>入力内容をご確認ください。</p>
                <ul>
                  <?php foreach ($errors as $error): ?>
                    <li><?= h($error) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <label for="company">会社名・屋号 <span class="required">必須</span>
              <input
                id="company"
                type="text"
                name="company"
                value="<?= h($old['company'] ?? '') ?>"
                placeholder="会社名・屋号"
                maxlength="255"
                autocomplete="organization"
                required
                aria-required="true"
                aria-invalid="<?= isset($fieldErrors['company']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($fieldErrors['company']) ? 'err-company' : '' ?>">
              <?php if (!empty($fieldErrors['company'])): ?>
                <p class="form-error" id="err-company"><?= h($fieldErrors['company']) ?></p>
              <?php endif; ?>
            </label>

            <label for="name">お名前 <span class="required">必須</span>
              <input
                id="name"
                type="text"
                name="name"
                value="<?= h($old['name'] ?? '') ?>"
                placeholder="山田 太郎"
                maxlength="100"
                autocomplete="name"
                required
                aria-required="true"
                aria-invalid="<?= isset($fieldErrors['name']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($fieldErrors['name']) ? 'err-name' : '' ?>">
              <?php if (!empty($fieldErrors['name'])): ?>
                <p class="form-error" id="err-name"><?= h($fieldErrors['name']) ?></p>
              <?php endif; ?>
            </label>

            <label for="email">メールアドレス <span class="required">必須</span>
              <input
                id="email"
                type="email"
                name="email"
                value="<?= h($old['email'] ?? '') ?>"
                placeholder="sample@example.com"
                maxlength="255"
                autocomplete="email"
                required
                aria-required="true"
                aria-invalid="<?= isset($fieldErrors['email']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($fieldErrors['email']) ? 'err-email' : '' ?>">
              <?php if (!empty($fieldErrors['email'])): ?>
                <p class="form-error" id="err-email"><?= h($fieldErrors['email']) ?></p>
              <?php endif; ?>
            </label>

            <label for="inquiry_type">ご相談内容 <span class="required">必須</span>
              <select
                id="inquiry_type"
                name="inquiry_type"
                required
                aria-required="true"
                aria-invalid="<?= isset($fieldErrors['inquiry_type']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($fieldErrors['inquiry_type']) ? 'err-inquiry' : '' ?>">
                <option value="">選択してください</option>
                <option value="coding_lp"<?= is_selected($old['inquiry_type'] ?? '', 'coding_lp') ?>>コーディング・LP実装</option>
                <option value="wordpress"<?= is_selected($old['inquiry_type'] ?? '', 'wordpress') ?>>WordPress制作・改修</option>
                <option value="maintenance"<?= is_selected($old['inquiry_type'] ?? '', 'maintenance') ?>>運用保守・軽微修正</option>
                <option value="form_check"<?= is_selected($old['inquiry_type'] ?? '', 'form_check') ?>>公開前チェック・フォーム確認</option>
                <option value="white_label_nda"<?= is_selected($old['inquiry_type'] ?? '', 'white_label_nda') ?>>NDA・貴社名義での進行相談</option>
                <option value="undecided"<?= is_selected($old['inquiry_type'] ?? '', 'undecided') ?>>まだ決まっていない</option>
              </select>
              <?php if (!empty($fieldErrors['inquiry_type'])): ?>
                <p class="form-error" id="err-inquiry"><?= h($fieldErrors['inquiry_type']) ?></p>
              <?php endif; ?>
            </label>

            <label for="message">案件概要・メッセージ <span class="optional">任意</span>
              <textarea
                id="message"
                name="message"
                rows="4"
                placeholder="例：デザイン支給のLPコーディングを相談したいです。&#10;NDA前のため、クライアント名やURLは伏せています。"
                maxlength="5000"
                aria-invalid="<?= isset($fieldErrors['message']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($fieldErrors['message']) ? 'err-message' : '' ?>"><?= h($old['message'] ?? '') ?></textarea>
              <?php if (!empty($fieldErrors['message'])): ?>
                <p class="form-error" id="err-message"><?= h($fieldErrors['message']) ?></p>
              <?php endif; ?>
            </label>

            <label class="checkbox-label">
              <input
                type="checkbox"
                name="privacy"
                value="1"
                <?= !empty($old['privacy']) ? 'checked' : '' ?>
                required
                aria-required="true"
                aria-invalid="<?= isset($fieldErrors['privacy']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($fieldErrors['privacy']) ? 'err-privacy' : '' ?>">
              <a href="#privacy">プライバシーポリシー</a>に同意のうえ送信します
              <?php if (!empty($fieldErrors['privacy'])): ?>
                <p class="form-error" id="err-privacy"><?= h($fieldErrors['privacy']) ?></p>
              <?php endif; ?>
            </label>

            <p class="form-note">
              送信前にクライアント名やURLを出す必要はありません。<br>
              内容を確認し、対応可否・確認したい点・概算の進め方を返信します。
            </p>

            <button class="button button-primary button-submit" type="submit">案件概要だけ送って相談する</button>
          </form>
        </div>
      </div>
    </section>

    <section id="why" class="section section--white" aria-labelledby="why-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="why-title">制作会社の現場で、こんな作業が詰まりやすいですか？</h2>
          <p>新規制作だけでなく、下層ページ制作、既存サイト改修、公開後更新まで。必要な範囲だけ外部化する前提で進めています。</p>
        </div>
        <div class="why-grid">
          <article class="why-card reveal">
            <span class="why-icon">1</span>
            <p>デザインは上がっているのに、コーディングに着手できず公開が延びている</p>
          </article>
          <article class="why-card reveal">
            <span class="why-icon">2</span>
            <p>公開前のリンクチェック・フォーム確認・OGP設定が積み残しになっている</p>
          </article>
          <article class="why-card reveal">
            <span class="why-icon">3</span>
            <p>外注したいが、クライアント情報の共有範囲や進行ルールの擦り合わせが手間になる</p>
          </article>
        </div>
        <p class="why-conclusion reveal">DF CONNECTは、制作会社様の現場で必要な範囲の制作実務を一部受ける外部パートナーです。</p>
      </div>
    </section>

    <section id="about" class="section section--pale" aria-labelledby="about-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="about-title">個人事業の外部制作パートナーとして、代表者本人が窓口になります。</h2>
          <p>
            DF CONNECTは、Web制作会社様・広告代理店様の外部制作パートナーとして活動している個人事業です。大人数の制作会社ではありません。<br>
            その分、相談内容の把握、対応可否判断、制作時の確認、納品前のチェックまで、連絡経路をシンプルに進められます。<br>
            大量案件を同時に受ける体制ではないため、対応範囲・納期・連絡ルールを事前に整理したうえで、無理のない形で対応します。
          </p>
        </div>
        <div class="about-grid">
          <article class="about-card reveal">
            <h3>代表者本人が相談窓口</h3>
            <p>営業担当と制作者が分かれていないため、相談内容のズレを減らしやすい体制です。</p>
          </article>
          <article class="about-card reveal">
            <h3>対応可否を先に整理</h3>
            <p>短納期や仕様が固まりきっていない案件でも、対応できる範囲と確認が必要な点を先に整理します。</p>
          </article>
          <article class="about-card reveal">
            <h3>貴社の進行ルールに合わせる</h3>
            <p>メール、チャット、タスク管理、ファイル共有など、発注元企業様の既存フローに合わせて進めます。</p>
          </article>
          <article class="about-card reveal">
            <h3>守秘前提で相談可能</h3>
            <p>NDA締結前は、クライアント名やURLを伏せた概要のみで問題ありません。</p>
          </article>
        </div>
      </div>
    </section>

    <section id="what" class="section section--white" aria-labelledby="what-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="what-title">任せられること</h2>
          <p>新規制作をすべて丸ごと請けるというより、制作会社様が外部化しやすい実装・改修・確認作業を中心に対応します。</p>
        </div>
        <div class="what-grid">
          <article class="what-card what-card--primary reveal">
            <h3>コーディング・LP実装</h3>
            <p>Figma / XD / 画像カンプからのHTML/CSS実装、レスポンシブ対応。LPや下層ページ、既存サイトへのページ追加など、必要な範囲だけご相談いただけます。</p>
            <ul class="what-detail">
              <li>セマンティックHTML（section / article / nav / details 等）</li>
              <li>CSSカスタムプロパティ設計、CSS Grid / Flexbox</li>
              <li>clamp() / min() によるフルイドレスポンシブ</li>
              <li>Vanilla JavaScript（IntersectionObserver、ARIA制御、メニュー・アコーディオン等）</li>
              <li>WAI-ARIA、キーボード操作、prefers-reduced-motion対応</li>
            </ul>
          </article>
          <article class="what-card reveal">
            <h3>WordPress制作・改修</h3>
            <p>オリジナルテーマのフルスクラッチ開発、固定ページテンプレート作成、カスタム投稿タイプ・タクソノミー設計、既存テーマ修正、フォーム調整に対応します。</p>
          </article>
          <article class="what-card reveal">
            <h3>運用保守・軽微修正</h3>
            <p>テキスト修正、画像差し替え、表示崩れ対応、WordPress更新補助など、公開後に発生する細かな作業に対応します。</p>
          </article>
          <article class="what-card reveal">
            <h3>公開前チェック・フォーム確認</h3>
            <p>表示崩れ、リンク切れ、フォーム送信、OGP / meta確認など、納品前・公開前の確認作業を支援します。</p>
          </article>
        </div>
      </div>
    </section>

    <section id="env" class="section section--pale" aria-labelledby="env-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="env-title">作業環境・納品形式</h2>
          <p>貴社の既存フローに合わせて作業を進められるよう、対応可能な環境を整理しています。</p>
        </div>
        <div class="env-grid">
          <article class="env-card reveal">
            <h3>デザイン受け取り</h3>
            <p>Figma / Adobe XD / Photoshop / 画像カンプ（PDF・PNG）</p>
          </article>
          <article class="env-card reveal">
            <h3>納品形式</h3>
            <p>HTML/CSSファイル一式 / WordPress直接構築 / Git経由 / zip納品 / FTP</p>
          </article>
          <article class="env-card reveal">
            <h3>バージョン管理</h3>
            <p>Git対応（GitHub / GitLab / Backlog Git）</p>
          </article>
          <!-- HUMAN_REVIEW: 連絡ツール — 実際に対応可能なものだけ残す。docs/human_review_before_publish.md 参照 -->
          <article class="env-card reveal">
            <h3>連絡ツール</h3>
            <p>Slack / Chatwork / Discord / メール</p>
          </article>
          <article class="env-card reveal">
            <h3>開発環境</h3>
            <p>Docker（ローカル環境構築対応）/ テストサーバー確認 / XServer等の共用サーバー対応</p>
          </article>
        </div>
      </div>
    </section>

    <section id="works" class="section section--pale" aria-labelledby="works-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="works-title">公開できる範囲の協力実績を掲載しています。</h2>
          <p>
            制作会社様との協力案件では、守秘義務や掲載ルールの都合上、社名・URL・画面キャプチャを掲載できない場合があります。<br>
            ここでは、公開できる範囲で、担当内容が伝わる形に整理しています。実際の見た目や導線は、下部のデモサイトでご確認ください。
          </p>
        </div>
        <div class="works-grid">
          <article class="work-card reveal">
            <h3>協力実績：企業サイトのCMS移設・レスポンシブ対応</h3>
            <p class="work-labels">企業サイト / CMS対応 / レスポンシブ / 2024年公開</p>
            <p>制作会社様の案件にて、既存企業サイトの移設・構築に協力しました。配色調整、スマートフォン・タブレット対応、CMS対応など、既存サイトの印象を大きく崩さない再構築を担当しています。</p>
            <!-- HUMAN_REVIEW: ページ数・工期を出せるなら追記。docs/human_review_before_publish.md 参照 -->
            <p class="work-scope">担当：HTML/CSSコーディング → カスタム投稿タイプ・固定ページテンプレートによるWordPress化 → レスポンシブ対応 → 表示確認・修正対応</p>
            <p class="work-note">守秘義務の都合上、社名・URLは掲載していません。</p>
          </article>
          <article class="work-card reveal">
            <h3>協力実績：店舗サイトのHTML再構築・CMS対応</h3>
            <p class="work-labels">店舗サイト / HTML再構築 / CMS対応 / レスポンシブ / 2024年公開</p>
            <p>制作会社様の案件にて、既存店舗サイトのHTML再構築に協力しました。既存デザインを活かしながら、スマートフォン・タブレット対応、CMS設定、トップページ更新欄の調整などを行っています。</p>
            <!-- HUMAN_REVIEW: ページ数・工期を出せるなら追記。docs/human_review_before_publish.md 参照 -->
            <p class="work-scope">担当：既存HTML構造の再設計 → CSS再構築 → CMS組み込み・更新欄設定 → レスポンシブ対応 → クロスブラウザ確認</p>
            <p class="work-note">守秘義務の都合上、社名・URLは掲載していません。</p>
          </article>
        </div>
      </div>
    </section>

    <section id="demo" class="section section--white" aria-labelledby="demo-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="demo-title">デモサイトで、実装品質と導線設計を確認できます。</h2>
          <p>
            実案件は守秘義務の都合上、社名・URL・画面キャプチャを掲載できない場合があります。<br>
            そのため、品質確認用のデモサイトを用意しています。スマホ表示、セクション構成、CTA、下層導線、見た目のトーンをご確認ください。
          </p>
          <p class="privacy-subnote">デモサイトは品質確認用のサンプルです。実際の納品実績ではありません。ソースコードもそのままご確認いただけます。</p>
        </div>
        <div class="demo-grid">
          <article class="demo-card reveal">
            <h3>HARU COFFEE</h3>
            <p class="work-labels">想定業種：飲食店・カフェ</p>
            <p class="demo-tech">WordPress自作テーマ / セマンティックHTML / CSSカスタムプロパティ / レスポンシブ</p>
            <p>店舗の世界観を伝えるファーストビュー、メニュー・ニュース・アクセスへの導線、スマホでの読みやすさ。</p>
            <a class="button button-small button-ghost" href="/haru/" target="_blank" rel="noopener">HARU COFFEEを見る</a>
          </article>
          <article class="demo-card reveal">
            <h3>LIVESTA</h3>
            <p class="work-labels">想定業種：不動産会社・地域密着型サービス</p>
            <p class="demo-tech">WordPress自作テーマ / カスタム投稿タイプ / CSS Grid / レスポンシブ</p>
            <p>企業サイトとしての信頼感、サービス紹介、問い合わせ導線、BtoC寄りサイトの構成。</p>
            <a class="button button-small button-ghost" href="/livesta/" target="_blank" rel="noopener">LIVESTAを見る</a>
          </article>
          <article class="demo-card reveal">
            <h3>東京精密工業</h3>
            <p class="work-labels">想定業種：製造業・BtoB企業サイト</p>
            <p class="demo-tech">WordPress自作テーマ / セマンティックHTML / Vanilla JS / レスポンシブ</p>
            <p>BtoB向けの落ち着いたトーン、強み・製品・会社概要・問い合わせ導線、技術訴求の見せ方。</p>
            <a class="button button-small button-ghost" href="/tpi/" target="_blank" rel="noopener">東京精密工業を見る</a>
          </article>
        </div>
      </div>
    </section>

    <section id="how" class="section section--pale" aria-labelledby="how-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="how-title">概要だけ送って、必要な範囲から進められます。</h2>
        </div>
        <div class="how-grid">
          <article class="how-step reveal">
            <span class="step-num">1</span>
            <h3>概要を送る</h3>
            <p>NDA前は、クライアント名・URL・詳細情報を伏せた内容で問題ありません。</p>
          </article>
          <article class="how-step reveal">
            <span class="step-num">2</span>
            <h3>対応範囲を整理する</h3>
            <p>作業内容、納期、支給素材、連絡方法、MTG同席の有無を確認します。</p>
          </article>
          <article class="how-step reveal">
            <span class="step-num">3</span>
            <h3>見積・スケジュール確認</h3>
            <p>作業範囲、確認回数、納品形式を整理したうえで進めます。</p>
          </article>
          <article class="how-step reveal">
            <span class="step-num">4</span>
            <h3>必要に応じてNDA締結</h3>
            <p>詳細情報やログイン情報の共有が必要な場合は、NDA締結後に進めます。</p>
          </article>
          <article class="how-step reveal">
            <span class="step-num">5</span>
            <h3>制作・修正対応</h3>
            <p>貴社のチャット、メール、タスク管理、ファイル共有ルールに合わせて進行します。</p>
          </article>
          <!-- HUMAN_REVIEW: 対応ブラウザ範囲を確認。docs/human_review_before_publish.md 参照 -->
          <article class="how-step reveal">
            <span class="step-num">6</span>
            <h3>確認・納品</h3>
            <p>Chrome・Safari・Edge・Firefox・iOS Safariでの表示確認、HTMLバリデーション、リンクチェック、修正反映を行い、貴社の納品ルールに合わせて提出します。</p>
          </article>
        </div>
        <p class="how-note reveal">連絡・共有方法は、貴社の既存フローに合わせて調整します。</p>
      </div>
    </section>

    <section id="scope" class="section section--white" aria-labelledby="scope-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="scope-title">対応できる範囲を、事前に整理します。</h2>
          <p>個人事業として対応しているため、無理にすべてを引き受けるのではなく、対応できる範囲と難しい範囲を事前に確認します。</p>
        </div>
        <div class="scope-grid">
          <article class="scope-card reveal">
            <h3>原則対応外</h3>
            <ul>
              <li>大規模なシステム開発</li>
              <li>会員機能・決済機能・予約システムなどの新規開発</li>
              <li>仕様が未整理のままの丸投げ案件</li>
              <li>短納期かつ仕様変更が多い案件</li>
              <li>契約前の機密情報共有</li>
              <li>発注元企業様の許可がない実績公開</li>
              <li>エンドクライアントへの直接営業</li>
            </ul>
          </article>
          <article class="scope-card reveal">
            <h3>要相談</h3>
            <ul>
              <li>既存サイトの状態が不明な改修</li>
              <li>急ぎの公開作業</li>
              <li>複数ページの短納期対応</li>
              <li>MTG同席が必要な案件</li>
              <li>保守範囲が広い月額対応</li>
            </ul>
          </article>
        </div>
      </div>
    </section>

    <section id="faq" class="section section--pale" aria-labelledby="faq-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="faq-title">よくある質問</h2>
        </div>
        <div class="faq-list">
          <details class="faq-item reveal">
            <summary>Q1. 個人事業主でも、制作会社の外部パートナーとして継続依頼できますか？</summary>
            <p>はい、可能です。<br>ただし、大人数で大量案件を同時に対応する体制ではないため、対応範囲・納期・稼働状況を事前に確認します。継続案件の場合も、無理のない範囲を整理したうえで進めます。</p>
          </details>
          <details class="faq-item reveal">
            <summary>Q2. NDA締結前に相談できますか？</summary>
            <p>はい。NDA前は、クライアント名・URL・ログイン情報などを伏せた概要だけで問題ありません。詳細共有が必要な場合は、NDA締結後に進めます。</p>
          </details>
          <details class="faq-item reveal">
            <summary>Q3. エンドクライアントとのやり取りもお願いできますか？</summary>
            <p>原則として、エンドクライアントとの契約・連絡窓口は発注元企業様でお願いします。必要に応じて、貴社の制作パートナーとしてMTGに同席し、技術説明や仕様確認をサポートすることは可能です。</p>
          </details>
          <details class="faq-item reveal">
            <summary>Q4. 貴社名義での納品に対応できますか？</summary>
            <p>対応可能です。表記ルール、連絡経路、共有範囲、納品形式を事前に確認し、発注元企業様の方針に合わせて進めます。</p>
          </details>
          <details class="faq-item reveal">
            <summary>Q5. デモサイトは実際の納品実績ですか？</summary>
            <p>いいえ。デモサイトは、制作品質や導線設計を確認していただくためのサンプルサイトです。実案件の詳細は、守秘義務に配慮し、公開できる範囲で個別にご説明します。</p>
          </details>
          <details class="faq-item reveal">
            <summary>Q6. 実績は見られますか？</summary>
            <p>公開できる範囲の協力実績を、匿名で掲載しています。社名・URL・画面キャプチャは、守秘義務や発注元企業様の方針により掲載していません。品質確認は、デモサイトをご確認ください。</p>
          </details>
          <details class="faq-item reveal">
            <summary>Q7. どのくらいの情報があれば相談できますか？</summary>
            <p>最初は、作業内容、希望納期、支給素材の有無だけで大丈夫です。たとえば「Figmaあり、LPコーディング希望、6月上旬公開予定」程度の内容でも対応可否を確認できます。</p>
          </details>
          <!-- HUMAN_REVIEW: 料金の金額を確認・修正。docs/human_review_before_publish.md 参照 -->
          <details class="faq-item reveal">
            <summary>Q8. 料金の目安を教えてください</summary>
            <p>案件の規模・仕様により変動しますが、参考価格帯は以下のとおりです。</p>
            <ul class="faq-price-list">
              <li>LP実装（デザイン支給・1ページ）：¥50,000〜</li>
              <li>WordPress固定ページ追加：¥15,000〜</li>
              <li>軽微修正（テキスト・画像差し替え等）：¥5,000〜 / 件</li>
            </ul>
            <p>正式な金額は、作業範囲・ページ数・仕様を確認したうえで個別にお見積りします。</p>
          </details>
          <!-- HUMAN_REVIEW: 納期の目安を確認・修正。docs/human_review_before_publish.md 参照 -->
          <details class="faq-item reveal">
            <summary>Q9. 納期の目安を教えてください</summary>
            <p>案件の規模により変動しますが、目安は以下のとおりです。</p>
            <ul class="faq-price-list">
              <li>LP実装（1ページ）：3〜5営業日</li>
              <li>下層ページ追加：2〜5営業日</li>
              <li>WordPress固定ページ追加：2〜3営業日</li>
              <li>軽微修正：1〜2営業日</li>
            </ul>
            <p>短納期の場合も、まずは対応可否を確認しますのでお気軽にご相談ください。</p>
          </details>
        </div>
      </div>
    </section>

    <section class="section final-cta" aria-labelledby="final-cta-title">
      <div class="container final-cta-inner reveal">
        <h2 id="final-cta-title">まずは、社名・URLを伏せて概要だけ送ってください。</h2>
        <p>正式な仕様書がなくても問題ありません。<br>「この作業だけ外に出せるか」「この納期で対応できるか」「既存サイトの改修だけ相談できるか」など、分かる範囲でお送りください。</p>
        <a class="button button-primary" href="#contact">案件概要だけ送って相談する</a>
      </div>
    </section>

    <section id="privacy" class="section section--white privacy-section" aria-labelledby="privacy-title">
      <div class="container">
        <div class="section-heading">
          <h2 id="privacy-title">プライバシーポリシー</h2>
          <p>DF CONNECT（以下「当社」）は、お問い合わせフォームで取得した情報を、問い合わせ対応、見積作成、業務上の連絡、ならびに不正送信対策の目的で利用します。</p>
        </div>
        <div class="privacy-content">
          <h3>取得する情報</h3>
          <ul>
            <li>会社名、氏名、メールアドレス</li>
            <li>相談内容（任意項目含む）</li>
            <li>IPアドレス・User-Agent・送信元情報</li>
          </ul>
          <h3>利用目的</h3>
          <ul>
            <li>お問い合わせ対応</li>
            <li>見積作成・ご提案準備</li>
            <li>業務上の連絡</li>
            <li>不正送信・なりすまし対策</li>
          </ul>
          <p>法令に基づく場合を除き、同意なく第三者に提供しません。問い合わせ情報の開示・訂正・削除の依頼は、フォーム送信窓口までご連絡ください。</p>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="footer-brand">
        <div>
          <p class="footer-title">DF CONNECT</p>
          <p class="footer-subtitle">個人事業のWeb制作パートナー</p>
        </div>
        <p>Web制作会社・広告代理店向けに、コーディング、WordPress改修、軽微修正、公開前チェックを必要な範囲で対応しています。</p>
      </div>
      <div class="footer-links">
        <a href="#what">対応内容</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="#works">匿名実績</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="#demo">デモ</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="#how">進め方</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="#env">作業環境</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="#contact">相談する</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="#privacy">プライバシーポリシー</a>
      </div>
      <p class="footer-business">
        屋号：DF CONNECT<br>
        事業形態：個人事業<br>
        代表者：佐藤 優羽<br>
        所在地：東京都江東区平野1-1-10 小宮ビル<br>
        連絡先：お問い合わせフォームよりご連絡ください<br>
        対応領域：Web制作・改修・運用保守
      </p>
      <p class="copyright">© DF CONNECT</p>
    </div>
  </footer>

  <script src="js/main.js" defer></script>
</body>
</html>
