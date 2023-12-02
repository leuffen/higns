import {customElement, ka_session_storage, ka_sleep, KaCustomElement, KaHtmlElement, template} from "@kasimirjs/embed";
import {api_call, href, route, router} from "@kasimirjs/app";
import {currentRoute} from "@kasimirjs/app";
import {CurRoute} from "@kasimirjs/app";
import {API} from "../_routes";
import {DefaultModal, FlexModal} from "@kasimirjs/kit-bootstrap";

// language=html
let html = `
        
<div class="container-fluid" style="height: 50000px">
    <div class="row">
        <h2>Thread:</h2>
        <div>
            <button ka.on.click="$fn.initialize()">Initialize (Copy all struff from _root to /opt)</button>
        </div>
        <div class="mt-4">
            <h2>Ai Generate Data files</h2>
            <select ka.options="fileList" ka.bind="$scope.file"></select>
            <button ka.on.click="$fn.aiGenerate($this)">AI Generate</button>
        </div>
    </div>
</div>


`

@customElement("thread-page")
@route("initialize", "/static/{subscription_id}/thread/{thread_id}")
@template(html)
class ThreadPage extends KaCustomElement {

    constructor(public route : CurRoute) {
        super();
        let scope = this.init({

        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();



    }


    // language=html

}
