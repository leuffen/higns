import {customElement, ka_session_storage, ka_sleep, KaCustomElement, KaHtmlElement, template} from "@kasimirjs/embed";
import {api_call, href, route, router} from "@kasimirjs/app";
import {currentRoute} from "@kasimirjs/app";
import {CurRoute} from "@kasimirjs/app";
import {API} from "../_routes";
import {DefaultModal, FlexModal} from "@kasimirjs/kit-bootstrap";
import {ThreadMessageList} from "../components/ThreadMessageList";
import {ThreadListWithFilters} from "../components/ThreadListWithFilters";

// language=html
let html = `
        
<div class="container-fluid position-relative" style="height:100vh">
    <div class="position-absolute w-100 bg-light" style="top:0; height: 60px;">
        <div class="row w-100">
            <div class="col-6">
                <img src="/static/higns-logo.webp" style="height: 60px" class="float-start me-2">
                <h1 class="m-1">HiGNS: [[thread_id]]</h1>
            </div>
            <div class="col-6">
                info
            </div>
            
        </div>
    </div>
    <div class="position-absolute w-100" style="top:60px; bottom: 60px;">
        <div class="row h-100">
            <div class="col-3 h-100 bg-light" ">
                <div class="h-100" ka.content="threadMetaList"></div>
            </div>
            <div class="col-4 h-100 overflow-scroll">
                <div ka.content="threadList"></div>
            </div>
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
            threadMetaList: null,
            subscription_id: router.currentRoute.route_params.subscription_id,
            thread_id: router.currentRoute.route_params.thread_id
        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        this.scope.threadList = new ThreadMessageList(this.scope.subscription_id, this.scope.thread_id)
        this.scope.threadMetaList = new ThreadListWithFilters(this.scope.subscription_id)

    }


    // language=html

}
