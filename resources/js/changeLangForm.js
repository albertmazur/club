document.addEventListener('DOMContentLoaded', function (){
    function currentLang() {
        const sel = document.getElementById('languageSwitcher')
        return sel ? sel.value : document.documentElement.lang || 'en'
    }

    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]')
        return meta ? meta.content : ''
    }

    function getField(prefix, lang) {
        return document.querySelector(`[name="${prefix}[${lang}]"]`)
    }

    function detectPrefixes() {
        const prefixes = new Set()
        document.querySelectorAll('[name*="["]').forEach(el => {
            const m = el.getAttribute('name').match(/^([a-zA-Z0-9_]+)\[[a-z]{2}\]$/)
            if (m) prefixes.add(m[1])
        })
        return Array.from(prefixes)
    }

    async function translateOrCopy(text, source, target) {
        const resp = await fetch('/translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
            body: JSON.stringify({ text, source, target }),
        })

        if (!resp.ok) {
            return text
        }
        const data = await resp.json().catch(() => ({}))
        return data.text ?? text
    }

    async function autoTranslateEmptyFromSelected() {
        const spinner = document.getElementById('autoTranslateSpinner')
        const form = document.getElementsByTagName('form')[0]

        spinner && spinner.classList.remove('d-none')
        form.style.marginTop = 'none'

        try {
            const src = currentLang()
            const prefixes = detectPrefixes()

            const sourceTexts = {}
            for (const p of prefixes) {
                const el = getField(p, src)
                if (!el) continue
                sourceTexts[p] = (el.value || '').trim()
            }

            const allLangs = Array.from(document.querySelectorAll('.i18n-field'))
            .map(div => div.getAttribute('data-lang'))
            .filter((v, i, a) => !!v && a.indexOf(v) === i)

            for (const lang of allLangs) {
                if (lang === src) continue

                for (const p of prefixes) {
                    const targetEl = getField(p, lang)
                    if (!targetEl) continue

                    const isEmpty = (targetEl.value || '').trim().length === 0
                    const srcText = (sourceTexts[p] || '').trim()
                    if (!isEmpty || !srcText) continue

                    const translated = await translateOrCopy(srcText, src, lang)
                    targetEl.value = translated
                    targetEl.dispatchEvent(new Event('input', { bubbles: true }))
                }
            }
        } catch (e) {
            console.error('Auto-translate error:', e)
        } finally {
            spinner && spinner.classList.add('d-none')
            form.style.marginTop = '100px'
        }
    }
    document.getElementById('autoTranslateBtn')?.addEventListener('click', autoTranslateEmptyFromSelected)


    const switcher = document.getElementById('languageSwitcher')
    const blocks = document.querySelectorAll('.i18n-field')

    function toggleLang(lang){
        blocks.forEach(block => {
            const isActive = block.dataset.lang === lang
            block.classList.toggle('d-none', !isActive)
        })
    }

    toggleLang(switcher.value)

    switcher.addEventListener('change', function (){
        toggleLang(this.value)
    })
})
