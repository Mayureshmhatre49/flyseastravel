{{--
    Multi-image uploader with drag-to-reorder.
    The FIRST image in the list is the hero image; the rest become the gallery.

    Expects:
      $existing — ordered array of current image src strings (hero first, then gallery).
                  Pass [] on the create form.
--}}
@php
    // On a validation error, restore the order the admin had (existing images only —
    // freshly-picked files can't be repopulated into a file input by the browser).
    $oldOrder = old('image_order');
    if ($oldOrder !== null) {
        $decoded = json_decode($oldOrder, true) ?: [];
        $initialImages = collect($decoded)
            ->filter(fn ($e) => is_array($e) && ($e['type'] ?? '') === 'existing')
            ->pluck('value')
            ->filter()
            ->values()
            ->all();
    } else {
        $initialImages = array_values(array_filter($existing ?? []));
    }

    // Pre-seed the hidden field so images are preserved even if JS is disabled.
    $initialOrder = collect($initialImages)
        ->map(fn ($src) => ['type' => 'existing', 'value' => $src])
        ->values()
        ->all();
@endphp

<div class="img-uploader" id="imgUploader">
    <p class="img-uploader-help">
        Upload one or more images. <strong>Drag to reorder</strong> — the first image is used as the
        <strong>hero</strong> across the site; the rest fill the gallery. JPG, PNG, WEBP or GIF, up to 4&nbsp;MB each.
    </p>

    {{-- Ordered thumbnails live here --}}
    <div class="img-grid" id="imgGrid"></div>

    {{-- Empty-state hint (toggled by JS) --}}
    <p class="img-empty" id="imgEmpty">No images yet. Add your first image below — it becomes the hero.</p>

    {{-- Add-images tile (kept OUTSIDE the sortable grid) --}}
    <label class="img-add" id="imgAdd">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <span>Add images</span>
        {{-- Staging input: selections are moved into JS state, never submitted directly --}}
        <input type="file" id="imgStaging" accept="image/*" multiple hidden>
    </label>

    {{-- Submitted state --}}
    <input type="hidden" name="image_order" id="imgOrder" value="{{ json_encode($initialOrder) }}">
    <input type="file" name="new_images[]" id="imgFiles" multiple hidden>
</div>

