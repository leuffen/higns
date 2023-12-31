import {customElement, ka_session_storage, ka_sleep, KaCustomElement, KaHtmlElement, template} from "@kasimirjs/embed";
import {api_call, href, route, router} from "@kasimirjs/app";
import {currentRoute} from "@kasimirjs/app";
import {CurRoute} from "@kasimirjs/app";
import {API} from "../_routes";
import {DefaultModal, FlexModal} from "@kasimirjs/kit-bootstrap";
import {ThreadMessageList} from "../components/ThreadMessageList";
import {ThreadListWithFilters} from "../components/ThreadListWithFilters";
import {ThreadMetaDetails} from "../components/ThreadMetaDetails";
import {ThreadAttachmentList} from "../components/ThreadAttachmentList";

// language=html
let html = `
        
<div class="container-fluid position-relative" style="height:100vh">
    <!-- Header -->
    <div class="position-absolute w-100 bg-light" style="top:0; height: 60px;">
        <div class="row w-100">
            <div class="col-6">
                <img src="/static/higns-logo.webp" style="height: 60px" class="float-start me-2">
                <h1 class="m-1">HiGNS</h1>
            </div>
            <div class="col-6">
                info
            </div>
            
        </div>
    </div>
    <div class="position-absolute w-100" style="top:60px; bottom: 60px;">
        <div class="row h-100">
            <!-- Sidebar -->
            <div class="col-3 h-100 bg-light" ">
                <div class="h-100" ka.content="threadMetaList"></div>
            </div>
        
            <!-- Message List -->
            <div class="col-4 h-100 position-relative">
                <div ka.content="threadMetaDetails" class="position-absolute top-0 start-0 end-0 bg-light" style="height: 150px"></div>
                <div class="position-absolute bottom-0 w-100 overflow-scroll" ka.content="threadList" style="top: 150px"></div>

            </div>
        
            <!-- Right Bar -->
        
            <div class="col-5 h-100 bg-light d-flex flex-column ">
                <div>Top</div>
                <div class="flex-grow-1 w-100">Top</div>
                <div class="w-100 border-top overflow-scroll" style="height: 30%" ka.content="threadMedia"></div>
                
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
            threadMetaDetails: null,
            threadMedia: null,
            subscription_id: router.currentRoute.route_params.subscription_id,
            thread_id: router.currentRoute.route_params.thread_id
        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        this.scope.threadList = new ThreadMessageList(this.scope.subscription_id, this.scope.thread_id)
        this.scope.threadMetaList = new ThreadListWithFilters(this.scope.subscription_id, this.scope.thread_id)
        this.scope.threadMetaDetails = new ThreadMetaDetails(this.scope.subscription_id, this.scope.thread_id)
        this.scope.threadMedia = new ThreadAttachmentList(this.scope.subscription_id, this.scope.thread_id)
    }


    // language=html

}
