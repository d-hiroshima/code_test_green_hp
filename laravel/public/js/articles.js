const deleteArticle = () => {
    if (confirm('記事を削除しますか？')) {
        const deleteTarget = document.getElementById('delete');
        const id = deleteTarget.dataset.id;
        if (!id) {
            alert('削除に失敗しました。idが見つかりません。')
        }

        fetch(`/articles/${id}`, {
            method: 'delete',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(response => response.json())
            .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
            if (data.status === 'success') {
                alert('データを削除しました。')
            }
        }).catch(error => console.error('Error:', error));
    } else {
        return false;
    }
}

window.addEventListener('DOMContentLoaded', (e) => {
    const deleteTarget = document.getElementById('delete');
    deleteTarget.addEventListener('click', deleteArticle);
});
