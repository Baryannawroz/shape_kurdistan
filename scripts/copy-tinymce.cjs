const fs = require('fs');
const path = require('path');

const src = path.join(__dirname, '..', 'node_modules', 'tinymce');
const dest = path.join(__dirname, '..', 'public', 'vendor', 'tinymce');

if (! fs.existsSync(src)) {
    process.stderr.write('copy-tinymce: node_modules/tinymce not found (skip)\n');

    process.exit(0);
}

fs.rmSync(dest, { recursive: true, force: true });
fs.cpSync(src, dest, { recursive: true });
process.stdout.write('copy-tinymce: published to public/vendor/tinymce\n');
