<?php
/**
 * CODEX - Firebase Lesson API
 * GET  ?action=getNextId  → returns next lesson ID (e.g. L24)
 * POST ?action=save       → saves / updates a lesson
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ─── FIREBASE CONFIG ───────────────────────────────────────────────────────
// Same project used by dashboard.php and add_lesson.php (Firebase JS SDK)
//   apiKey            : AIzaSyBmFwQe51Sfkhr36aXXlw4NYv7jag-8OcY
//   authDomain        : codex-f1355.firebaseapp.com
//   projectId         : codex-f1355
//   appId             : 1:273276166035:web:e1f895eeaa03200a975266
define('FIREBASE_BASE_URL', 'https://codex-f1355-default-rtdb.firebaseio.com');
// If Firebase rules require auth, set your database secret here:
// define('FIREBASE_SECRET', 'YOUR_DATABASE_SECRET_HERE');
// ───────────────────────────────────────────────────────────────────────────

/**
 * Make a request to Firebase REST API.
 */
function firebaseRequest(string $path, string $method = 'GET', mixed $data = null): array {
    $url = FIREBASE_BASE_URL . $path . '.json';

    if (defined('FIREBASE_SECRET')) {
        $url .= '?auth=' . FIREBASE_SECRET;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    if ($method === 'PUT' || $method === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }

    $result   = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($curlErr) {
        return ['code' => 0, 'data' => null, 'error' => $curlErr];
    }

    return ['code' => $httpCode, 'data' => json_decode($result, true)];
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$action        = $_GET['action'] ?? '';

// ── GET: Fetch next Lesson ID ──────────────────────────────────────────────
if ($requestMethod === 'GET' && $action === 'getNextId') {
    $result = firebaseRequest('/Lessons', 'GET');

    if ($result['code'] !== 200) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => 'Firebase unreachable. Check your database URL and network.',
            'detail'  => $result['error'] ?? "HTTP {$result['code']}",
        ]);
        exit;
    }

    $data = $result['data'];

    if (!$data || !is_array($data)) {
        // No lessons yet
        echo json_encode(['success' => true, 'nextId' => 'L1', 'totalLessons' => 0]);
        exit;
    }

    $nums = [];
    foreach (array_keys($data) as $key) {
        if (preg_match('/^L(\d+)$/', $key, $m)) {
            $nums[] = (int) $m[1];
        }
    }

    $nextNum = empty($nums) ? 1 : max($nums) + 1;

    echo json_encode([
        'success'      => true,
        'nextId'       => 'L' . $nextNum,
        'totalLessons' => count($data),
    ]);
    exit;
}

// ── POST: Save / Update Lesson ─────────────────────────────────────────────
if ($requestMethod === 'POST' && $action === 'save') {
    $raw   = file_get_contents('php://input');
    $input = json_decode($raw, true);

    $lessonId   = $input['lessonId']   ?? null;
    $lessonData = $input['lessonData'] ?? null;

    if (!$lessonId || !$lessonData) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing lessonId or lessonData']);
        exit;
    }

    if (!preg_match('/^L\d+$/', $lessonId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid lessonId format. Expected L{number}.']);
        exit;
    }

    // ── Transform the incoming lessonData into the exact Firebase schema ──
    $firebaseLesson = buildFirebaseLesson($lessonData);

    // Check if lesson already exists (prevent full overwrite)
    $check = firebaseRequest('/Lessons/' . $lessonId, 'GET');
    if ($check['code'] === 200 && $check['data'] !== null) {
        $result = firebaseRequest('/Lessons/' . $lessonId, 'PATCH', $firebaseLesson);
    } else {
        $result = firebaseRequest('/Lessons/' . $lessonId, 'PUT', $firebaseLesson);
    }

    if ($result['code'] === 200) {
        echo json_encode([
            'success'  => true,
            'lessonId' => $lessonId,
            'message'  => 'Lesson saved successfully.',
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => 'Firebase write failed',
            'code'    => $result['code'],
        ]);
    }
    exit;
}

// ── Fallback ───────────────────────────────────────────────────────────────
http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Invalid or unsupported request.']);


