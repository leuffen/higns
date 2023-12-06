import {customElement, ka_session_storage, ka_sleep, KaCustomElement, KaHtmlElement, template} from "@kasimirjs/embed";
import {api_call, href, route, router} from "@kasimirjs/app";
import {currentRoute} from "@kasimirjs/app";
import {CurRoute} from "@kasimirjs/app";
import {API} from "../_routes";
import {DefaultModal, FlexModal} from "@kasimirjs/kit-bootstrap";
import {ThreadMessageList} from "../components/ThreadMessageList";

// language=html
let html = `
        
<div class="container-fluid" style="">
    <h1>Messages: [[thread_id]]</h1>
    <div class="row">
        <div class="col-3">
        </div>
        <div class="col-9">
            <div ka.content="threadList"></div>
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
            threadList: null,
            subscription_id: router.currentRoute.route_params.subscription_id,
            thread_id: router.currentRoute.route_params.thread_id
        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        this.scope.threadList = new ThreadMessageList(this.scope.subscription_id, this.scope.thread_id)


    }


    // language=html

}
