const reply = document.querySelectorAll('.article-comment-button-comment a')
const textarea = document.querySelector('.new-comment-text')
const hiddenId = document.querySelectorAll('.article-comment-user-login')

reply.forEach((element, id) => {
    element.addEventListener('click', () => textarea.value = `@${hiddenId[id].innerText} `)
})

const shareBtn = document.querySelector('.article-post-button-share')

function copyURL() {
    window.location.reload()
    alert("Link do posta: " + window.location.toString())
}

shareBtn.addEventListener('click', copyURL)