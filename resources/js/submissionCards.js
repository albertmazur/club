document.addEventListener('DOMContentLoaded', function (){
    document.querySelectorAll('.submission-card').forEach(function (card){
        const switcher = card.querySelector('.submission-language-switcher')
        if (!switcher) return

        function updateLanguage(lang){
            card.querySelectorAll('.submission-content, .submission-comment')
                .forEach(el => el.classList.add('d-none'))

            card.querySelectorAll(`.submission-content[data-lang="${lang}"], .submission-comment[data-lang="${lang}"]`)
                .forEach(el => el.classList.remove('d-none'))
        }

        switcher.addEventListener('change', function (){
            updateLanguage(this.value)
        })

        updateLanguage(switcher.value)
    })
})