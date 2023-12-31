import {customElement, ka_session_storage, ka_sleep, KaCustomElement, KaHtmlElement, template} from "@kasimirjs/embed";
import {api_call, href, route, router} from "@kasimirjs/app";
import {CurRoute} from "@kasimirjs/app";
import {ThreadList} from "../components/ThreadList";
import {ThreadListWithFilters} from "../components/ThreadListWithFilters";


// language=html
let html = `
        
<div class="container-fluid" style="">
    <div class="row">
        <div class="col-12" style="height: 100vh">
            <h2>HiGNS Threads:</h2>
            <div class="h-100" ka.content="threadList"></div>
        </div>
    </div>
</div>


`



@customElement("index-page")
@route("gallery", "/static/{subscription_id}")
@template(html)
class IndexPage extends KaCustomElement {

    constructor(public route : CurRoute) {
        super();
        let scope = this.init({
            threadList: null
        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        this.scope.threadList = new ThreadListWithFilters(router.currentRoute.route_params["subscription_id"])



    }


    // language=html

}
