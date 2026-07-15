<?php

/**
 * Simple Markdown → HTML → PDF helper (Windows Edge headless).
 * Usage: php scripts/md-to-pdf.php path/to/file.md [path/to/out.pdf]
 */

$in = $argv[1] ?? null;
$outPdf = $argv[2] ?? null;

if (! $in || ! is_file($in)) {
    fwrite(STDERR, "Usage: php scripts/md-to-pdf.php input.md [output.pdf]\n");
    exit(1);
}

$md = file_get_contents($in);
$htmlBody = mdToHtml($md);

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>THLIN Work Log</title>
<style>
  @page { margin: 18mm 16mm; }
  body {
    font-family: "Segoe UI", Calibri, Arial, sans-serif;
    font-size: 11pt;
    line-height: 1.45;
    color: #1f2937;
  }
  h1 { font-size: 20pt; color: #124a84; margin: 0 0 12px; border-bottom: 3px solid #185FA5; padding-bottom: 8px; }
  h2 { font-size: 14pt; color: #124a84; margin: 22px 0 10px; page-break-after: avoid; }
  h3 { font-size: 12pt; color: #185FA5; margin: 16px 0 8px; page-break-after: avoid; }
  p, li { margin: 0 0 8px; }
  ul, ol { padding-left: 1.25rem; margin: 0 0 10px; }
  table { width: 100%; border-collapse: collapse; margin: 0 0 14px; font-size: 9.5pt; page-break-inside: auto; }
  th, td { border: 1px solid #d1d5db; padding: 6px 8px; vertical-align: top; text-align: left; }
  th { background: #eef4fb; color: #124a84; font-weight: 700; }
  tr { page-break-inside: avoid; }
  code { font-family: Consolas, "Courier New", monospace; background: #f3f4f6; padding: 1px 4px; border-radius: 3px; font-size: 9pt; }
  pre { background: #f3f4f6; padding: 10px 12px; border-radius: 6px; overflow-x: auto; font-size: 8.5pt; }
  blockquote { margin: 0 0 12px; padding: 8px 12px; border-left: 4px solid #185FA5; background: #f8fafc; color: #374151; }
  hr { border: 0; border-top: 1px solid #d1d5db; margin: 18px 0; }
  strong { color: #111827; }
</style>
</head>
<body>
{$htmlBody}
</body>
</html>
HTML;

$htmlPath = preg_replace('/\.md$/i', '.html', $in) ?: ($in.'.html');
file_put_contents($htmlPath, $html);

$pdfPath = $outPdf ?: (preg_replace('/\.md$/i', '.pdf', $in) ?: ($in.'.pdf'));

$edgeCandidates = [
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
];

$browser = null;
foreach ($edgeCandidates as $candidate) {
    if (is_file($candidate)) {
        $browser = $candidate;
        break;
    }
}

if (! $browser) {
    fwrite(STDERR, "No Edge/Chrome found. HTML written to {$htmlPath}\n");
    exit(2);
}

$fileUrl = 'file:///'.str_replace('\\', '/', $htmlPath);
$cmd = '"'.$browser.'" --headless --disable-gpu --no-pdf-header-footer'
    .' --print-to-pdf="'.$pdfPath.'" '.escapeshellarg($fileUrl);

passthru($cmd, $code);

if (! is_file($pdfPath) || filesize($pdfPath) < 100) {
    fwrite(STDERR, "PDF generation failed (exit {$code}). HTML at {$htmlPath}\n");
    exit(3);
}

echo $pdfPath.PHP_EOL;
exit(0);

function mdToHtml(string $md): string
{
    $md = str_replace(["\r\n", "\r"], "\n", $md);
    $lines = explode("\n", $md);
    $out = [];
    $inCode = false;
    $inTable = false;
    $listType = null;

    $flushList = function () use (&$out, &$listType) {
        if ($listType) {
            $out[] = $listType === 'ol' ? '</ol>' : '</ul>';
            $listType = null;
        }
    };

    $flushTable = function () use (&$out, &$inTable) {
        if ($inTable) {
            $out[] = '</tbody></table>';
            $inTable = false;
        }
    };

    foreach ($lines as $line) {
        if (str_starts_with($line, '```')) {
            $flushList();
            $flushTable();
            if ($inCode) {
                $out[] = '</code></pre>';
                $inCode = false;
            } else {
                $out[] = '<pre><code>';
                $inCode = true;
            }
            continue;
        }

        if ($inCode) {
            $out[] = htmlspecialchars($line, ENT_QUOTES, 'UTF-8')."\n";
            continue;
        }

        if (preg_match('/^\|(.+)\|$/', trim($line))) {
            $cells = array_map('trim', explode('|', trim($line, "| \t")));
            if (preg_match('/^\|?\s*:?-+:?\s*(\|\s*:?-+:?\s*)+\|?$/', trim($line))) {
                continue; // separator row
            }
            $flushList();
            if (! $inTable) {
                $out[] = '<table><thead><tr>';
                foreach ($cells as $cell) {
                    $out[] = '<th>'.inline($cell).'</th>';
                }
                $out[] = '</tr></thead><tbody>';
                $inTable = true;
            } else {
                $out[] = '<tr>';
                foreach ($cells as $cell) {
                    $out[] = '<td>'.inline($cell).'</td>';
                }
                $out[] = '</tr>';
            }
            continue;
        }

        $flushTable();

        if (trim($line) === '') {
            $flushList();
            continue;
        }

        if (preg_match('/^(-{3,}|\*{3,}|_{3,})$/', trim($line))) {
            $flushList();
            $out[] = '<hr>';
            continue;
        }

        if (preg_match('/^(#{1,6})\s+(.+)$/', $line, $m)) {
            $flushList();
            $level = strlen($m[1]);
            $out[] = "<h{$level}>".inline($m[2])."</h{$level}>";
            continue;
        }

        if (preg_match('/^>\s?(.*)$/', $line, $m)) {
            $flushList();
            $out[] = '<blockquote><p>'.inline($m[1]).'</p></blockquote>';
            continue;
        }

        if (preg_match('/^(\d+)\.\s+(.+)$/', $line, $m)) {
            if ($listType !== 'ol') {
                $flushList();
                $out[] = '<ol>';
                $listType = 'ol';
            }
            $out[] = '<li>'.inline($m[2]).'</li>';
            continue;
        }

        if (preg_match('/^[-*+]\s+(.+)$/', $line, $m)) {
            if ($listType !== 'ul') {
                $flushList();
                $out[] = '<ul>';
                $listType = 'ul';
            }
            $out[] = '<li>'.inline($m[1]).'</li>';
            continue;
        }

        $flushList();
        $out[] = '<p>'.inline($line).'</p>';
    }

    $flushList();
    $flushTable();
    if ($inCode) {
        $out[] = '</code></pre>';
    }

    return implode("\n", $out);
}

function inline(string $text): string
{
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text) ?? $text;
    $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text) ?? $text;
    $text = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $text) ?? $text;
    $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $text) ?? $text;

    return $text;
}
