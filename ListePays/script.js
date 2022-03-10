
document.addEventListener('DOMContentLoaded', function() {
    axios.get('https://api.tag-walk.com/api/users/countries').then(res => {
        const container = document.getElementById('listPays');
        const fragment = document.createDocumentFragment();
        for(const pays in res.data) {
            const item = document.createElement('li');
            item.textContent = `${ pays } : ${ res.data[pays] }`;
            fragment.appendChild(item);
        }
        container.appendChild(fragment);
    }).catch(error => {
        console.log(error);
    });
});