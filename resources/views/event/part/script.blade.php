<script src="https://js.stripe.com/v3/"></script>
<script>
    let countTickets = document.getElementById('countTickets')
    if(countTickets !== null && countTickets !== 'undefined'){
        countTickets.addEventListener('input', (e) => {
            let price = parseFloat(document.getElementById('priceEvent').textContent)
            let sumPrice = parseInt(e.target.value)*price
            document.getElementById('sumPrice').textContent = sumPrice.toFixed(2)
        })
    }

    let reportButtons = document.querySelectorAll('.reportButton')

    if(reportButtons !== null && reportButtons !== 'undefined'){
        reportButtons.forEach(element => {
            element.addEventListener('click', (e) => {
                let registrationCommentModal = document.getElementById('registrationCommentModal')
                console.log(e.target.value)
                if(registrationCommentModal !== null && registrationCommentModal !== 'undefined'){
                    document.getElementById('comment_id').value = e.target.value
                }
            })
        })
    }

    const form = document.getElementById('payment-form')
    if(form) setupStripe()
    
    async function setupStripe(){
        const stripe = Stripe("{{ config('services.stripe.key') }}", {
            locale: 'pl'
        })

        const elements = stripe.elements()
        const cardNumber = elements.create('cardNumber')
        const cardExpiry = elements.create('cardExpiry')
        const cardCvc = elements.create('cardCvc')

        cardNumber.mount('#card-number')
        cardExpiry.mount('#card-expiry')
        cardCvc.mount('#card-cvc')

        
        const submitButton = form.querySelector("button[type='submit']")

        form.addEventListener('submit', async function(event){
            event.preventDefault()

            submitButton.disabled = true
            const originalText = submitButton.innerHTML
            submitButton.innerHTML = @json(__('stripe.processing_payment'))

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: "card",
                card: cardNumber,
            })

            if (error){
                showErrorAlert(error.message)
                submitButton.disabled = false
                submitButton.innerHTML = originalText
                return
            }

            let formData = new FormData(form)
            formData.append("payment_method", paymentMethod.id)

            fetch("{{ route('ticket.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success){
                    window.location.href = "{{ route('ticket.payment.status') }}"
                } else {
                    showErrorAlert(data.error)
                }
            })
            .catch(error => {
                showErrorAlert(error.message)
                submitButton.disabled = false
                submitButton.innerHTML = originalText
            })
        })
    }
</script>