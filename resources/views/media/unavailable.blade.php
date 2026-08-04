<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>File unavailable — {{ $mediaFile->title }}</title>
    <style>
        body { font-family: Inter, system-ui, sans-serif; margin: 0; background: #eef3f9; color: #1f2937; }
        main { max-width: 560px; margin: 4rem auto; padding: 2rem; background: #fff; border-radius: 16px; box-shadow: 0 16px 40px rgba(4, 44, 83, 0.08); }
        h1 { font-size: 1.5rem; margin: 0 0 0.75rem; color: #042c53; }
        p { line-height: 1.6; margin: 0 0 1rem; }
        a { color: #185fa5; font-weight: 700; }
        code { background: #f1f5f9; padding: 0.1rem 0.35rem; border-radius: 4px; }
    </style>
</head>
<body>
    <main>
        <h1>PDF not available on this server</h1>
        <p><strong>{{ $mediaFile->title }}</strong> is listed in the CMS, but the file is not stored on this computer or deployment.</p>
        <p>This usually happens when the PDF was uploaded on Vercel (or another environment) while your local site shares the same database.</p>
        <p>Go to <a href="{{ route('admin.media.index') }}">Uploaded Files</a> and upload the PDF again on this environment, or configure <code>CLOUDINARY_URL</code> on Vercel for permanent cloud storage.</p>
    </main>
</body>
</html>