<style>
    .img-uploader-help { font-size:13px; color:var(--fs-ink-muted); line-height:1.55; margin-bottom:14px; }
    .img-grid { display:flex; flex-wrap:wrap; gap:12px; }
    .img-grid:empty { display:none; }
    .img-thumb {
        position:relative; width:128px; height:128px; border-radius:var(--fs-r-md);
        overflow:hidden; border:1px solid var(--fs-line); background:var(--fs-bg-soft);
        cursor:grab; flex:0 0 auto; user-select:none;
    }
    .img-thumb:active { cursor:grabbing; }
    .img-thumb img { width:100%; height:100%; object-fit:cover; display:block; pointer-events:none; }
    .img-thumb.dragging { opacity:.4; }
    .img-thumb.is-hero { border-color:var(--fs-primary); box-shadow:0 0 0 2px var(--fs-primary); }
    .img-hero-badge {
        position:absolute; top:6px; left:6px; display:none; align-items:center; gap:3px;
        background:var(--fs-primary); color:#fff; font-size:10px; font-weight:700;
        letter-spacing:.04em; text-transform:uppercase; padding:3px 7px; border-radius:var(--fs-r-pill);
    }
    .img-thumb.is-hero .img-hero-badge { display:inline-flex; }
    .img-remove {
        position:absolute; top:6px; right:6px; width:22px; height:22px; border:none; cursor:pointer;
        border-radius:50%; background:rgba(17,17,17,.62); color:#fff; line-height:0;
        display:flex; align-items:center; justify-content:center; padding:0;
    }
    .img-remove:hover { background:#C0392B; }
    .img-pos {
        position:absolute; bottom:6px; left:6px; background:rgba(17,17,17,.62); color:#fff;
        font-size:11px; font-weight:600; padding:2px 7px; border-radius:var(--fs-r-pill);
    }
    .img-empty { font-size:13px; color:var(--fs-ink-soft); margin:10px 0 0; }
    .img-empty.hidden { display:none; }
    .img-add {
        margin-top:14px; display:inline-flex; align-items:center; gap:8px; cursor:pointer;
        padding:10px 16px; border:1px dashed var(--fs-line); border-radius:var(--fs-r-md);
        font-size:13px; font-weight:600; color:var(--fs-ink-muted); background:var(--fs-bg-soft);
    }
    .img-add:hover { border-color:var(--fs-primary); color:var(--fs-primary); }
</style>

<script>
(function () {
    const root    = document.getElementById('imgUploader');
    if (!root) return;
    const grid    = document.getElementById('imgGrid');
    const empty   = document.getElementById('imgEmpty');
    const staging = document.getElementById('imgStaging');
    const orderIn = document.getElementById('imgOrder');
    const filesIn = document.getElementById('imgFiles');
    const form    = root.closest('form');

    const fileMap = {};   // id -> File (for newly added images)
    let seq = 0;

    function makeThumb({ kind, src, url, id }) {
        const el = document.createElement('div');
        el.className = 'img-thumb';
        el.draggable = true;
        el.dataset.kind = kind;
        el.dataset.id = id;
        if (kind === 'existing') el.dataset.url = url;

        const img = document.createElement('img');
        img.src = src;
        img.alt = '';
        el.appendChild(img);

        const badge = document.createElement('span');
        badge.className = 'img-hero-badge';
        badge.textContent = '★ Hero';
        el.appendChild(badge);

        const pos = document.createElement('span');
        pos.className = 'img-pos';
        el.appendChild(pos);

        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'img-remove';
        remove.setAttribute('aria-label', 'Remove image');
        remove.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
        remove.addEventListener('click', function () {
            if (el.dataset.kind === 'new') {
                URL.revokeObjectURL(img.src);
                delete fileMap[el.dataset.id];
            }
            el.remove();
            refresh();
        });
        el.appendChild(remove);

        return el;
    }

    function addExisting(url) {
        grid.appendChild(makeThumb({ kind: 'existing', src: url, url: url, id: 'e' + (seq++) }));
    }

    function addNew(file) {
        const id = 'n' + (seq++);
        fileMap[id] = file;
        grid.appendChild(makeThumb({ kind: 'new', src: URL.createObjectURL(file), id: id }));
    }

    // Keep hero badge, position numbers and the empty-state in sync.
    function refresh() {
        const thumbs = grid.querySelectorAll('.img-thumb');
        thumbs.forEach(function (t, i) {
            t.classList.toggle('is-hero', i === 0);
            t.querySelector('.img-pos').textContent = (i + 1);
        });
        empty.classList.toggle('hidden', thumbs.length > 0);
    }

    // ---- Drag to reorder (works across a wrapping grid) ----
    grid.addEventListener('dragstart', function (e) {
        const t = e.target.closest('.img-thumb');
        if (!t) return;
        t.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });
    grid.addEventListener('dragend', function (e) {
        const t = e.target.closest('.img-thumb');
        if (t) t.classList.remove('dragging');
        refresh();
    });
    grid.addEventListener('dragover', function (e) {
        e.preventDefault();
        const dragging = grid.querySelector('.dragging');
        if (!dragging) return;
        const after = afterElement(e.clientX, e.clientY);
        if (after == null) grid.appendChild(dragging);
        else if (after !== dragging) grid.insertBefore(dragging, after);
        refresh();
    });

    function afterElement(x, y) {
        const els = [...grid.querySelectorAll('.img-thumb:not(.dragging)')];
        let best = null, bestDist = Infinity;
        els.forEach(function (el) {
            const b = el.getBoundingClientRect();
            const cx = b.left + b.width / 2;
            const cy = b.top + b.height / 2;
            const dist = Math.hypot(x - cx, y - cy);
            if (dist < bestDist) { bestDist = dist; best = { el: el, cx: cx }; }
        });
        if (!best) return null;
        return x < best.cx ? best.el : best.el.nextElementSibling;
    }

    // ---- Add images ----
    staging.addEventListener('change', function (e) {
        [...e.target.files].forEach(function (file) {
            if (file.type && file.type.startsWith('image/')) addNew(file);
        });
        staging.value = '';   // allow re-selecting the same file
        refresh();
    });

    // ---- Serialise on submit ----
    if (form) {
        form.addEventListener('submit', function () {
            const order = [];
            const dt = new DataTransfer();
            let newIdx = 0;
            grid.querySelectorAll('.img-thumb').forEach(function (t) {
                if (t.dataset.kind === 'existing') {
                    order.push({ type: 'existing', value: t.dataset.url });
                } else {
                    const file = fileMap[t.dataset.id];
                    if (file) {
                        dt.items.add(file);
                        order.push({ type: 'new', value: newIdx++ });
                    }
                }
            });
            orderIn.value = JSON.stringify(order);
            filesIn.files = dt.files;
        });
    }

    // ---- Initial render ----
    @json($initialImages).forEach(addExisting);
    refresh();
})();
</script>
