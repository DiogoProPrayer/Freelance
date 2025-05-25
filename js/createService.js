let filesArray = [];
const dropArea = document.getElementById('drop-area');
const input = document.getElementById('images');
const preview = document.getElementById('preview');
const dropText = document.getElementById('drop-text');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev => {
    dropArea.addEventListener(ev, e => {
    e.preventDefault();
    e.stopPropagation();
    dropArea.classList[(ev === 'dragenter' || ev === 'dragover') ? 'add' : 'remove']('hover');
    });
});

dropArea.addEventListener('drop', e => handleFiles(e.dataTransfer.files));

// Only open selector if clicking outside of a remove-btn
dropArea.addEventListener('click', e => {
    if (!e.target.classList.contains('remove-btn')) {
        input.click();
    }
});

input.addEventListener('change', () => handleFiles(input.files));

function handleFiles(files) {
    for (const f of files) {
        if (!filesArray.some(x => x.name === f.name && x.size === f.size)) {
            filesArray.push(f);
        }
    }
    updatePreview();
    updateInputFiles();
}

function updatePreview() {
    preview.innerHTML = '';
    dropText.style.display = filesArray.length ? 'none' : 'block';
    filesArray.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = e => {
            const thumb = document.createElement('div');
            thumb.className = 'thumb';
            const img = document.createElement('img');
            img.src = e.target.result;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'remove-btn';
            btn.innerHTML = '&times;';
            btn.onclick = () => {
                filesArray.splice(idx, 1);
                updatePreview();
                updateInputFiles();
            };
            thumb.append(img, btn);
            preview.appendChild(thumb);
        };
        reader.readAsDataURL(file);
    });
}

function updateInputFiles() {
    const dt = new DataTransfer();
    filesArray.forEach(f => dt.items.add(f));
    input.files = dt.files;
}
