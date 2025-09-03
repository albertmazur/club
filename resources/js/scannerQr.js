import { Html5Qrcode } from "html5-qrcode"

let isScanning = false

function showResult(message, type = 'success'){
    const resultBox = document.getElementById('qr-result')
    const messageBox = document.getElementById('qr-message')

    resultBox.classList.remove('d-none', 'alert-success', 'alert-danger')
    resultBox.classList.add('alert', 'mt-2', 'alert-dismissible', 'fade', 'show')
    resultBox.classList.add(type === 'success' ? 'alert-success' : 'alert-danger')

    messageBox.innerText = message

    setTimeout(() => {
        resultBox.classList.add('d-none')
        resultBox.classList.remove('alert-success', 'alert-danger', 'show', 'fade')
    }, 5000)
}

function onScanFailure(error){
    isScanning = false
}

function onScanSuccess(decodedText){
    if (isScanning) return
    isScanning = true

    const qrElement = document.getElementById('qr-reader')
    const verifyUrl = qrElement.dataset.checkUrl

    fetch(verifyUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ token: decodedText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success){
            showResult(window.lang.ticket_valid.replace(':id', data.ticket_id), 'success')
        } else {
            showResult(window.lang.ticket_invalid.replace(':message', data.message), 'danger')
        }
    })
    .catch(error => {
        showResult(window.lang.ticket_error, 'danger')
    })
}

document.addEventListener("DOMContentLoaded", async () => {
    const qrReader = new Html5Qrcode("qr-reader", false)

    qrReader.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        onScanSuccess,
        onScanFailure
    )
    .catch(err => {
        showResult(window.lang.camera_error.replace(':error', err), 'danger')
    })
})