import { Controller } from '@hotwired/stimulus';
import axios from 'axios';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        console.log('Controller Stimulus CloudFlare: OK 4');
    }

    patch(event){
        let input = event.target

        axios.post('/cloudflare/api/patch/'+input.id, {value: event.target.checked ? 'on' : 'off' }).then( function (response){
            console.log(response);
        })
    }

    patchGroup(event){
        let mainParent = event.currentTarget.parentNode.parentNode
        let inputs = mainParent.querySelectorAll('input')
        let values = {}
        
        inputs.forEach(input => {
            values[input.id] = input.checked ? 'on' : 'off'
        });

        axios.post('/cloudflare/api/patch/'+mainParent.id, {value: values}).then( function (response){
            console.log(response);
        })
    }

    patchGroup(event){
        let mainParent = event.currentTarget.parentNode.parentNode
        let inputs = mainParent.querySelectorAll('input')
        let values = {}
        
        inputs.forEach(input => {
            values[input.id] = input.checked ? 'on' : 'off'
        });

        axios.post('/cloudflare/api/patch/'+mainParent.id, {value: values}).then( function (response){
            console.log(response);
        })
    }

    clickButton(event){
        console.log(event.params)
        console.log(event.params.elem)
        let elem = event.params.elem

        axios.post('/cloudflare/api/patch/'+elem, null).then( function (response){
            console.log(response);
        })
    }
}

