const fs = require('fs');
const path = require('path');

const distDir = path.join(__dirname, '..', 'node_modules', 'cropperjs', 'dist');
const target = path.join(__dirname, '..', 'public', 'vendor', 'cropperjs');

const files = ['cropper.min.js', 'cropper.min.css'];

if (!fs.existsSync(distDir)) {
    console.error('Cropper.js not found. Run: npm install');
    process.exit(1);
}

fs.rmSync(target, { recursive: true, force: true });
fs.mkdirSync(target, { recursive: true });

for (const file of files) {
    const srcPath = path.join(distDir, file);

    if (!fs.existsSync(srcPath)) {
        console.error(`Expected Cropper.js asset missing: ${file}`);
        process.exit(1);
    }

    fs.copyFileSync(srcPath, path.join(target, file));
}

console.log('Cropper.js copied to public/vendor/cropperjs');
