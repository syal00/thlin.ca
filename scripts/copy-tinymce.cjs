const fs = require('fs');
const path = require('path');

const source = path.join(__dirname, '..', 'node_modules', 'tinymce');
const target = path.join(__dirname, '..', 'public', 'vendor', 'tinymce');

function copyRecursive(from, to) {
    fs.mkdirSync(to, { recursive: true });

    for (const entry of fs.readdirSync(from, { withFileTypes: true })) {
        const srcPath = path.join(from, entry.name);
        const destPath = path.join(to, entry.name);

        if (entry.isDirectory()) {
            copyRecursive(srcPath, destPath);
        } else {
            fs.copyFileSync(srcPath, destPath);
        }
    }
}

if (!fs.existsSync(source)) {
    console.error('TinyMCE not found. Run: npm install');
    process.exit(1);
}

fs.rmSync(target, { recursive: true, force: true });
copyRecursive(source, target);

console.log('TinyMCE copied to public/vendor/tinymce');
