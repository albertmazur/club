import './bootstrap'

window.showErrorAlert = function(message){
    let mainElement = document.querySelector('main')

    let alertDiv = document.createElement('div')
    alertDiv.className = 'alert alert-danger alert-dismissible fade show my-3'
    alertDiv.role = 'alert'
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `

    mainElement.insertBefore(alertDiv, mainElement.firstChild)
}