// ═══════════════════════════════════════════════════════════════════════════
// SCHEMA BUILDER
// Transforms the front-end payload into the exact Firebase structure used
// by the CODEX Android app (matches the existing L1–L23 export).
//
// Firebase schema reference (from DB export):
//
// Lessons/{LN}
//   difficulty       : string  ("Beginner" | "Intermediate" | "Advanced")
//   main_title       : string  ("<p>…</p>")
//   title_desc       : string  (rich HTML)
//   content/{TN}
//     TITLE
//       title          : string  ("<p>…</p>")
//       description    : string  (rich HTML)
//       helper1        : string  (rich HTML description)
//       helper1Drawable: string  (image filename, e.g. "l24_img1")
//       helper1Code    : string  (encoded code snippet)
//       helper2        : string
//       helper2Drawable: string
//       helper2Code    : string
//       helper3        : string
//       helper3Drawable: string
//       helper3Code    : string
//     EXAMPLE (optional)
//       TYPES
//         exampleTitle      : string
//         exampleDescription: string
//         helper4        : string
//         helper4Drawable: string
//         helper4Code    : string
//         helper5        : string
//         helper5Drawable: string
//         helper5Code    : string
//     SUBTITLE (optional)
//       OUTPUT
//         subtitle       : string
//         helper6        : string
//         helper6Drawable: string
//         helper6Code    : string
//         helper7        : string
//         helper7Drawable: string
//         helper7Code    : string
//     TOOLTIP (optional)
//       tooltip          : string  (plain text)
// ═══════════════════════════════════════════════════════════════════════════
function buildFirebaseLesson(array $data): array {
    $lesson = [
        'difficulty' => $data['difficulty'] ?? '',
        'main_title' => $data['main_title'] ?? '<br/>',
        'title_desc' => $data['title_desc'] ?? '<br/>',
        'content'    => [],
    ];

    foreach ($data['content'] ?? [] as $blockId => $block) {
        $fbBlock = [];

        // ── TITLE ────────────────────────────────────────────────────────
        $title = $block['TITLE'] ?? [];
        $imgs  = $title['images'] ?? [];
        $codes = $title['codes']  ?? [];

        $fbBlock['TITLE'] = [
            'title'           => $title['title']       ?? '<br/>',
            'description'     => $title['description'] ?? '<br/>',
            'helper1'         => htmlOrBlank($imgs[0]['helper']  ?? ''),
            'helper1Drawable' => $imgs[0]['fileName']  ?? '',
            'helper1Code'     => $codes[0]['snippet']  ?? '',
            'helper2'         => htmlOrBlank($imgs[1]['helper']  ?? ''),
            'helper2Drawable' => $imgs[1]['fileName']  ?? '',
            'helper2Code'     => $codes[1]['snippet']  ?? '',
            'helper3'         => htmlOrBlank($imgs[2]['helper']  ?? ''),
            'helper3Drawable' => $imgs[2]['fileName']  ?? '',
            'helper3Code'     => $codes[2]['snippet']  ?? '',
        ];

        // ── EXAMPLE / TYPES ──────────────────────────────────────────────
        $example    = $block['EXAMPLE'] ?? [];
        $exImgs     = $example['images'] ?? [];
        $exCodes    = $example['codes']  ?? [];
        $hasExample = !empty($example['title']) || !empty($example['description'])
                   || count($exImgs) || count($exCodes);

        if ($hasExample) {
            $fbBlock['EXAMPLE'] = [
                'TYPES' => [
                    'exampleTitle'       => $example['title']       ?? '<br/>',
                    'exampleDescription' => $example['description'] ?? '<br/>',
                    'helper4'            => htmlOrBlank($exImgs[0]['helper']  ?? ''),
                    'helper4Drawable'    => $exImgs[0]['fileName']  ?? '',
                    'helper4Code'        => $exCodes[0]['snippet']  ?? '',
                    'helper5'            => htmlOrBlank($exImgs[1]['helper']  ?? ''),
                    'helper5Drawable'    => $exImgs[1]['fileName']  ?? '',
                    'helper5Code'        => $exCodes[1]['snippet']  ?? '',
                ],
            ];
        }

        // ── SUBTITLE / OUTPUT ────────────────────────────────────────────
        $output    = $block['SUBTITLE'] ?? [];
        $outImgs   = $output['images']  ?? [];
        $outCodes  = $output['codes']   ?? [];
        $hasOutput = !empty($output['subtitle']) || !empty($output['title'])
                  || count($outImgs) || count($outCodes);

        if ($hasOutput) {
            $fbBlock['SUBTITLE'] = [
                'OUTPUT' => [
                    'subtitle'       => $output['subtitle']    ?? '<br/>',
                    'helper6'        => htmlOrBlank($outImgs[0]['helper']  ?? ''),
                    'helper6Drawable'=> $outImgs[0]['fileName'] ?? '',
                    'helper6Code'    => $outCodes[0]['snippet'] ?? '',
                    'helper7'        => htmlOrBlank($outImgs[1]['helper']  ?? ''),
                    'helper7Drawable'=> $outImgs[1]['fileName'] ?? '',
                    'helper7Code'    => $outCodes[1]['snippet'] ?? '',
                ],
            ];
        }

        // ── TOOLTIP ──────────────────────────────────────────────────────
        $tooltipText = $block['TOOLTIP']['tooltip'] ?? '';
        if (trim($tooltipText)) {
            $fbBlock['TOOLTIP'] = ['tooltip' => $tooltipText];
        }

        $lesson['content'][$blockId] = $fbBlock;
    }

    return $lesson;
}

/**
 * Wrap a plain helper string in <p> tags, or return '<br/>' if empty.
 */
function htmlOrBlank(string $text): string {
    $text = trim($text);
    if ($text === '') return '<br/>';
    // If it already contains HTML tags, leave as-is
    if (preg_match('/<[^>]+>/', $text)) return $text;
    return '<p>' . htmlspecialchars($text, ENT_QUOTES) . '</p>';
}