const getRedirectInput = document.querySelector('#redirect-to-create-post')

if (getRedirectInput != null) {
    getRedirectInput.addEventListener('click', () => {
        location.href = '/forums/new-post.php'
    })
}

const shareBtn = document.querySelector('.qa-post-button-share')

function copyURL(e) {
    window.location.reload()
    alert("Link do posta: " + window.location.toString())
}

shareBtn.addEventListener('click', copyURL)