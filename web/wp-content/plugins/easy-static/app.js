/* 
index 
*/

const main = document.getElementById('es-main');
const pages_result = document.getElementById('plug-static-pages');
const btn_generate = document.querySelector('.plug-static-btn-generate');
const nonce = main.getAttribute('data-nonce');
const ajax_url = main.getAttribute('data-ajaxurl');
const toogle_status = document.getElementById("plug-static-toggle-status");
toogle_status.checked = Boolean(main.getAttribute('data-static'));

btn_generate.onclick = () => {
    document.getElementById('pages').classList.add('disabled');
    if (document.querySelector('.es-notification')) {
        document.querySelector('.es-notification').remove();
    }

    // set haschange to true if page/post is edited
    btn_generate.classList.add('loading');
    const data = new FormData();
    data.append('action', "test");
    data.append('nonce', nonce);
    data.append('status', toogle_status.checked);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {
        btn_generate.classList.remove('loading');
        document.getElementById('pages').classList.remove('disabled');

        const response = JSON.parse(xhr.responseText);
        // pages_result.innerHTML = response.markup;

        // window.location.reload();
    }
}



// Switch mode 
if (toogle_status.checked) {
    document.getElementById('pages').classList.remove('disabled');
} else {
    document.getElementById('pages').classList.add('disabled');
}

toogle_status.onchange = () => {
    const data = new FormData();

    if (toogle_status.checked) {
        document.getElementById('pages').classList.remove('disabled');
    } else {
        document.getElementById('pages').classList.add('disabled');
    }

    data.append('action', "static_change_status");
    data.append('nonce', nonce);
    data.append('status', toogle_status.checked);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {
        btn_generate.disabled = false;
        toogle_status.disabled = false;
        window.location.reload();
    }
}



// tabs
const tab_links = document.querySelectorAll('.nav-tab-wrapper .nav-tab');
const tab_content = document.querySelectorAll('.tab-content');

tab_content.forEach((tab, i) => {
    tab.style.display = tab.id === 'pages' ? 'block' : 'none'
})

tab_links.forEach(link => {
    link.onclick = e => {
        e.preventDefault();

        tab_links.forEach(aa => {
            if (aa === link)
                aa.classList.add('nav-tab-active');
            else aa.classList.remove('nav-tab-active');
        })

        const id = link.getAttribute('href');
        tab_content.forEach(tab => {
            tab.style.display = '#' + tab.id === id ? 'block' : 'none'
        })
    }
})

/*
export
 */

const relative = document.getElementById('es-relative');

relative.addEventListener('keypress', (e) => {
    //if (e.which === 47)  e.preventDefault();
    if (e.which === 13) e.preventDefault();
});
relative.onblur = () => {
    let value = relative.innerText;
   
    if (value.charAt(0) === '/') {
        value = value.substring(1)
    }
    if (value.charAt(value.length - 1) === "/") {
        value = value.slice(0, -1)
    }

    const removeAccents = str => str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    value = removeAccents(value);
    value = value.replace(/[^a-z0-9/\-_]/gmi, "");

    relative.innerText = value;

    const data = new FormData();
    data.append('action', "static_export_slug");
    data.append('nonce', nonce);
    data.append('slug', value);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => { }
    if (document.querySelector('.es-action')) {
        document.querySelector('.es-action').classList.remove('disabled');
    }
}



// generate pages
const btn_download_pages = document.getElementById('es-download-pages');
btn_download_pages.onclick = () => {
    btn_download_pages.classList.add('loading');
    const data = new FormData();
    data.append('action', "static_export_pages");
    data.append('nonce', nonce);
    data.append('slug', relative.innerText);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {
        zip()
    }
}

function zip() {
    const data = new FormData();
    data.append('action', "static_export_download_no_uploads");
    data.append('nonce', nonce);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {
        const response = JSON.parse(xhr.response);
        link_download_uploads.href = window.location.origin + "/wp-content/easy-static/export.zip";
        link_download_uploads.dowload = "export";
        link_download_uploads.style.display = "inline";
        btn_download_pages.classList.remove('loading');
    }
}

const link_download_uploads = document.getElementById('es-download-uploads');
link_download_uploads.addEventListener('click', () => {
    setTimeout(() => {
        link_download_uploads.style.display = "none";
    }, 300);
});


// remove zip
const btn_zip_remove = document.getElementById('es-zip-remove');
btn_zip_remove.onclick = () => {
    btn_zip_remove.classList.add('loading');
    const data = new FormData();
    data.append('action', "static_export_download_remove");
    data.append('nonce', nonce);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {
        btn_zip_remove.classList.remove('loading');
    }
}


// Authentification
const auth_user_input = document.getElementById("es-auth-user");
const auth_password_input = document.getElementById("es-auth-password");
function authentification() {
    const data = new FormData();
    data.append('action', "static_authentification");
    data.append('nonce', nonce);
    data.append('user', auth_user_input.value);
    data.append('password', auth_password_input.value);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {

    }
}
if (auth_user_input) auth_user_input.onchange = () => authentification()

if (auth_password_input) auth_password_input.onchange = () => authentification()



// Options
const minify = document.getElementById("es-option-minify");

function _minify() {
    const data = new FormData();
    data.append('action', "static_minify");
    data.append('nonce', nonce);
    data.append('minify', minify.checked);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {
    }
}

minify.onchange = () => {
    _minify();
}
