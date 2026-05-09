<?php
declare(strict_types=1);

$package = require __DIR__ . '/package.php';
$rootDir = dirname(__DIR__);
$configPath = $rootDir . '/xserver-db-config.php';
$sqlPath = __DIR__ . '/wordpress.sql';
$lockPath = __DIR__ . '/.imported';

function xserver_h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function xserver_detect_current_url(): string
{
    $scheme = 'http';

    if (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos((string) $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
    ) {
        $scheme = 'https';
    }

    $host = trim((string) ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? ''));

    if ($host === '') {
        return '';
    }

    return rtrim($scheme . '://' . $host, '/');
}

function xserver_env(string $name, string $default = ''): string
{
    $value = getenv($name);

    return $value === false ? $default : (string) $value;
}

function xserver_identifier(string $name): string
{
    return '`' . str_replace('`', '``', $name) . '`';
}

function xserver_primary_keys(string $table): array
{
    global $wpdb;

    $rows = $wpdb->get_results(
        'SHOW INDEX FROM ' . xserver_identifier($table) . " WHERE Key_name = 'PRIMARY' ORDER BY Seq_in_index ASC",
        ARRAY_A
    );

    if (!is_array($rows)) {
        return [];
    }

    $keys = [];

    foreach ($rows as $row) {
        if (isset($row['Column_name']) && $row['Column_name'] !== '') {
            $keys[] = (string) $row['Column_name'];
        }
    }

    return array_values(array_unique($keys));
}

function xserver_text_columns(string $table): array
{
    global $wpdb;

    $rows = $wpdb->get_results('SHOW FULL COLUMNS FROM ' . xserver_identifier($table), ARRAY_A);

    if (!is_array($rows)) {
        return [];
    }

    $columns = [];

    foreach ($rows as $row) {
        $type = strtolower((string) ($row['Type'] ?? ''));

        if ($type === '') {
            continue;
        }

        if (
            strpos($type, 'char') !== false
            || strpos($type, 'text') !== false
            || strpos($type, 'json') !== false
            || strpos($type, 'enum') !== false
            || strpos($type, 'set') !== false
        ) {
            $field = (string) ($row['Field'] ?? '');

            if ($field !== '') {
                $columns[] = $field;
            }
        }
    }

    return array_values(array_unique($columns));
}

function xserver_replace_value($value, array $searches, string $replace)
{
    if (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] = xserver_replace_value($item, $searches, $replace);
        }

        return $value;
    }

    if (is_object($value)) {
        foreach (get_object_vars($value) as $key => $item) {
            $value->$key = xserver_replace_value($item, $searches, $replace);
        }

        return $value;
    }

    if (!is_string($value) || $value === '') {
        return $value;
    }

    if (function_exists('is_serialized') && is_serialized($value)) {
        $decoded = maybe_unserialize($value);
        $replaced = xserver_replace_value($decoded, $searches, $replace);

        return maybe_serialize($replaced);
    }

    return str_replace($searches, $replace, $value);
}

function xserver_search_replace_urls(array $sourceUrls, string $targetUrl): int
{
    global $wpdb;

    $normalizedSources = [];

    foreach ($sourceUrls as $url) {
        $url = rtrim((string) $url, '/');

        if ($url === '' || $url === $targetUrl) {
            continue;
        }

        if (!in_array($url, $normalizedSources, true)) {
            $normalizedSources[] = $url;
        }
    }

    if ($targetUrl === '' || $normalizedSources === []) {
        return 0;
    }

    $updatedRows = 0;
    $tables = $wpdb->get_col('SHOW TABLES');

    if (!is_array($tables)) {
        return 0;
    }

    foreach ($tables as $table) {
        $table = (string) $table;
        $primaryKeys = xserver_primary_keys($table);
        $textColumns = xserver_text_columns($table);

        if ($primaryKeys === [] || $textColumns === []) {
            continue;
        }

        $selectColumns = array_values(array_unique(array_merge($primaryKeys, $textColumns)));
        $quotedColumns = array_map('xserver_identifier', $selectColumns);
        $rows = $wpdb->get_results(
            'SELECT ' . implode(', ', $quotedColumns) . ' FROM ' . xserver_identifier($table),
            ARRAY_A
        );

        if (!is_array($rows) || $rows === []) {
            continue;
        }

        foreach ($rows as $row) {
            $updates = [];
            $where = [];

            foreach ($primaryKeys as $primaryKey) {
                if (array_key_exists($primaryKey, $row)) {
                    $where[$primaryKey] = $row[$primaryKey];
                }
            }

            if ($where === []) {
                continue;
            }

            foreach ($textColumns as $column) {
                if (!array_key_exists($column, $row)) {
                    continue;
                }

                $original = $row[$column];
                $replaced = xserver_replace_value($original, $normalizedSources, $targetUrl);

                if ($replaced !== $original) {
                    $updates[$column] = $replaced;
                }
            }

            if ($updates === []) {
                continue;
            }

            $wpdb->update($table, $updates, $where);
            $updatedRows++;
        }
    }

    update_option('siteurl', $targetUrl);
    update_option('home', $targetUrl);

    return $updatedRows;
}

