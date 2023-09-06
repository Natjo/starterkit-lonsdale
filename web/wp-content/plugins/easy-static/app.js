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
        pages_result.innerHTML = response.markup;
    }
}

// posts is active
const checkbox_static_active = pages_result.querySelectorAll(".checkbox-static_active");
checkbox_static_active.forEach((el) => {
    el.onchange = () => {

        if (!el.checked) {
            el.value = 0;
        } else {
           // el.parentNode.parentNode.querySelector('.info-update').classList.remove('error');
            el.value = 1;
        }

        const data = new FormData();
        data.append('action', "static_posts_his_active");
        data.append('nonce', nonce);
        data.append('id', el.id);
        data.append('slug', el.dataset.slug);
        data.append('status', el.checked);
        const xhr = new XMLHttpRequest();
        xhr.open("post", ajax_url, true);
        xhr.send(data);
        xhr.onload = () => { }
    }
})

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

    }

}


//
const input_host = document.getElementById("es-host");
input_host.onchange = () => {
    const data = new FormData();
    data.append('action', "static_change_host");
    data.append('nonce', nonce);
    data.append('host', input_host.value);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => { }
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
relative.oninput = (e) => {

    /* let value = relative.innerText;
     if (value.charAt(0) === '/') {
         value = value.substring(1)
     }
     if (value.charAt(value.length - 1) === "/") {
         value = value.slice(0, -1)
     }
     relative.innerText = value;
     if (relative.innerText.length > 1) {
         document.querySelector('.es-action').classList.remove('disabled');

     } else {
         document.querySelector('.es-action').classList.add('disabled');
     }*/
}

relative.addEventListener('keypress', (e) => {
    //if (e.which === 47)  e.preventDefault();
    if (e.which === 13) e.preventDefault();
});
relative.onblur = () => {
    if (relative.innerText.length > 1) {
        const data = new FormData();
        data.append('action', "static_export_slug");
        data.append('nonce', nonce);
        data.append('slug', relative.innerText);
        const xhr = new XMLHttpRequest();
        xhr.open("post", ajax_url, true);
        xhr.send(data);
        xhr.onload = () => { }
        document.querySelector('.es-action').classList.remove('disabled');

    } else {
        document.querySelector('.es-action').classList.add('disabled');
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
        btn_download_pages.classList.remove('loading');
    }
}

// zip all
const btn_zip_uploads = document.getElementById('es-zip-uploads');
const link_download_uploads = document.getElementById('es-download-uploads');
btn_zip_uploads.onclick = () => {
    btn_zip_uploads.classList.add('loading');
    const data = new FormData();
    data.append('action', "static_export_download_uploads");
    data.append('nonce', nonce);
    const xhr = new XMLHttpRequest();
    xhr.open("post", ajax_url, true);
    xhr.send(data);
    xhr.onload = () => {
        const response = JSON.parse(xhr.response);
        link_download_uploads.href = window.location.origin + "/wp-content/easy-static/export.zip";
        link_download_uploads.dowload = "export";
        link_download_uploads.style.display = "inline";
        btn_zip_uploads.classList.remove('loading');
    }
}
link_download_uploads.addEventListener('click', () => {
    setTimeout(() => {
        link_download_uploads.style.display = "none";
    }, 300);
});

// remov ezip
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