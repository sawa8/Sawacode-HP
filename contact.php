<?php
declare(strict_types=1);

mb_language('Japanese');
mb_internal_encoding('UTF-8');

/* ===== 設定 ===== */
const MAIL_TO      = 'sawa.designers.office@gmail.com';
const MAIL_FROM    = 'info@sawa-works-design.com'; // ← エックスサーバーで作成したメールアドレスに変更
const MAIL_SUBJECT = '【お問い合わせ】sawa web design';

/* ===== ヘルパー ===== */
function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function render(string $title, string $heading, string $message, bool $ok): void
{
    $cls = $ok ? 'is-ok' : 'is-error';
    ?><!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title><?= h($title) ?> | sawa web design</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.typekit.net/bdr2aga.css">
  <link rel="stylesheet" href="./css/styles.css">
  <script defer src="./js/script.js"></script>
</head>
<body>
  <header class="header">
    <div class="header__inner">
      <a href="index.html" class="header-logo__inner">
        <img class="header-logo__img" src="./images/logo_sawaworks.svg" alt="ロゴ">
        <span class="header-logo__text">sawa web design</span>
      </a>
      <div class="c-hamburger"><span></span><span></span><span></span></div>
      <nav class="p-header__pc-nav">
        <ul class="pc-nav__items">
          <li><a href="index.html" class="pc-nav__item">home</a></li>
          <li><a href="about.html" class="pc-nav__item">about</a></li>
          <li><a href="works.html" class="pc-nav__item">works</a></li>
          <li><a href="contact.html" class="pc-nav__item">contact</a></li>
        </ul>
      </nav>
      <nav class="p-header__sp-nav">
        <ul class="sp-nav__items">
          <li><a href="index.html" class="sp-nav__item">home</a></li>
          <li><a href="about.html" class="sp-nav__item">about</a></li>
          <li><a href="works.html" class="sp-nav__item">works</a></li>
          <li><a href="contact.html" class="sp-nav__item">contact</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <div class="drawer-overlay"></div>

  <div class="page-header">
    <h1 class="page-header__title">contact</h1>
    <p class="page-header__subtitle"><?= h($heading) ?></p>
  </div>

  <section class="section">
    <div class="inner">
      <div class="contact__content <?= $cls ?>" style="text-align:center;max-width:680px;margin-inline:auto;">
        <p class="contact__intro" style="white-space:pre-line;"><?= h($message) ?></p>
        <p style="margin-top:2rem;">
          <a class="contact-form__submit" href="index.html" style="display:inline-block;text-decoration:none;">トップへ戻る</a>
        </p>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="footer__inner">
      <div class="footer__logo-inner">
        <img class="footer__logo-img" src="./images/logo_sawaworks.svg" alt="ロゴ">
        <span class="footer__logo">sawa web design</span>
      </div>
      <nav class="footer__nav">
        <ul class="footer__nav-items">
          <li><a href="index.html" class="footer__nav-item">home</a></li>
          <li><a href="about.html" class="footer__nav-item">about</a></li>
          <li><a href="works.html" class="footer__nav-item">works</a></li>
          <li><a href="contact.html" class="footer__nav-item">contact</a></li>
        </ul>
      </nav>
      <p class="footer__copyright">Copyright 2026 sawa web design</p>
    </div>
  </footer>

  <div class="pagetop"><div class="pagetop__arrow"></div></div>
</body>
</html><?php
    exit;
}

/* ===== POST のみ受付 ===== */
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: contact.html');
    exit;
}

/* ===== 入力取得 ===== */
$name    = trim((string)($_POST['name'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$tel     = trim((string)($_POST['tel'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

/* ===== バリデーション ===== */
$errors = [];
if ($name === '') {
    $errors[] = 'お名前をご入力ください。';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '正しいメールアドレスをご入力ください。';
}
if ($message === '') {
    $errors[] = 'お問い合わせ内容をご入力ください。';
}
// ヘッダーインジェクション対策（改行を含む入力は拒否）
foreach ([$name, $email, $tel] as $oneLine) {
    if (preg_match('/[\r\n]/', $oneLine)) {
        $errors[] = '不正な入力が検出されました。';
        break;
    }
}

if ($errors) {
    render('送信エラー', 'send error', implode("\n", $errors) . "\n\nお手数ですが、入力内容をご確認のうえ再度お試しください。", false);
}

/* ===== メール組み立て ===== */
$body = <<<TXT
sawa web design のお問い合わせフォームから送信がありました。

────────────────────
お名前　　：{$name}
メール　　：{$email}
電話番号　：{$tel}
────────────────────
【お問い合わせ内容】
{$message}
────────────────────
送信日時　：%s
送信元IP　：%s
TXT;

$body = sprintf($body, date('Y-m-d H:i:s'), (string)($_SERVER['REMOTE_ADDR'] ?? '不明'));

$headers  = 'From: sawa web design <' . MAIL_FROM . '>' . "\r\n";
$headers .= 'Reply-To: ' . $email . "\r\n";

$sent = mb_send_mail(MAIL_TO, MAIL_SUBJECT, $body, $headers, '-f' . MAIL_FROM);

if ($sent) {
    render(
        '送信完了',
        'thank you',
        "お問い合わせありがとうございます。\n内容を確認の上、折り返しご連絡いたします。",
        true
    );
} else {
    render(
        '送信エラー',
        'send error',
        "申し訳ございません。送信処理に失敗しました。\nお手数ですが、時間をおいて再度お試しください。",
        false
    );
}