function xserver_import_sql(mysqli $mysqli, string $sqlPath): void
{
    $sql = file_get_contents($sqlPath);

    if ($sql === false || trim($sql) === '') {
        throw new RuntimeException('SQL ダンプを読み込めませんでした。');
    }

    if (!$mysqli->multi_query($sql)) {
        throw new RuntimeException('SQL の実行に失敗しました: ' . $mysqli->error);
    }

    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());

    if ($mysqli->error !== '') {
        throw new RuntimeException('SQL の実行中にエラーが発生しました: ' . $mysqli->error);
    }
}

$token = (string) ($_GET['token'] ?? $_POST['token'] ?? '');
$expectedToken = (string) ($package['import_token'] ?? '');
$currentUrl = xserver_detect_current_url();

if ($token === '' || $token !== $expectedToken) {
    http_response_code(403);
    ?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>XServer Import</title>
</head>
<body>
  <h1>トークン不一致</h1>
  <p>正しい token を付けてアクセスしてください。</p>
</body>
</html>
<?php
    exit;
}

if (!is_file($configPath)) {
    http_response_code(400);
    ?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>XServer Import</title>
</head>
<body>
  <h1>設定ファイル未配置</h1>
  <p><code>xserver-db-config.php.example</code> を <code>xserver-db-config.php</code> にコピーして、DB 情報を入力してください。</p>
  <p>配置先: <code><?= xserver_h($configPath) ?></code></p>
</body>
</html>
<?php
    exit;
}

require_once $configPath;

if (is_file($lockPath)) {
    $lockData = file_get_contents($lockPath);
    ?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>XServer Import</title>
</head>
<body>
  <h1>インポート済み</h1>
  <p>このパッケージはすでにインポート済みです。</p>
  <p>現在 URL: <code><?= xserver_h($currentUrl) ?></code></p>
  <?php if ($lockData !== false && trim($lockData) !== ''): ?>
  <pre><?= xserver_h($lockData) ?></pre>
  <?php endif; ?>
</body>
</html>
<?php
    exit;
}

$dbName = xserver_env('WORDPRESS_DB_NAME');
$dbUser = xserver_env('WORDPRESS_DB_USER');
$dbPassword = xserver_env('WORDPRESS_DB_PASSWORD');
$dbHost = xserver_env('WORDPRESS_DB_HOST');

if ($dbName === '' || $dbUser === '' || $dbHost === '') {
    http_response_code(400);
    ?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>XServer Import</title>
</head>
<body>
  <h1>DB 設定不足</h1>
  <p><code>xserver-db-config.php</code> の DB 設定を確認してください。</p>
</body>
</html>
<?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>XServer Import</title>
</head>
<body>
  <h1><?= xserver_h((string) ($package['site_key'] ?? 'wordpress')) ?> 初回インポート</h1>
  <ul>
    <li>現在 URL: <code><?= xserver_h($currentUrl) ?></code></li>
    <li>元 URL: <code><?= xserver_h(implode(', ', (array) ($package['source_urls'] ?? []))) ?></code></li>
    <li>SQL: <code><?= xserver_h($sqlPath) ?></code></li>
  </ul>
  <form method="post">
    <input type="hidden" name="token" value="<?= xserver_h($token) ?>">
    <button type="submit">SQL を取り込み、URL を現在ドメインへ置換する</button>
  </form>
</body>
</html>
<?php
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!is_file($sqlPath)) {
        throw new RuntimeException('SQL ダンプが見つかりません。');
    }

    $mysqli = mysqli_init();
    $mysqli->real_connect($dbHost, $dbUser, $dbPassword, $dbName);
    $mysqli->set_charset('utf8mb4');

    xserver_import_sql($mysqli, $sqlPath);
    $mysqli->close();

    require_once $rootDir . '/wp-load.php';

    $replacedRows = xserver_search_replace_urls((array) ($package['source_urls'] ?? []), $currentUrl);

    if (function_exists('flush_rewrite_rules')) {
        flush_rewrite_rules();
    }

    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }

    file_put_contents(
        $lockPath,
        json_encode(
            [
                'site_key' => $package['site_key'] ?? '',
                'imported_at' => date('c'),
                'current_url' => $currentUrl,
                'replaced_rows' => $replacedRows,
            ],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        )
    );
} catch (Throwable $throwable) {
    http_response_code(500);
    ?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>XServer Import</title>
</head>
<body>
  <h1>インポート失敗</h1>
  <pre><?= xserver_h($throwable->getMessage()) ?></pre>
</body>
</html>
<?php
    exit;
}
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>XServer Import</title>
</head>
<body>
  <h1>インポート完了</h1>
  <ul>
    <li>現在 URL: <code><?= xserver_h($currentUrl) ?></code></li>
    <li>置換元 URL: <code><?= xserver_h(implode(', ', (array) ($package['source_urls'] ?? []))) ?></code></li>
  </ul>
  <p>確認後は <code>_migration/</code> を削除してください。</p>
</body>
</html>
