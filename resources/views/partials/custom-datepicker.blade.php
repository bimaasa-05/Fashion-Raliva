<style>
    .raliva-dp-panel { width: 288px; }
    .raliva-dp-panel .rdp-day { width: 100%; aspect-ratio: 1 / 1; }
    .rdp-day:disabled { opacity: 0.28; pointer-events: none; }
    @media (prefers-reduced-motion: reduce) {
        .raliva-dp-panel { transition: none !important; }
    }
</style>
<script>
(function () {
    if (window.__ralivaDatePickerStarted) return;
    window.__ralivaDatePickerStarted = true;

    var MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    var DAYS = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

    function pad(n) { return String(n).padStart(2, '0'); }
    function isoDate(y, m, d) { return y + '-' + pad(m) + '-' + pad(d); }
    function fmtDisplay(iso) {
        if (!iso) return '-- Pilih --';
        var p = iso.split('T');
        var d = p[0].split('-');
        var out = d[2] + '-' + d[1] + '-' + d[0];
        if (p[1]) out += ' ' + p[1].slice(0, 5);
        return out;
    }
    function newEl(tag, cls, text) {
        var el = document.createElement(tag);
        if (cls) el.className = cls;
        if (text !== undefined) el.textContent = text;
        return el;
    }
    function iconSpan(name, extra) {
        var s = newEl('span', 'material-symbols-outlined text-[18px] text-on-surface-variant shrink-0' + (extra || ''));
        s.textContent = name;
        return s;
    }
    function todayParts() {
        var t = new Date();
        return { y: t.getFullYear(), m: t.getMonth() + 1, d: t.getDate() };
    }
    function lastDayOf(y, m) { return new Date(y, m, 0).getDate(); }

    function stepBtn(kind, dir, icon, label) {
        var b = newEl('button', 'rdp-step w-9 h-9 flex items-center justify-center rounded-lg border border-muted-border bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant active:opacity-70 transition-colors cursor-pointer');
        b.type = 'button';
        b.setAttribute('data-kind', kind);
        b.setAttribute('data-dir', String(dir));
        b.setAttribute('aria-label', label);
        b.appendChild(iconSpan(icon));
        b.addEventListener('click', function (e) { e.stopPropagation(); stepTime(kind, dir); });
        return b;
    }

    function stepCol(kind, label) {
        var col = newEl('div', 'flex-1 flex flex-col items-center gap-1');
        var up = stepBtn(kind, 1, 'expand_less', 'Naik ' + label);
        var val = newEl('span', 'rdp-val text-lg font-bold text-on-surface', '00');
        val.dataset.kind = kind;
        var down = stepBtn(kind, -1, 'expand_more', 'Turun ' + label);
        col.appendChild(up);
        col.appendChild(val);
        col.appendChild(down);
        col.appendChild(newEl('span', 'text-[10px] font-bold uppercase tracking-wider text-on-surface-variant', label));
        return col;
    }

    function stepTime(kind, dir) {
        if (!active) return;
        var i = parseInt(kind === 'h' ? active.timeH : active.timeM, 10) || 0;
        i += dir;
        if (kind === 'h') {
            if (i < 0) i = 23;
            if (i > 23) i = 0;
            active.timeH = pad(i);
        } else {
            if (i < 0) i = 59;
            if (i > 59) i = 0;
            active.timeM = pad(i);
        }
        updateTimeUI();
    }

    function updateTimeUI() {
        if (!panel || !active) return;
        panel.querySelectorAll('.rdp-val[data-kind="h"]').forEach(function (el) { el.textContent = active.timeH; });
        panel.querySelectorAll('.rdp-val[data-kind="m"]').forEach(function (el) { el.textContent = active.timeM; });
        var st = panel.querySelector('.rdp-date-status');
        if (st) st.textContent = active.pendingDate ? 'Dipilih: ' + fmtDisplay(active.pendingDate) : '';
    }

    var panel = null;
    var active = null;

    var valueDesc = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value');

    function buildPanel() {
        panel = newEl('div', 'raliva-dp-panel hidden fixed bg-surface-container-lowest border border-muted-border rounded-xl shadow-2xl p-3 z-[9999]');
        panel.style.opacity = '0.98';
        document.body.appendChild(panel);

        var header = newEl('div', 'flex items-center justify-between gap-2 mb-2');
        var prev = newEl('button', 'rdp-nav w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-high text-on-surface-variant transition-colors cursor-pointer');
        prev.type = 'button';
        prev.setAttribute('aria-label', 'Bulan sebelumnya');
        prev.appendChild(iconSpan('chevron_left'));
        var title = newEl('div', 'rdp-title font-label-sm text-label-sm uppercase tracking-wider text-on-surface text-center flex-1 truncate');
        var next = newEl('button', 'rdp-nav w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-high text-on-surface-variant transition-colors cursor-pointer');
        next.type = 'button';
        next.setAttribute('aria-label', 'Bulan berikutnya');
        next.appendChild(iconSpan('chevron_right'));
        header.appendChild(prev);
        header.appendChild(title);
        header.appendChild(next);
        panel.appendChild(header);

        var weekday = newEl('div', 'grid grid-cols-7 gap-1 mb-1');
        for (var i = 1; i <= 6; i++) weekday.appendChild(newEl('div', 'text-center text-[10px] font-bold uppercase text-on-surface-variant py-1', DAYS[i]));
        weekday.appendChild(newEl('div', 'text-center text-[10px] font-bold uppercase text-red-500/70 py-1', DAYS[0]));
        panel.appendChild(weekday);

        var grid = newEl('div', 'rdp-grid grid grid-cols-7 gap-1');
        panel.appendChild(grid);

        var timeRow = newEl('div', 'rdp-time hidden mt-3 pt-3 border-t border-muted-border');
        timeRow.appendChild(newEl('div', 'rdp-date-status font-body-md text-body-md text-on-surface mb-2'));
        timeRow.appendChild(newEl('div', 'font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant mb-2', 'Jam'));
        var timeSel = newEl('div', 'flex items-start justify-center gap-3');
        timeSel.appendChild(stepCol('h', 'Jam'));
        timeSel.appendChild(newEl('span', 'text-xl font-bold text-on-surface-variant pt-7', ':'));
        timeSel.appendChild(stepCol('m', 'Menit'));
        timeRow.appendChild(timeSel);
        var saveBtn = newEl('button', 'rdp-save mt-3 block w-full btn-modal btn-modal-primary py-2 text-center');
        saveBtn.type = 'button';
        saveBtn.textContent = 'Simpan';
        timeRow.appendChild(saveBtn);
        panel.appendChild(timeRow);

        var foot = newEl('div', 'flex items-center justify-between mt-3 pt-2 border-t border-muted-border');
        var todayBtn = newEl('button', 'rdp-today text-xs font-bold text-gold-accent hover:underline cursor-pointer');
        todayBtn.type = 'button';
        todayBtn.textContent = 'Hari Ini';
        foot.appendChild(todayBtn);
        var okBtn = newEl('button', 'rdp-ok text-xs font-bold text-gold-accent hover:underline cursor-pointer');
        okBtn.type = 'button';
        okBtn.textContent = 'Tutup';
        foot.appendChild(okBtn);
        panel.appendChild(foot);

        prev.addEventListener('click', function (e) { e.stopPropagation(); shiftMonth(-1); });
        next.addEventListener('click', function (e) { e.stopPropagation(); shiftMonth(1); });
        todayBtn.addEventListener('click', function (e) { e.stopPropagation(); pickToday(); });
        okBtn.addEventListener('click', function (e) { e.stopPropagation(); closePanel(); });
        saveBtn.addEventListener('click', function (e) { e.stopPropagation(); applyDateTime(); });
        panel.addEventListener('click', function (e) { e.stopPropagation(); });
    }

    var view = { y: 0, m: 0 };

    function shiftMonth(delta) {
        view.m += delta;
        if (view.m < 1) { view.m = 12; view.y--; }
        if (view.m > 12) { view.m = 1; view.y++; }
        renderMonth();
    }

    function inRange(y, m, d) {
        var a = active;
        if (!a || (!a.input.min && !a.input.max)) return true;
        var iso = isoDate(y, m, d);
        if (a.input.min && iso < a.input.min) return false;
        if (a.input.max && iso > a.input.max) return false;
        return true;
    }

    function renderMonth() {
        if (!panel) return;
        var t = todayParts();
        panel.querySelector('.rdp-title').textContent = MONTHS[view.m - 1] + ' ' + view.y;
        var grid = panel.querySelector('.rdp-grid');
        grid.innerHTML = '';
        var firstDow = new Date(view.y, view.m - 1, 1).getDay();
        var lead = firstDow === 0 ? 6 : firstDow - 1;
        var last = lastDayOf(view.y, view.m);
        for (var i = 0; i < lead; i++) grid.appendChild(newEl('div', ''));
        for (var day = 1; day <= last; day++) {
            var btn = newEl('button', 'rdp-day flex items-center justify-center rounded-lg text-sm font-medium transition-colors cursor-pointer hover:bg-surface-container-high');
            btn.type = 'button';
            btn.textContent = day;
            var iso = isoDate(view.y, view.m, day);
            if (!inRange(view.y, view.m, day)) btn.disabled = true;
            if (active && (active.isDatetime ? (active.pendingDate || active.iso) : active.iso) === iso) {
                btn.classList.add('bg-gold-accent', 'text-white', 'ring-2', 'ring-gold-accent/60');
            } else if (view.y === t.y && view.m === t.m && day === t.d) {
                btn.classList.add('ring-1', 'ring-gold-accent');
            }
            btn.setAttribute('data-iso', iso);
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (active) active.pendingDate = this.getAttribute('data-iso');
                onDayPick(this.getAttribute('data-iso'));
            });
            grid.appendChild(btn);
        }
    }

    function onDayPick(iso) {
        if (!active) return;
        if (active.isDatetime) {
            var pr = iso.split('-');
            active.pendingDate = iso;
            var h = active.timeH, mm = active.timeM;
            active.dateObj = { y: +pr[0], m: +pr[1], d: +pr[2], h: +h, min: +mm };
            panel.querySelector('.rdp-time').classList.remove('hidden');
            renderMonth();
            updateTimeUI();
        } else {
            setValue(active, iso);
            closePanel();
        }
    }

    function pickToday() {
        var t = todayParts();
        if (active && inRange(t.y, t.m, t.d)) {
            if (active.isDatetime) {
                active.pendingDate = isoDate(t.y, t.m, t.d);
                onDayPick(active.pendingDate);
            } else {
                setValue(active, isoDate(t.y, t.m, t.d));
                closePanel();
            }
        }
    }

    function applyDateTime() {
        if (!active || !active.pendingDate) return;
        var h = active.timeH;
        var m = active.timeM;
        setValue(active, active.pendingDate + 'T' + h + ':' + m);
        closePanel();
    }

    function positionPanel(rect) {
        var pw = panel.offsetWidth || 288;
        var ph = panel.offsetHeight;
        var left = rect.left;
        var top = rect.bottom + 8;
        if (top + ph > window.innerHeight - 8) top = Math.max(8, rect.top - ph - 8);
        if (left + pw > window.innerWidth - 8) left = Math.max(8, window.innerWidth - pw - 8);
        panel.style.left = left + 'px';
        panel.style.top = top + 'px';
    }

    function syncDisabled(inst) {
        var d = inst.input.disabled;
        inst.display.disabled = d;
        inst.display.classList.toggle('opacity-40', d);
        inst.display.classList.toggle('cursor-not-allowed', d);
    }

    function refresh(inst) {
        var iso = inst.input.value;
        inst.iso = iso || null;
        inst.pendingDate = null;
        inst.display.querySelector('.raliva-dp-label').textContent = fmtDisplay(iso);
        inst.display.querySelector('.raliva-dp-label').classList.toggle('text-on-surface-variant', !iso);
        if (iso && inst.isDatetime && inst.input.value.indexOf('T') >= 0) {
            var tp = inst.input.value.split('T')[1].split(':');
            inst.timeH = tp[0]; inst.timeM = tp[1];
        }
        syncDisabled(inst);
        if (active === inst) {
            if (inst.iso) {
                var pr = inst.iso.split('T')[0].split('-');
                view.y = +pr[0]; view.m = +pr[1];
            } else {
                var t = todayParts(); view.y = t.y; view.m = t.m;
            }
            if (panel && !panel.classList.contains('hidden')) renderMonth();
        }
        validatePromo(inst);
    }

    function openPanel(inst) {
        var a = inst;
        if (a.input.disabled) return;
        closePanel();
        active = a;
        panel.querySelector('.rdp-time').classList.add('hidden');
        updateTimeUI();
        refresh(a);
        renderMonth();
        var r = a.display.getBoundingClientRect();
        panel.classList.remove('hidden');
        positionPanel(r);
    }

    function closePanel() {
        if (panel) {
            panel.classList.add('hidden');
            panel.querySelector('.rdp-time').classList.add('hidden');
        }
        active = null;
    }

    function setValue(inst, iso) {
        inst.input.value = iso == null ? '' : iso;
    }

    function validatePromo(inst) {
        var nama = inst.input.getAttribute('name');
        if (nama !== 'mulai_pada' && nama !== 'berakhir_pada') return;
        var form = inst.input.form || inst.input.closest('form');
        if (!form) return;
        var b = form.querySelector('[name="berakhir_pada"]');
        var m = form.querySelector('[name="mulai_pada"]');
        var msg = null;
        if (b && m && b.value && m.value && b.value < m.value) {
            msg = 'Tanggal berakhir harus setelah tanggal mulai.';
        }
        removeError(inst);
        if (msg) {
            var p = newEl('p', 'raliva-dp-err text-error text-xs mt-1', msg);
            p.id = 'rdp-err-' + (inst.input.id || inst.input.getAttribute('name'));
            inst.wrap.parentNode.insertBefore(p, inst.wrap.nextSibling);
        }
        if (b) {
            var ber = b.closest('.raliva-dp-wrap');
            if (ber) ber.querySelector('.raliva-dp-display').classList.toggle('border-error', !!msg);
        }
    }

    function removeError(inst) {
        var el = inst.wrap.parentNode.querySelector('#rdp-err-' + (inst.input.id || inst.input.getAttribute('name')));
        if (el) el.remove();
        inst.wrap.querySelector('.raliva-dp-display').classList.remove('border-error');
        var form = inst.input.form || inst.input.closest('form');
        if (form && inst.input.id === 'berakhir_pada') {
            var ber = form.querySelector('[name="berakhir_pada"]');
            if (ber) {
                var bw = ber.closest('.raliva-dp-wrap');
                if (bw) bw.querySelector('.raliva-dp-display').classList.remove('border-error');
            }
        }
        var kerja = form ? form.querySelector('[name="mulai_pada"]') : null;
        if (kerja) {
            var kw = kerja.closest('.raliva-dp-wrap');
            if (kw) kw.querySelector('.raliva-dp-display').classList.remove('border-error');
        }
    }

    function enhance(input) {
        if (!input || input.dataset.rdpDone) return;
        if (input.closest('.raliva-dp-wrap')) return;
        if (input.type !== 'date' && input.type !== 'datetime-local') return;
        input.dataset.rdpDone = '1';

        var isDatetime = input.type === 'datetime-local';
        var wrap = newEl('span', 'raliva-dp-wrap relative block w-full');
        var display = document.createElement('button');
        display.type = 'button';
        var orig = input.getAttribute('class') || '';
        display.className = orig + ' raliva-dp-display w-full flex items-center justify-between gap-2 text-left cursor-pointer select-none';
        var lbl = newEl('span', 'raliva-dp-label truncate flex-1');
        display.appendChild(lbl);
        display.appendChild(iconSpan('calendar_month'));

        input.parentNode.insertBefore(wrap, input);
        input.type = 'hidden';
        wrap.appendChild(input);
        wrap.appendChild(display);

        var inst = {
            input: input,
            wrap: wrap,
            display: display,
            isDatetime: isDatetime,
            iso: null,
            pendingDate: null,
            timeH: '00',
            timeM: '00',
            dateObj: null
        };

        Object.defineProperty(input, 'value', {
            configurable: true,
            get: function () { return valueDesc.get.call(input); },
            set: function (v) {
                valueDesc.set.call(input, v === null || v === undefined ? '' : String(v));
                requestAnimationFrame(function () { refresh(inst); });
            }
        });

        display.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            openPanel(inst);
        });

        refresh(inst);

        new MutationObserver(function () { syncDisabled(inst); }).observe(input, { attributes: true, attributeFilter: ['disabled'] });
    }

    function scan(root) {
        if (!root) return;
        var els = root.querySelectorAll ? root.querySelectorAll('input[type="date"], input[type="datetime-local"]') : [];
        for (var i = 0; i < els.length; i++) enhance(els[i]);
    }

    if (!panel) buildPanel();

    document.addEventListener('click', function (e) {
        if (active && !e.target.closest('.raliva-dp-wrap') && !e.target.closest('.raliva-dp-panel')) {
            closePanel();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && panel && !panel.classList.contains('hidden')) closePanel();
    });

    var repositioning = false;
    function reposition() {
        if (!active || !panel || panel.classList.contains('hidden')) return;
        var r = active.display.getBoundingClientRect();
        positionPanel(r);
        if (r.bottom < 0 || r.top > window.innerHeight) closePanel();
    }
    window.addEventListener('scroll', function () {
        if (!repositioning) { repositioning = true; requestAnimationFrame(function () { repositioning = false; reposition(); }); }
    }, true);
    window.addEventListener('resize', reposition);

    scan(document);

    var mo = new MutationObserver(function (muts) {
        for (var i = 0; i < muts.length; i++) {
            var added = muts[i].addedNodes;
            for (var j = 0; j < added.length; j++) {
                if (added[j].nodeType !== 1) continue;
                if (added[j].matches && added[j].matches('input[type="date"], input[type="datetime-local"]')) enhance(added[j]);
                scan(added[j]);
            }
        }
    });
    mo.observe(document.body, { childList: true, subtree: true });
})();
</script>