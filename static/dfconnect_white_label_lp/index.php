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
  <title>Web制作会社向け制作・改修パートナー｜DF CONNECT</title>
  <meta name="description" content="DF CONNECTは、Web制作会社・広告代理店向けの制作・改修・運用保守パートナーです。デザイン支給後のコーディング、WordPress改修、軽微修正、公開前チェックまで、貴社名義・NDA前提で対応します。案件概要だけでもご相談ください。">
  <meta property="og:title" content="Web制作会社向け制作・改修パートナー｜DF CONNECT">
  <meta property="og:description" content="NDA対応・貴社名義で納品。デザイン支給後のコーディング、WordPress改修、軽微修正・保守まで。案件概要だけでもご相談ください。">
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
        <img src="assets/img/logo.svg" alt="DF CONNECT" width="260" height="48">
      </a>
      <button class="menu-button js-menu-button" type="button" aria-controls="site-nav" aria-expanded="false">
        <span></span><span></span><span></span>
        <span class="sr-only">メニューを開く</span>
      </button>
      <nav id="site-nav" class="site-nav" aria-label="グローバルナビゲーション">
        <a href="#what">対応内容</a>
        <a href="#how">進め方</a>
        <a class="button button-primary button-small" href="#contact-form">相談する</a>
      </nav>
    </div>
  </header>

  <main id="main">
    <section id="top" class="hero section">
      <div class="container hero-grid">
        <div class="hero-copy reveal">
          <h1>Web制作会社の手が足りない時に。<br>貴社名義で動ける制作・改修パートナー。</h1>
          <p class="hero-lead">デザイン支給後のコーディング、WordPress改修、軽微修正・保守まで。<br>NDA前は社名・URLを伏せた概要だけでご相談いただけます。</p>

          <div class="hero-chips" aria-label="DF CONNECTの特徴">
            <span class="chip">NDA対応</span>
            <span class="chip">貴社名義で納品</span>
            <span class="chip">必要時のみMTG同席</span>
            <span class="chip">小さな修正から相談OK</span>
          </div>

          <a class="button button-primary hero-cta-sp" href="#contact-form">概要だけ送って相談する</a>
        </div>

        <div class="hero-form-wrapper reveal">
          <form id="contact-form" class="contact-form js-contact-form" action="api/contact.php" method="post" novalidate>
            <p class="form-title">案件概要だけでもご相談ください</p>
            <p class="form-subtitle">NDA締結前は、クライアント名・URL・詳細情報を伏せた内容で問題ありません。<br>対応可否と進め方を整理して返信します。</p>

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
                placeholder="株式会社〇〇 / 屋号"
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
                <option value="form_check"<?= is_selected($old['inquiry_type'] ?? '', 'form_check') ?>>フォーム・公開前チェック</option>
                <option value="white_label_nda"<?= is_selected($old['inquiry_type'] ?? '', 'white_label_nda') ?>>NDA・ホワイトラベル相談</option>
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
              <a href="privacy.html" target="_blank" rel="noopener">プライバシーポリシー</a>に同意のうえ送信します
              <?php if (!empty($fieldErrors['privacy'])): ?>
                <p class="form-error" id="err-privacy"><?= h($fieldErrors['privacy']) ?></p>
              <?php endif; ?>
            </label>

            <button class="button button-primary button-submit" type="submit">概要だけ送って相談する</button>
          </form>
        </div>
      </div>
    </section>

    <section id="why" class="section section--white" aria-labelledby="why-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="why-title">制作会社の現場で、こんな作業が詰まりやすくありませんか？</h2>
          <p>新規案件だけでなく、下層ページ制作、既存サイトの改修、公開後の更新作業まで。<br>社内リソースを圧迫しやすい実務を、必要な範囲だけ外部化できます。</p>
        </div>
        <div class="why-grid">
          <article class="why-card reveal">
            <span class="why-icon">1</span>
            <p>案件はあるのに、手を動かせる制作リソースが足りない</p>
          </article>
          <article class="why-card reveal">
            <span class="why-icon">2</span>
            <p>軽微修正や保守作業が社内に溜まっている</p>
          </article>
          <article class="why-card reveal">
            <span class="why-icon">3</span>
            <p>外注したいが、クライアント情報や品質管理が不安</p>
          </article>
        </div>
        <p class="why-conclusion reveal">DF CONNECTは、制作会社様の裏側で、必要な作業だけを引き受ける外部制作パートナーです。</p>
      </div>
    </section>

    <section id="what" class="section section--pale" aria-labelledby="what-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="what-title">任せられること</h2>
        </div>
        <div class="what-grid">
          <article class="what-card reveal">
            <h3>コーディング・LP実装</h3>
            <p>Figma / XD / 画像カンプからのHTML/CSS実装、レスポンシブ対応。LPや下層ページの実装もご相談いただけます。</p>
          </article>
          <article class="what-card reveal">
            <h3>WordPress制作・改修</h3>
            <p>固定ページ追加、既存テーマ修正、フォーム調整、軽微なPHP対応。既存サイトの改修やWordPress化にも対応します。</p>
          </article>
          <article class="what-card reveal">
            <h3>運用保守・軽微修正</h3>
            <p>テキスト修正、画像差し替え、WordPress更新補助、バックアップ確認など。公開後の細かな作業を外部化できます。</p>
          </article>
          <article class="what-card reveal">
            <h3>公開前チェック・フォーム確認</h3>
            <p>表示崩れ、リンク切れ、フォーム送信、OGP/meta、公開作業補助。納品前・公開前の確認作業も支援します。</p>
          </article>
        </div>
        <p class="what-price reveal">小さな修正・公開前チェックは10,000円〜、下層ページ実装・WordPress改修は30,000円〜ご相談可能です。</p>
      </div>
    </section>

    <section id="how" class="section section--white" aria-labelledby="how-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="how-title">概要だけ送って、必要な範囲から進められます</h2>
        </div>
        <div class="how-grid">
          <article class="how-step reveal">
            <span class="step-num">1</span>
            <h3>概要だけ送る</h3>
            <p>NDA前は、クライアント名・URL・詳細情報を伏せた内容で問題ありません。</p>
          </article>
          <article class="how-step reveal">
            <span class="step-num">2</span>
            <h3>対応範囲を整理する</h3>
            <p>作業内容、納期、連絡経路、MTG同席の有無を確認します。</p>
          </article>
          <article class="how-step reveal">
            <span class="step-num">3</span>
            <h3>必要に応じてNDA後に制作開始</h3>
            <p>貴社名義・貴社窓口の方針に合わせて進行します。</p>
          </article>
        </div>
        <p class="how-note reveal">連絡・共有方法は、貴社の既存フローに合わせて調整します。</p>
      </div>
    </section>

    <section id="assurance" class="section section--pale" aria-labelledby="assurance-title">
      <div class="container">
        <div class="section-heading reveal">
          <h2 id="assurance-title">安心して進めるための前提</h2>
        </div>
        <div class="assurance-grid">
          <article class="assurance-card reveal">
            <h3>NDA前の相談</h3>
            <p>NDA締結前は、社名・URLなどの機密情報を伏せた概要のみで問題ありません。</p>
          </article>
          <article class="assurance-card reveal">
            <h3>エンドクライアント対応</h3>
            <p>エンドクライアントとの連絡・契約は、原則として発注元企業様を窓口にして進行します。必要に応じて、貴社の制作パートナーとして打ち合わせ同席や技術説明をサポートします。</p>
          </article>
          <article class="assurance-card reveal">
            <h3>開発範囲</h3>
            <p>対応範囲は、Web制作に付随する実装・改修・保守が中心です。会員機能、決済、予約システム、業務システムなど、大規模なシステム開発は原則対象外です。</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section final-cta" aria-labelledby="final-cta-title">
      <div class="container final-cta-inner reveal">
        <h2 id="final-cta-title">まずは概要だけ送ってください。</h2>
        <p>対応可否と進め方を整理して返信します。</p>
        <a class="button button-primary" href="#contact-form">概要だけ送って相談する</a>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="footer-brand">
        <img src="assets/img/logo.svg" alt="DF CONNECT" width="180" height="33">
        <p>Web制作会社・広告代理店向けの制作・改修・運用保守パートナー</p>
      </div>
      <div class="footer-links">
        <a href="#">会社概要</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="privacy.html">プライバシーポリシー</a>
        <span class="footer-sep" aria-hidden="true">/</span>
        <a href="#">特定商取引法に基づく表記</a>
      </div>
      <p class="copyright">&copy; DF CONNECT All Rights Reserved.</p>
    </div>
  </footer>

  <script src="js/main.js" defer></script>
</body>
</html>
