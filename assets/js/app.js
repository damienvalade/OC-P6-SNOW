import Cropper from 'cropperjs/dist/cropper'
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router'
import Routes from './routes'

import axios from 'axios'

Routing.setRoutingData(Routes)

var cropper
var preview = document.getElementById('avatar')
var file_input = document.getElementById('update_form_image')

window.previewFile = function () {

    let file = file_input.files[0]
    let reader = new FileReader()

    reader.addEventListener('load', function (event) {
        preview.src = reader.result
    }, false)

    if (file) {
        reader.readAsDataURL(file)
    }

    preview.addEventListener('load', function () {
        if(cropper !== undefined){
            cropper.destroy()
        }
        cropper = new Cropper(preview, {
            aspectRatio: 1 / 1,
            movable: false,
            rotatable: false,
            viewMode: 2
        })

    })
}


let form = document.getElementById('form_update')
form.addEventListener('submit', function (event) {
    event.preventDefault()
    cropper.getCroppedCanvas({
        maxHeight: 1000,
        maxWidth: 1000
    }).toBlob(function (blob) {
        ajaxWithAxios(blob)
    })
})

function ajaxWithAxios(blob) {
    let url = Routing.generate('app_settings')
    let data = new FormData(form)
    data.append('file', blob)
    axios({
        method: 'POST',
        url: url,
        data: data,
        headers: {'X-Requested-With' : 'XMLHttpRequest'}
    })
        .then((response) => {

            if( response.data === 'redirect')
            {
                window.location.replace('/settings')
            }
        })
        .catch((error) => {
            console.error(error)
        })
}