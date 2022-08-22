const slugify = require('slugify');

document.addEventListener('DOMContentLoaded', () => {
    const titleElt = document.getElementById('title');
    const slugElt = document.getElementById('slug');

    titleElt.addEventListener('change', (e) => {
        slugElt.value = slugify(e.target.value).toLowerCase();
    })
})
